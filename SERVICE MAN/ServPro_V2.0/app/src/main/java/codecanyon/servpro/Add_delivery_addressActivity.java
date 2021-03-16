package codecanyon.servpro;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.google.android.material.textfield.TextInputLayout;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Config.BaseURL;
import codecanyon.servpro.databinding.ActivityAddDeliveryAddressBinding;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;
import util.Session_management;

public class Add_delivery_addressActivity extends CommonAppCompatActivity implements View.OnClickListener {

    private static String TAG = Add_delivery_addressActivity.class.getSimpleName();

    private boolean isEdit = false;
    private String delivery_id;
    private String zipcode_id;

    private ActivityAddDeliveryAddressBinding binding;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        binding = ActivityAddDeliveryAddressBinding.inflate(getLayoutInflater());
        setContentView(binding.getRoot());

        binding.btnAddAddress.setOnClickListener(this);

        Intent args = getIntent();
        // check intent is null or not
        if (args.getStringExtra("id") != null) {
            isEdit = true;
            binding.btnAddAddress.setText(getResources().getString(R.string.edit_address));

            delivery_id = args.getStringExtra("id");
            String delivery_user_id = args.getStringExtra("user_id");
            String delivery_zipcode = args.getStringExtra("delivery_zipcode");
            String delivery_address = args.getStringExtra("delivery_address");
            String delivery_landmark = args.getStringExtra("delivery_landmark");
            String delivery_fullname = args.getStringExtra("delivery_fullname");
            String delivery_mobilenumber = args.getStringExtra("delivery_mobilenumber");
            String delivery_city = args.getStringExtra("delivery_city");

            binding.etAddAddressFname.setText(delivery_fullname);
            binding.etAddAddressZipcode.setText(delivery_zipcode);
            binding.etAddAddressMobile.setText(delivery_mobilenumber);
            binding.etAddAddressAddress.setText(delivery_address);
            binding.etAddAddressLandmark.setText(delivery_landmark);
            binding.etAddAddressCity.setText(delivery_city);
        }

    }

    @Override
    public void onClick(View view) {
        int id = view.getId();

        if (id == R.id.btn_add_address) {
            attemptAddAddress();
        }

    }

    /**
     * Attempts to sign in or register the account specified by the login form.
     * If there are form errors (invalid email, missing fields, etc.), the
     * errors are presented and no actual login attempt is made.
     */
    private void attemptAddAddress() {

        // Store values at the time of the login attempt.
        String getzip = binding.etAddAddressZipcode.getText().toString();
        String fname = binding.etAddAddressFname.getText().toString();
        String mobile = binding.etAddAddressMobile.getText().toString();
        String address = binding.etAddAddressAddress.getText().toString();
        String landmark = binding.etAddAddressLandmark.getText().toString();
        String city = binding.etAddAddressCity.getText().toString();

        boolean cancel = false;
        View focusView = null;

        if (getzip.isEmpty()) {
            binding.etAddAddressZipcode.setError(getResources().getString(R.string.error_field_required));
            focusView = binding.etAddAddressZipcode;
            cancel = true;
        }

        if (TextUtils.isEmpty(fname)) {
            binding.etAddAddressFname.setError(getString(R.string.error_field_required));
            focusView = binding.etAddAddressFname;
            cancel = true;
        }

        if (TextUtils.isEmpty(mobile)) {
            binding.etAddAddressMobile.setError(getString(R.string.error_field_required));
            focusView = binding.etAddAddressMobile;
            cancel = true;
        } else if (!isPhoneValid(mobile)) {
            binding.etAddAddressMobile.setError(getString(R.string.phone_to_short));
            focusView = binding.etAddAddressMobile;
            cancel = true;
        }

        if (TextUtils.isEmpty(address)) {
            binding.etAddAddressAddress.setError(getString(R.string.error_field_required));
            focusView = binding.etAddAddressAddress;
            cancel = true;
        }

        if (TextUtils.isEmpty(landmark)) {
            binding.etAddAddressLandmark.setError(getString(R.string.error_field_required));
            focusView = binding.etAddAddressLandmark;
            cancel = true;
        }

        if (TextUtils.isEmpty(city)) {
            binding.etAddAddressCity.setError(getString(R.string.error_field_required));
            focusView = binding.etAddAddressCity;
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
                Session_management sessionManagement = new Session_management(this);
                String userid = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);

                makeAddAddress(userid, getzip, address, landmark, fname, mobile, city);
            } else {
                // else internet not available then show snackbar in activity
                ConnectivityReceiver.showSnackbar(this);
            }
        }
    }

    // this function use for check phone number string length gretter then 9 then return true otherwise false
    private boolean isPhoneValid(String phoneno) {
        //TODO: Replace this with your own logic
        return phoneno.length() > 9;
    }

    private void makeAddAddress(String delivery_user_id, String delivery_zipcode, String delivery_address, String delivery_landmark,
                                String delivery_fullname, String delivery_mobilenumber, String delivery_city) {

        // adding post parameters in arraylist
        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("user_id", delivery_user_id));
        params.add(new NameValuePair("zipcode", delivery_zipcode));
        params.add(new NameValuePair("address", delivery_address));
        params.add(new NameValuePair("landmark", delivery_landmark));
        params.add(new NameValuePair("fullname", delivery_fullname));
        params.add(new NameValuePair("mobilenumber", delivery_mobilenumber));
        params.add(new NameValuePair("city", delivery_city));

        final String url;

        if (isEdit) {
            params.add(new NameValuePair("id", delivery_id));
            url = BaseURL.EDIT_DELIVERY_ADDRESS_URL;
        } else {
            url = BaseURL.ADD_DELIVERY_ADDRESS_URL;
        }

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                url, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response + url);

                // display toast message
                CommonAppCompatActivity.showToast(Add_delivery_addressActivity.this, getResources().getString(R.string.added_delivery_ddress));
                finish();
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                // display toast message
                CommonAppCompatActivity.showToast(Add_delivery_addressActivity.this, responce);
            }
        }, true, this);
        task.execute();
    }


}
