package codecanyon.servpro;

import android.Manifest;
import android.app.DatePickerDialog;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Environment;
import android.provider.MediaStore;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;
import androidx.core.content.FileProvider;

import com.google.android.material.textfield.TextInputLayout;
import com.karumi.dexter.Dexter;
import com.karumi.dexter.MultiplePermissionsReport;
import com.karumi.dexter.PermissionToken;
import com.karumi.dexter.listener.PermissionRequest;
import com.karumi.dexter.listener.multi.MultiplePermissionsListener;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import Config.BaseURL;
import codecanyon.servpro.databinding.ActivityEditProfileBinding;
import kotlin.jvm.Throws;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.ImageComprasser;
import util.JSONParser;
import util.NameValuePair;
import util.Session_management;
import util.UploadPicture;

public class Edit_profileActivity extends AppCompatActivity implements View.OnClickListener, AdapterView.OnItemSelectedListener {

    private static String TAG = RegisterActivity.class.getSimpleName();

    private static final int CAMERA_CAPTURE_IMAGE_REQUEST_CODE1 = 101;
    private static final int GALLERY_REQUEST_CODE1 = 201;

    public static final int MEDIA_TYPE_IMAGE = 1;
    public static final int MEDIA_TYPE_GALLERY = 2;

    private Uri fileUri;
    File imagefile1 = null;

    private String get_dob;
    private String firstname, lastname, email, dob, mobile, address, city, gender;

    private Session_management sessionManagement;

    private ActivityEditProfileBinding binding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityEditProfileBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        sessionManagement = new Session_management(this);

        ArrayList<String> gender = new ArrayList<>();
        gender.add(getResources().getString(R.string.male));
        gender.add(getResources().getString(R.string.female));

