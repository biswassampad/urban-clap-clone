package codecanyon.servpro;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.textfield.TextInputLayout;

import java.util.ArrayList;

import Config.BaseURL;
import codecanyon.servpro.databinding.ActivityForgotBinding;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;

public class ForgotActivity extends AppCompatActivity {

    private static String TAG = ForgotActivity.class.getSimpleName();

    private ActivityForgotBinding binding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityForgotBinding.inflate(getLayoutInflater());
        CommonAppCompatActivity.setFullScreen(this);
        setContentView(R.layout.activity_forgot);

        binding.btnForgot.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                attemptForgot();
            }
        });

    }

    public void Cancle(View view) {
        finish();
    }

    /**
     * Attempts to sign in or register the account specified by the login form.
     * If there are form errors (invalid email, missing fields, etc.), the
     * errors are presented and no actual login attempt is made.
     */
    private void attemptForgot() {

        //Reset Errors
        binding.etForgotEmail.setError(null);

        // Store values at the time of the login attempt.
        String email = binding.etForgotEmail.getText().toString();

        boolean cancel = false;
        View focusView = null;

        // Check for a valid email address.
        if (TextUtils.isEmpty(email)) {
            binding.etForgotEmail.setError(getString(R.string.error_field_required));
            focusView = binding.etForgotEmail;
            cancel = true;
        } else if (!isEmailValid(email)) {
            binding.etForgotEmail.setError(getString(R.string.error_invalid_email));
            focusView = binding.etForgotEmail;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt login and focus the first
            // form field with an error.
            focusView.requestFocus();
        } else {
            // Show a progress spinner, and kick off a background task to
            // perform the user login attempt.

            // checking internet connection is available or not
            if (ConnectivityReceiver.isConnected()) {
                makeForgot(email);
            } else {
                // show snackbar in activity
                ConnectivityReceiver.showSnackbar(this);
            }
        }
    }

    // this function return true if email is valid otherwise false
    private boolean isEmailValid(String email) {
        //TODO: Replace this with your own logic
        return email.contains("@");
    }

    private void makeForgot(String email) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("email", email));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.FORGOT_PASSWORD_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                Toast.makeText(ForgotActivity.this, response, Toast.LENGTH_SHORT).show();

                Intent loginIntent = new Intent(ForgotActivity.this, LoginActivity.class);
                startActivity(loginIntent);
                finish();
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                Toast.makeText(ForgotActivity.this, responce, Toast.LENGTH_SHORT).show();
            }
        }, true, this);
        task.execute();

    }

}
