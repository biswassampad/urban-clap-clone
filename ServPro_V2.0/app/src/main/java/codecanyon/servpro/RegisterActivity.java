package codecanyon.servpro;

import android.app.DatePickerDialog;
import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.textfield.TextInputLayout;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;

import Config.BaseURL;
import codecanyon.servpro.databinding.ActivityRegisterBinding;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;

public class RegisterActivity extends AppCompatActivity implements View.OnClickListener {

    private static String TAG = RegisterActivity.class.getSimpleName();

    private String get_dob;

    private ActivityRegisterBinding binding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityRegisterBinding.inflate(getLayoutInflater());
        CommonAppCompatActivity.setFullScreen(this);
        setContentView(binding.getRoot());

        binding.ivRegDob.setOnClickListener(this);
        binding.tvRegDob.setOnClickListener(this);
        binding.btnRegister.setOnClickListener(this);

    }

    @Override
    public void onClick(View view) {
        int id = view.getId();

        if (id == R.id.tv_reg_dob || id == R.id.iv_reg_dob) {
            setBOD();
        } else if (id == R.id.btn_register) {
            attemptRegister();
        }
    }

    public void Cancle(View view) {
        finish();
    }

    /**
     * Attempts to sign in or register the account specified by the login form.
     * If there are form errors (invalid email, missing fields, etc.), the
     * errors are presented and no actual login attempt is made.
     */
    private void attemptRegister() {

        //Reset Errors
        binding.etRegFirstname.setError(null);
        binding.etRegLastname.setError(null);
        binding.etRegEmail.setError(null);
        binding.tvRegDob.setError(null);
        binding.etRegMobile.setError(null);
        binding.etRegPassword.setError(null);
        binding.etRegConfPassword.setError(null);

        // Store values at the time of the login attempt.
        String firstname = binding.etRegFirstname.getText().toString();
        String lastname = binding.etRegLastname.getText().toString();
        String email = binding.etRegEmail.getText().toString();
        String dob = binding.tvRegDob.getText().toString();
        String mobile = binding.etRegMobile.getText().toString();
        String password = binding.etRegPassword.getText().toString();
        String repassword = binding.etRegConfPassword.getText().toString();

        boolean cancel = false;
        View focusView = null;

        if (TextUtils.isEmpty(firstname)) {
            binding.etRegFirstname.setError(getString(R.string.error_field_required));
            focusView = binding.etRegFirstname;
            cancel = true;
        }

        if (TextUtils.isEmpty(lastname)) {
            binding.etRegLastname.setError(getString(R.string.error_field_required));
            focusView = binding.etRegLastname;
            cancel = true;
        }

        // Check for a valid email address.
        if (TextUtils.isEmpty(email)) {
            binding.etRegEmail.setError(getString(R.string.error_field_required));
            focusView = binding.etRegEmail;
            cancel = true;
        } else if (!isEmailValid(email)) {
            binding.etRegEmail.setError(getString(R.string.error_invalid_email));
            focusView = binding.etRegEmail;
            cancel = true;
        }

        if (dob.equals(getResources().getString(R.string.dob))) {
            binding.tvRegDob.setError(getString(R.string.error_field_required));
            focusView = binding.tvRegDob;
            cancel = true;
        }

        if (TextUtils.isEmpty(mobile)) {
            binding.etRegMobile.setError(getString(R.string.error_field_required));
            focusView = binding.etRegMobile;
            cancel = true;
        } else if (!isPhoneValid(mobile)) {
            binding.etRegMobile.setError(getString(R.string.phone_to_short));
            focusView = binding.etRegMobile;
            cancel = true;
        }

        // Check for a valid password, if the user entered one.
        if (TextUtils.isEmpty(password)) {
            binding.etRegPassword.setError(getString(R.string.error_field_required));
            focusView = binding.etRegPassword;
            cancel = true;
        } else if (!isPasswordValid(password)) {
            binding.etRegPassword.setError(getString(R.string.error_invalid_password));
            focusView = binding.etRegPassword;
            cancel = true;
        }

        if (TextUtils.isEmpty(repassword)) {
            binding.etRegConfPassword.setError(getString(R.string.error_field_required));
            focusView = binding.etRegConfPassword;
            cancel = true;
        } else if (!repassword.equals(password)) {
            binding.etRegConfPassword.setError(getString(R.string.password_not_match));
            focusView = binding.etRegConfPassword;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt login and focus the first
            // form field with an error.
            focusView.requestFocus();
        } else {
            // Show a progress spinner, and kick off a background task to
            // perform the user login attempt.

            if (binding.chkRegTerms.isChecked()) {
                if (ConnectivityReceiver.isConnected()) {
                    String fullname = firstname + " " + lastname;
                    makeRegister(fullname, email, mobile, get_dob, password);
                } else {
                    ConnectivityReceiver.showSnackbar(this);
                }
            } else {
                Toast.makeText(this, getResources().getString(R.string.reg_chk_note), Toast.LENGTH_SHORT).show();
            }

        }
    }

    // this function return true if email is valid otherwise false
    private boolean isEmailValid(String email) {
        //TODO: Replace this with your own logic
        return email.contains("@");
    }

    // this function return true if password greater then 4 character otherwise false
    private boolean isPasswordValid(String password) {
        //TODO: Replace this with your own logic
        return password.length() > 4;
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

                            binding.tvRegDob.setText(str);
                        } catch (ParseException e) {
                            e.printStackTrace();
                            binding.tvRegDob.setText(get_dob);
                        }

                    }
                }, mYear, mMonth, mDay);
        datePickerDialog.show();
    }

    private void makeRegister(String user_fullname, String user_email, String user_phone, String user_bdate, String user_password) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("user_fullname", user_fullname));
        params.add(new NameValuePair("user_bdate", user_bdate));
        params.add(new NameValuePair("user_email", user_email));
        params.add(new NameValuePair("user_phone", user_phone));
        params.add(new NameValuePair("user_password", user_password));

        Log.e(TAG, user_fullname + "," + user_email + "," + user_phone + "," + user_bdate + "," + user_password);

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.REGISTER_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                Toast.makeText(RegisterActivity.this, getResources().getString(R.string.registration_successfull), Toast.LENGTH_SHORT).show();

                Intent loginIntent = new Intent(RegisterActivity.this, LoginActivity.class);
                startActivity(loginIntent);
                finish();
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                Toast.makeText(RegisterActivity.this, responce, Toast.LENGTH_SHORT).show();
            }
        }, true, this);
        task.execute();

    }

}
