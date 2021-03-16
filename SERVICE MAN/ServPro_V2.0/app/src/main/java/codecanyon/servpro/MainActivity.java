package codecanyon.servpro;

import android.Manifest;
import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.content.ActivityNotFoundException;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.content.FileProvider;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;

import android.view.Menu;
import android.view.MenuItem;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.android.material.navigation.NavigationView;
import com.karumi.dexter.Dexter;
import com.karumi.dexter.MultiplePermissionsReport;
import com.karumi.dexter.PermissionToken;
import com.karumi.dexter.listener.PermissionRequest;
import com.karumi.dexter.listener.multi.MultiplePermissionsListener;
import com.squareup.picasso.Picasso;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import Config.BaseURL;
import fcm.MyFirebaseRegister;
import fragment.ContactAboutFragment;
import fragment.HomeFragment;
import fragment.TeamFragment;
import util.DatabaseHandler;
import util.ImageComprasser;
import util.Session_management;
import util.UploadPicture;

public class MainActivity extends AppCompatActivity
        implements NavigationView.OnNavigationItemSelectedListener,
        View.OnClickListener, BottomNavigationView.OnNavigationItemSelectedListener {

    private static String TAG = MainActivity.class.getSimpleName();

    private static final int CAMERA_CAPTURE_IMAGE_REQUEST_CODE1 = 101;
    private static final int GALLERY_REQUEST_CODE1 = 201;

    public static final int MEDIA_TYPE_IMAGE = 1;
    public static final int MEDIA_TYPE_GALLERY = 2;

    private Uri fileUri;
    File imagefile1 = null;

    private ImageView iv_header_img, iv_header_edit, iv_header_home;
    private TextView tv_header_name, tv_header_email;
    private Menu nav_menu;
    private BottomNavigationView bottomNavigationView;

    private Session_management sessionManagement;

    private TextView totalBudgetCount;
    private TextView tv_title;

    private DatabaseHandler dbcart;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        dbcart = new DatabaseHandler(this);

        // intialize Session_management class
        sessionManagement = new Session_management(this);

        bottomNavigationView = (BottomNavigationView) findViewById(R.id.bottom_navigation);
        //BottomNavigationViewHelper.disableShiftMode(bottomNavigationView);

        bottomNavigationView.setOnNavigationItemSelectedListener(this);

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close) {
            @Override
            public void onDrawerStateChanged(int newState) {
                if (newState == DrawerLayout.STATE_SETTLING) {
                    updateSidemenu();
                    invalidateOptionsMenu();
                }
            }
        };
        drawer.setDrawerListener(toggle);
        toggle.syncState();

        NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);

        nav_menu = navigationView.getMenu();

        // getting side navigation header view for set values in side menu controlls
        View header = ((NavigationView) findViewById(R.id.nav_view)).getHeaderView(0);

        iv_header_img = (ImageView) header.findViewById(R.id.iv_header_img);
        iv_header_home = (ImageView) header.findViewById(R.id.iv_header_home);
        iv_header_edit = (ImageView) header.findViewById(R.id.iv_header_edit);
        tv_header_name = (TextView) header.findViewById(R.id.tv_header_name);
        tv_header_email = (TextView) header.findViewById(R.id.tv_header_email);

        iv_header_img.setOnClickListener(this);
        iv_header_home.setOnClickListener(this);
        iv_header_edit.setOnClickListener(this);
        tv_header_name.setOnClickListener(this);

        if (savedInstanceState == null) {
            Fragment fm = new HomeFragment();
            FragmentManager fragmentManager = getFragmentManager();
            fragmentManager.beginTransaction()
                    .replace(R.id.contentPanel, fm, "Home_fragment")
                    .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN)
                    .commit();
        }

        if (sessionManagement.isLoggedIn()) {
            String userid = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);

            // register firebase message token to server for push notification
            MyFirebaseRegister firebaseRegister = new MyFirebaseRegister(this);
            firebaseRegister.RegisterUser(userid);
        }

    }

    @Override
    public void onClick(View view) {
        int id = view.getId();

        if (id == R.id.iv_header_home) {
            Fragment fm = new HomeFragment();
            FragmentManager fragmentManager = getFragmentManager();
            fragmentManager.beginTransaction()
                    .replace(R.id.contentPanel, fm, "Home_fragment")
                    .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN)
                    .commit();
            DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
            drawer.closeDrawer(GravityCompat.START);
        } else if (id == R.id.iv_header_img || id == R.id.tv_header_name) {
            if (sessionManagement.isLoggedIn()) {
                // call function for show image options
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
            } else {
                Intent loginIntent = new Intent(MainActivity.this, LoginActivity.class);
                loginIntent.putExtra("setfinish", "true");
                startActivity(loginIntent);

                DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
                drawer.closeDrawer(GravityCompat.START);
            }

        } else if (id == R.id.iv_header_edit) {
            Intent i = new Intent(MainActivity.this, Edit_profileActivity.class);
            startActivity(i);
        }
    }

    // check user is login or not if login then show user data
    private void updateSidemenu() {
        if (sessionManagement.isLoggedIn()) {
            String getname = sessionManagement.getUserDetails().get(BaseURL.KEY_NAME);
            String getimage = sessionManagement.getUserDetails().get(BaseURL.KEY_IMAGE);
            String getemail = sessionManagement.getUserDetails().get(BaseURL.KEY_EMAIL);

            Picasso.with(this)
                    .load(BaseURL.IMG_PROFILE_URL + getimage)
                    .placeholder(R.drawable.ic_loading)
                    .into(iv_header_img);

            tv_header_name.setText(getname);
            tv_header_email.setText(getemail);
            iv_header_edit.setVisibility(View.VISIBLE);
            nav_menu.findItem(R.id.menu_user).setVisible(true);
            nav_menu.findItem(R.id.nav_logout).setVisible(true);

        } else {
            tv_header_name.setText(getResources().getString(R.string.login));
            tv_header_email.setText("");
            iv_header_edit.setVisibility(View.GONE);
            nav_menu.findItem(R.id.menu_user).setVisible(false);
            nav_menu.findItem(R.id.nav_logout).setVisible(false);
        }
    }

    @Override
    public void onBackPressed() {
        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        if (drawer.isDrawerOpen(GravityCompat.START)) {
            drawer.closeDrawer(GravityCompat.START);
        } else {
            super.onBackPressed();
        }
    }

    @Override
    public boolean onCreateOptionsMenu(final Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);

        ActionBar mActionBar = getSupportActionBar();
        LayoutInflater mInflater = LayoutInflater.from(this);

        View mCustomView = mInflater.inflate(R.layout.actionbar_layout, null);
        tv_title = (TextView) mCustomView.findViewById(R.id.tv_actionbar_title);
        switch (bottomNavigationView.getSelectedItemId()) {
            case R.id.action_service:
                tv_title.setText(getResources().getString(R.string.app_name));
                break;
            case R.id.action_team:
                tv_title.setText(getResources().getString(R.string.team));
                break;
            case R.id.action_about:
                tv_title.setText(getResources().getString(R.string.about_us));
                break;
            case R.id.action_contact:
                tv_title.setText(getResources().getString(R.string.contact_us));
                break;
            default:
                if (tv_title != null && getSupportActionBar().getTitle() != null) {
                    tv_title.setText(getSupportActionBar().getTitle().toString());
                }
                break;
        }

        mActionBar.setCustomView(mCustomView);
        mActionBar.setDisplayShowCustomEnabled(true);

        getSupportActionBar().setDisplayShowTitleEnabled(false);

        final MenuItem cart = menu.findItem(R.id.action_cart);
        View count = cart.getActionView();
        count.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                menu.performIdentifierAction(cart.getItemId(), 0);
            }
        });

        totalBudgetCount = (TextView) count.findViewById(R.id.tv_action_cart);

        updateCounter();

        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        //noinspection SimplifiableIfStatement
        if (id == R.id.action_cart) {
            if (dbcart.getCartCount() > 0) {
                Intent cartIntent = new Intent(this, CartActivity.class);
                startActivity(cartIntent);
            } else {
                Toast.makeText(this, getResources().getString(R.string.cart_empty), Toast.LENGTH_SHORT).show();
            }
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    // update actionbar cart items
    public void updateCounter() {
        if (totalBudgetCount != null) {
            totalBudgetCount.setText("" + dbcart.getCartCount());
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        updateCounter();
    }

    @SuppressWarnings("StatementWithEmptyBody")
    @Override
    public boolean onNavigationItemSelected(MenuItem item) {
        // Handle navigation view item clicks here.
        int id = item.getItemId();

        Intent i = null;
        Fragment fm = null;

        if (id == R.id.nav_profile) {
            i = new Intent(MainActivity.this, Edit_profileActivity.class);
        } else if (id == R.id.nav_service) {
            i = new Intent(MainActivity.this, My_servicesActivity.class);
        } else if (id == R.id.nav_offer) {
            i = new Intent(MainActivity.this, OffersActivity.class);
        } else if (id == R.id.nav_address) {
            i = new Intent(MainActivity.this, My_delivery_addressActivity.class);
        } else if (id == R.id.nav_rate) {
            reviewOnApp();
        } else if (id == R.id.nav_share) {
            shareApp();
        } else if (id == R.id.nav_logout) {
            sessionManagement.logoutSession();
            finish();
        } else if (id == R.id.action_service) {
            Fragment fm1 = new HomeFragment();
            FragmentManager fragmentManager = getFragmentManager();
            fragmentManager.beginTransaction()
                    .replace(R.id.contentPanel, fm1, "Home_fragment")
                    .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN)
                    .commit();
        } else if (id == R.id.action_team) {
            fm = new TeamFragment();
        } else if (id == R.id.action_about) {
            fm = new ContactAboutFragment();
            Bundle args = new Bundle();
            args.putString("title", "About");
            fm.setArguments(args);
        } else if (id == R.id.action_contact) {
            fm = new ContactAboutFragment();
            Bundle args = new Bundle();
            args.putString("title", "Contact");
            fm.setArguments(args);
        }

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        drawer.closeDrawer(GravityCompat.START);

        if (i != null) {
            startActivity(i);
        } else if (fm != null) {
            FragmentManager fragmentManager = getFragmentManager();
            FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction();
            fragmentTransaction.hide(((Activity) MainActivity.this).getFragmentManager()
                    .findFragmentByTag("Home_fragment"));
            fragmentTransaction.add(R.id.contentPanel, fm);
            fragmentTransaction.addToBackStack(null);
            fragmentTransaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
            fragmentTransaction.commit();
        }

        return true;
    }

    // finish this activity. this method call from fragment
    public void setFinish() {
        finish();
    }

    // set selection in bottom navigationbar from fragment
    public void setBottomSelection() {
        Menu menu = bottomNavigationView.getMenu();

        MenuItem item = menu.getItem(0);
        item.setChecked(true);
    }

    // set title in actionbar. this method called form fragment
    public void setTitle(String title) {
        if (tv_title != null) {
            tv_title.setText(title);
        }
    }

    // share intent for share app url from another app
    public void shareApp() {
        Intent sendIntent = new Intent();
        sendIntent.setAction(Intent.ACTION_SEND);
        sendIntent.putExtra(Intent.EXTRA_TEXT, "Hi friends i am using ." + " http://play.google.com/store/apps/details?id=" + getPackageName() + " APP");
        sendIntent.setType("text/plain");
        startActivity(sendIntent);
    }

    // redirect to play store in particular app
    public void reviewOnApp() {
        Uri uri = Uri.parse("market://details?id=" + getPackageName());
        Intent goToMarket = new Intent(Intent.ACTION_VIEW, uri);
        // To count with Play market backstack, After pressing back button,
        // to taken back to our application, we need to add following flags to intent.
        goToMarket.addFlags(Intent.FLAG_ACTIVITY_NO_HISTORY |
                Intent.FLAG_ACTIVITY_NEW_DOCUMENT |
                Intent.FLAG_ACTIVITY_MULTIPLE_TASK);
        try {
            startActivity(goToMarket);
        } catch (ActivityNotFoundException e) {
            startActivity(new Intent(Intent.ACTION_VIEW,
                    Uri.parse("http://play.google.com/store/apps/details?id=" + getPackageName())));
        }
    }

    // show image choosing dialog
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
                        Uri photoURI = FileProvider.getUriForFile(MainActivity.this, getPackageName() + ".fileprovider", photoFile);
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

                imagefile1 = new ImageComprasser().compressImage(MainActivity.this, new File(currentPhotoPath));

                Bitmap bitmap = BitmapFactory.decodeFile(imagefile1.getAbsolutePath());
                iv_header_img.setImageBitmap(bitmap);

                String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                // upload picture from camera image to server
                new UploadPicture(MainActivity.this, user_id, imagefile1.getAbsoluteFile().toString());

            } else if (resultCode == RESULT_CANCELED) {

                // user cancelled Image capture
                Toast.makeText(MainActivity.this,
                        "User cancelled image capture", Toast.LENGTH_SHORT)
                        .show();

            } else {
                // failed to capture image
                Toast.makeText(MainActivity.this,
                        "Sorry! Failed to capture image", Toast.LENGTH_SHORT)
                        .show();
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

                File file = new File(imgDecodableString);
                imagefile1 = new ImageComprasser().compressImage(MainActivity.this, file);
                cursor.close();

                // Set the Image in ImageView after decoding the String
                iv_header_img.setImageBitmap(b);

                String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                // get selected picture path from gallery and upload this picture to server
                new UploadPicture(MainActivity.this, user_id, imagefile1.getAbsoluteFile().toString());
            } catch (NullPointerException e) {
                e.printStackTrace();
            }
        }
    }

}