        // bind adapter of gender value
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, R.layout.row_spinner_text, R.id.tv_sp, gender);
        binding.spProfileGender.setAdapter(adapter);

        String fistname = sessionManagement.getUserDetails().get(BaseURL.KEY_NAME);
        String email = sessionManagement.getUserDetails().get(BaseURL.KEY_EMAIL);
        String mobile = sessionManagement.getUserDetails().get(BaseURL.KEY_MOBILE);
        String dob = sessionManagement.getUserDetails().get(BaseURL.KEY_BDATE);
        String getgender = sessionManagement.getUserDetails().get(BaseURL.KEY_GENDER);
        String getaddress = sessionManagement.getUserDetails().get(BaseURL.KEY_ADDRESS);
        String getcity = sessionManagement.getUserDetails().get(BaseURL.KEY_CITY);
        String getimage = sessionManagement.getUserDetails().get(BaseURL.KEY_IMAGE);

        String[] separated = fistname.split(" ");
        binding.etProfileFirstname.setText(separated[0]);
        binding.etProfileLastname.setText(separated[1]);

        binding.etProfileEmail.setText(email);
        binding.etProfileMobile.setText(mobile);
        binding.tvProfileDob.setText(dob);

        if (getaddress != null && !getaddress.isEmpty()) {
            binding.spProfileGender.setSelection(gender.indexOf(getgender));
            binding.etProfileAddress.setText(getaddress);
            binding.etProfileCity.setText(getcity);
        }

        if (getimage != null && !getimage.isEmpty()) {
            Picasso.with(this)
                    .load(BaseURL.IMG_PROFILE_URL + getimage)
                    .placeholder(R.mipmap.ic_launcher)
                    .into(binding.ivProfileImg);
        }

        binding.etProfileEmail.setKeyListener(null);

        binding.ivProfileDob.setOnClickListener(this);
        binding.tvProfileDob.setOnClickListener(this);
        binding.btnProfile.setOnClickListener(this);
        binding.ivProfileImg.setOnClickListener(this);

        binding.spProfileGender.setOnItemSelectedListener(this);

    }

    @Override
    public void onClick(View view) {
        int id = view.getId();

        if (id == R.id.tv_profile_dob || id == R.id.iv_profile_dob) {
            setBOD();
        } else if (id == R.id.btn_profile) {
            attemptEditProfile();
        } else if (id == R.id.iv_profile_img) {
            Dexter.withActivity(this)
                    .withPermissions(
                            Manifest.permission.CAMERA,
                            Manifest.permission.WRITE_EXTERNAL_STORAGE)
                    .withListener(new MultiplePermissionsListener() {
                        @Override
                        public void onPermissionsChecked(MultiplePermissionsReport report) {
                            if (report.areAllPermissionsGranted()) {
                                showImageChooser();
                            }
                        }

                        @Override
                        public void onPermissionRationaleShouldBeShown(List<PermissionRequest> permissions, PermissionToken token) {
                            token.continuePermissionRequest();
                        }
                    }).check();
        }
    }

    /**
     * Attempts to sign in or register the account specified by the login form.
     * If there are form errors (invalid email, missing fields, etc.), the
     * errors are presented and no actual login attempt is made.
     */
    private void attemptEditProfile() {

        //Reset errors
        binding.etProfileFirstname.setError(null);
        binding.etProfileLastname.setError(null);
        binding.etProfileEmail.setError(null);
        binding.tvProfileDob.setError(null);
        binding.etProfileMobile.setError(null);
        binding.etProfileAddress.setError(null);
        binding.etProfileCity.setError(null);

        // Store values at the time of the login attempt.
        firstname = binding.etProfileFirstname.getText().toString();
        lastname = binding.etProfileLastname.getText().toString();
        email = binding.etProfileEmail.getText().toString();
        dob = binding.tvProfileDob.getText().toString();
        mobile = binding.etProfileMobile.getText().toString();
        address = binding.etProfileAddress.getText().toString();
        city = binding.etProfileCity.getText().toString();
        gender = binding.spProfileGender.getSelectedItem().toString();

        boolean cancel = false;
        View focusView = null;

        if (TextUtils.isEmpty(firstname)) {
            binding.etProfileFirstname.setError(getString(R.string.error_field_required));
            focusView = binding.etProfileFirstname;
            cancel = true;
        }

        if (TextUtils.isEmpty(lastname)) {
            binding.etProfileLastname.setError(getString(R.string.error_field_required));
            focusView = binding.etProfileLastname;
            cancel = true;
        }

        // Check for a valid email address.
        if (TextUtils.isEmpty(email)) {
            binding.etProfileEmail.setError(getString(R.string.error_field_required));
            focusView = binding.etProfileEmail;
            cancel = true;
        } else if (!isEmailValid(email)) {
            binding.etProfileEmail.setError(getString(R.string.error_invalid_email));
            focusView = binding.etProfileEmail;
            cancel = true;
        }

        if (dob.equals(getResources().getString(R.string.dob))) {
            binding.tvProfileDob.setError(getString(R.string.error_field_required));
            focusView = binding.tvProfileDob;
            cancel = true;
        }

        if (TextUtils.isEmpty(mobile)) {
            binding.etProfileMobile.setError(getString(R.string.error_field_required));
            focusView = binding.etProfileMobile;
            cancel = true;
        } else if (!isPhoneValid(mobile)) {
            binding.etProfileMobile.setError(getString(R.string.phone_to_short));
            focusView = binding.etProfileMobile;
            cancel = true;
        }

        if (TextUtils.isEmpty(address)) {
            binding.etProfileAddress.setError(getString(R.string.error_field_required));
            focusView = binding.etProfileAddress;
            cancel = true;
        }

        if (TextUtils.isEmpty(city)) {
            binding.etProfileCity.setError(getString(R.string.error_field_required));
            focusView = binding.etProfileCity;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt login and focus the first
            // form field with an error.
            focusView.requestFocus();
        } else {
            // Show a progress spinner, and kick off a background task to
            // perform the user login attempt.

            // check internet connection is available or not
            if (ConnectivityReceiver.isConnected()) {
                String fullname = firstname + " " + lastname;
                String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                makeEditProfile(fullname, gender, dob, mobile, address, city, user_id);
            } else {
                ConnectivityReceiver.showSnackbar(this);
            }
        }
    }

    // this function return true if email is valid otherwise false
    private boolean isEmailValid(String email) {
        //TODO: Replace this with your own logic
        return email.contains("@");
    }

    // if phone number lessthan 9 character then return false otherwise true
    private boolean isPhoneValid(String phoneno) {
        //TODO: Replace this with your own logic
        return phoneno.length() > 9;
    }

    // show datepicker dialog and set selected date in textview
    private void setBOD() {

        int mYear, mMonth, mDay;

        // Get Current Date
        final Calendar c = Calendar.getInstance();
        mYear = c.get(Calendar.YEAR);
        mMonth = c.get(Calendar.MONTH);
        mDay = c.get(Calendar.DAY_OF_MONTH);

        DatePickerDialog datePickerDialog = new DatePickerDialog(this,
                R.style.datepicker,
                new DatePickerDialog.OnDateSetListener() {

                    @Override
                    public void onDateSet(DatePicker view, int year,
                                          int monthOfYear, int dayOfMonth) {

                        get_dob = "" + year + "-" + (monthOfYear + 1) + "-" + dayOfMonth;

                        try {
                            String inputPattern = "yyyy-MM-dd";
                            String outputPattern = "dd-MM-yyyy";
                            SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern);
                            SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern);

                            Date date = inputFormat.parse(get_dob);
                            String str = outputFormat.format(date);

                            get_dob = inputFormat.format(date);

                            binding.tvProfileDob.setText(str);
                        } catch (ParseException e) {
                            e.printStackTrace();
                            binding.tvProfileDob.setText(get_dob);
                        }

                    }
                }, mYear, mMonth, mDay);
        datePickerDialog.show();
    }

    private void makeEditProfile(String user_fullname, String user_gender, String user_bdate,
                                 String user_phone, String user_address, String user_city, String user_id) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("user_fullname", user_fullname));
        params.add(new NameValuePair("user_gender", user_gender));
        params.add(new NameValuePair("user_bdate", user_bdate));
        params.add(new NameValuePair("user_phone", user_phone));
        params.add(new NameValuePair("user_address", user_address));
        params.add(new NameValuePair("user_city", user_city));
        params.add(new NameValuePair("user_id", user_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.EDIT_PROFILE_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                Toast.makeText(Edit_profileActivity.this, response, Toast.LENGTH_SHORT).show();

                String fullname = firstname + " " + lastname;
                sessionManagement.updateData(fullname, gender, dob, mobile, address, city);

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                Toast.makeText(Edit_profileActivity.this, responce, Toast.LENGTH_SHORT).show();
            }
        }, true, this);
        task.execute();

    }

    // show alertdialog with custom layout
    private void showImageChooser() {

        AlertDialog.Builder dialogBuilder = new AlertDialog.Builder(this);

        // ...Irrelevant code for customizing the buttons and title
        LayoutInflater inflater = this.getLayoutInflater();
        View dialogView = inflater.inflate(R.layout.dialog_update_profile, null);
        dialogBuilder.setView(dialogView);

        TextView tv_camera = (TextView) dialogView.findViewById(R.id.tv_camera);
        TextView tv_gallery = (TextView) dialogView.findViewById(R.id.tv_gallery);
        TextView tv_cancle = (TextView) dialogView.findViewById(R.id.tv_cancle);

        final AlertDialog alertDialog = dialogBuilder.create();
        alertDialog.show();

        tv_camera.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                alertDialog.dismiss();

                Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
                // Ensure that there's a camera activity to handle the intent
                if (takePictureIntent.resolveActivity(getPackageManager()) != null) {
                    // Create the File where the photo should go
                    File photoFile = null;
                    try {
                        photoFile = createImageFile();
                    } catch (IOException ex) {
                        // Error occurred while creating the File
                    }
                    // Continue only if the File was successfully created
                    if (photoFile != null) {
                        Uri photoURI = FileProvider.getUriForFile(Edit_profileActivity.this, getPackageName() + ".fileprovider",
                                photoFile);
                        takePictureIntent.putExtra(MediaStore.EXTRA_OUTPUT, photoURI);
                        startActivityForResult(takePictureIntent, CAMERA_CAPTURE_IMAGE_REQUEST_CODE1);
                    }
                }
            }
        });

        tv_gallery.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                alertDialog.dismiss();
                // Create intent to Open Image applications like Gallery, Google Photos
                Intent galleryIntent = new Intent(Intent.ACTION_PICK, android.provider.MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
                // Start the Intent
                startActivityForResult(galleryIntent, GALLERY_REQUEST_CODE1);
            }
        });

        tv_cancle.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                alertDialog.dismiss();
            }
        });

    }

    String currentPhotoPath = "";

    private File createImageFile() throws IOException {
        // Create an image file name
        String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss", Locale.US).format(new Date());
        File storageDir = getCacheDir();
        File newFile = File.createTempFile("JPEG_${timeStamp}_", /* prefix */".jpg", /* suffix */storageDir /* directory */);
        // Save a file: path for use with ACTION_VIEW intents
        currentPhotoPath = newFile.getAbsolutePath();
        return newFile;
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        // if the result is capturing Image
        if (requestCode == CAMERA_CAPTURE_IMAGE_REQUEST_CODE1) {
            if (resultCode == RESULT_OK) {

                imagefile1 = new ImageComprasser().compressImage(Edit_profileActivity.this, new File(currentPhotoPath));

                Bitmap bitmap = BitmapFactory.decodeFile(imagefile1.getAbsolutePath());

                binding.ivProfileImg.setImageBitmap(bitmap);

                String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                // upload picture from camera image to server
                new UploadPicture(Edit_profileActivity.this, user_id, imagefile1.getAbsoluteFile().toString());

            } else if (resultCode == RESULT_CANCELED) {
                // user cancelled Image capture
                CommonAppCompatActivity.showToast(Edit_profileActivity.this, "User cancelled image capture");
            } else {
                // failed to capture image
                CommonAppCompatActivity.showToast(Edit_profileActivity.this, "Sorry! Failed to capture image");
            }

        } else if ((requestCode == GALLERY_REQUEST_CODE1)) {
            try {
                Uri selectedImage = data.getData();
                String[] filePathColumn = {MediaStore.Images.Media.DATA};

                // Get the cursor
                Cursor cursor = getContentResolver().query(selectedImage,
                        filePathColumn, null, null, null);
                // Move to first row
                cursor.moveToFirst();

                int columnIndex = cursor.getColumnIndex(filePathColumn[0]);
                String imgDecodableString = cursor.getString(columnIndex);

                Bitmap b = BitmapFactory.decodeFile(imgDecodableString);

                imagefile1 = new ImageComprasser().compressImage(Edit_profileActivity.this, new File(imgDecodableString));
                cursor.close();

                // Set the Image in ImageView after decoding the String
                binding.ivProfileImg.setImageBitmap(b);

                String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                // get selected picture path from gallery and upload this picture to server
                new UploadPicture(Edit_profileActivity.this, user_id, imagefile1.getAbsoluteFile().toString());
            } catch (NullPointerException e) {
                e.printStackTrace();
            }
        }
    }

    @Override
    public boolean onCreateOptionsMenu(final Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getSupportActionBar().setHomeButtonEnabled(true);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        getMenuInflater().inflate(R.menu.main, menu);

        ActionBar mActionBar = getSupportActionBar();
        LayoutInflater mInflater = LayoutInflater.from(this);

        View mCustomView = mInflater.inflate(R.layout.actionbar_layout, null);
        TextView tv_title = (TextView) mCustomView.findViewById(R.id.tv_actionbar_title);
        tv_title.setText(getSupportActionBar().getTitle().toString());

        mActionBar.setCustomView(mCustomView);
        mActionBar.setDisplayShowCustomEnabled(true);
        mActionBar.setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));

        getSupportActionBar().setDisplayShowTitleEnabled(false);

        MenuItem cart = menu.findItem(R.id.action_cart);
        MenuItem change_password = menu.findItem(R.id.action_change_password);

        cart.setVisible(false);
        change_password.setVisible(true);

        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                //onBackPressed();
                this.finish();
                return true;
            case R.id.action_change_password:
                Intent changeIntent = new Intent(this, Change_passwordActivity.class);
                startActivity(changeIntent);
                return true;
            default:
                return super.onOptionsItemSelected(menuItem);
        }
    }

    @Override
    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
        LinearLayout linearLayout = (LinearLayout) parent.getChildAt(0);
        TextView textView = (TextView) linearLayout.getChildAt(0);
        textView.setTextColor(ContextCompat.getColor(this, R.color.white));
    }

    @Override
    public void onNothingSelected(AdapterView<?> parent) {

    }

}
