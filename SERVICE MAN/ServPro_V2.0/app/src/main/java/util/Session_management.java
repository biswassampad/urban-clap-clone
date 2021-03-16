package util;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import com.securesharedpreferences.SecureSharedPreferences;

import java.util.HashMap;

import Config.BaseURL;
import codecanyon.servpro.LoginActivity;

import static Config.BaseURL.DELIVERY_ADDRESS;
import static Config.BaseURL.DELIVERY_CITY;
import static Config.BaseURL.DELIVERY_FULLNAME;
import static Config.BaseURL.DELIVERY_LANDMARK;
import static Config.BaseURL.DELIVERY_MOBILENUMBER;
import static Config.BaseURL.DELIVERY_ZIPCODE;
import static Config.BaseURL.ID;
import static Config.BaseURL.IS_LOGIN;
import static Config.BaseURL.KEY_ADDRESS;
import static Config.BaseURL.KEY_BDATE;
import static Config.BaseURL.KEY_CITY;
import static Config.BaseURL.KEY_EMAIL;
import static Config.BaseURL.KEY_GENDER;
import static Config.BaseURL.KEY_ID;
import static Config.BaseURL.KEY_IMAGE;
import static Config.BaseURL.KEY_MOBILE;
import static Config.BaseURL.KEY_NAME;
import static Config.BaseURL.KEY_TYPE_ID;
import static Config.BaseURL.PREFS_NAME;
import static Config.BaseURL.USER_FULLNAME;
import static Config.BaseURL.USER_ID;
import static Config.BaseURL.USER_IMAGE;

/**
 * Created by Rajesh on 2017-09-20.
 */

public class Session_management {

    SharedPreferences prefs;
    SharedPreferences.Editor editor;

    SharedPreferences prefs_address;
    SharedPreferences.Editor editor_address;

    Context context;

    int PRIVATE_MODE = 0;

    public Session_management(Context context) {
        this.context = context;
        prefs = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
        editor = prefs.edit();

        prefs_address = context.getSharedPreferences("Delivery_address", 0);
        editor_address = prefs_address.edit();
    }

    // store data in shared preference
    public void createLoginSession(String id, String email, String name
            , String type_id, String bdate, String mobile, String image,
                                   String gender, String address, String city) {

        if (!id.isEmpty()) {
            editor.putBoolean(IS_LOGIN, true);
        }

        editor.putString(KEY_ID, id);
        editor.putString(KEY_EMAIL, email);
        editor.putString(KEY_NAME, name);
        editor.putString(KEY_TYPE_ID, type_id);
        editor.putString(KEY_BDATE, bdate);
        editor.putString(KEY_MOBILE, mobile);
        editor.putString(KEY_IMAGE, image);
        editor.putString(KEY_GENDER, gender);
        editor.putString(KEY_ADDRESS, address);
        editor.putString(KEY_CITY, city);
        editor.commit();
    }

    // store data in shared preference
    public void createDeliveryaddress(String id, String user_id, String delivery_zipcode, String delivery_address,
                                      String delivery_landmark, String delivery_fullname, String delivery_mobilenumber,
                                      String delivery_city, String user_fullname, String user_image) {

        editor_address.putString("id", id);
        editor_address.putString("user_id", user_id);
        editor_address.putString("delivery_zipcode", delivery_zipcode);
        editor_address.putString("delivery_address", delivery_address);
        editor_address.putString("delivery_landmark", delivery_landmark);
        editor_address.putString("delivery_fullname", delivery_fullname);
        editor_address.putString("delivery_mobilenumber", delivery_mobilenumber);
        editor_address.putString("delivery_city", delivery_city);
        editor_address.putString("user_fullname", user_fullname);
        editor_address.putString("user_image", user_image);
        editor_address.commit();
    }

    public void checkLogin() {

        if (!this.isLoggedIn()) {
            Intent loginsucces = new Intent(context, LoginActivity.class);
            // Closing all the Activities
            loginsucces.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

            // Add new Flag to start new Activity
            loginsucces.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

            context.startActivity(loginsucces);
        }
    }

    /**
     * Get stored session data
     */
    public HashMap<String, String> getUserDetails() {
        HashMap<String, String> user = new HashMap<String, String>();

        user.put(KEY_ID, prefs.getString(KEY_ID, null));
        // user email id
        user.put(KEY_EMAIL, prefs.getString(KEY_EMAIL, null));
        // user name
        user.put(KEY_NAME, prefs.getString(KEY_NAME, null));

        user.put(KEY_TYPE_ID, prefs.getString(KEY_TYPE_ID, null));

        user.put(KEY_BDATE, prefs.getString(KEY_BDATE, null));

        user.put(KEY_MOBILE, prefs.getString(KEY_MOBILE, null));

        user.put(KEY_IMAGE, prefs.getString(KEY_IMAGE, null));

        user.put(KEY_GENDER, prefs.getString(KEY_GENDER, null));

        user.put(KEY_ADDRESS, prefs.getString(KEY_ADDRESS, null));

        user.put(KEY_CITY, prefs.getString(KEY_CITY, null));

        // return user
        return user;
    }

    /**
     * Get stored session data
     */
    public HashMap<String, String> getDeliveryAddress(){
        HashMap<String, String> address = new HashMap<String, String>();

        address.put(ID, prefs_address.getString(ID, null));
        // user email id
        address.put(USER_ID, prefs_address.getString(USER_ID, null));
        // user name
        address.put(DELIVERY_ZIPCODE, prefs_address.getString(DELIVERY_ZIPCODE, null));

        address.put(DELIVERY_ADDRESS, prefs_address.getString(DELIVERY_ADDRESS, null));

        address.put(DELIVERY_LANDMARK, prefs_address.getString(DELIVERY_LANDMARK, null));

        address.put(DELIVERY_FULLNAME, prefs_address.getString(DELIVERY_FULLNAME, null));

        address.put(DELIVERY_MOBILENUMBER, prefs_address.getString(DELIVERY_MOBILENUMBER, null));

        address.put(DELIVERY_CITY, prefs_address.getString(DELIVERY_CITY, null));

        address.put(USER_FULLNAME, prefs_address.getString(USER_FULLNAME, null));

        address.put(USER_IMAGE, prefs_address.getString(USER_IMAGE, null));

        // return user
        return address;
    }

    // update session data
    public void updateData(String name, String gender, String bdate, String mobile, String address, String city) {

        editor.putString(KEY_NAME, name);
        editor.putString(KEY_GENDER, gender);
        editor.putString(KEY_BDATE, bdate);
        editor.putString(KEY_MOBILE, mobile);
        editor.putString(KEY_ADDRESS, address);
        editor.putString(KEY_CITY, city);

        editor.apply();
    }

    // update session data
    public void updateImage(String image) {
        editor.putString(KEY_IMAGE, image);

        editor.apply();
    }

    // clear session and redirect to login screen
    public void logoutSession() {
        editor.clear();
        editor.commit();

        editor_address.clear();
        editor_address.commit();

        Intent logout = new Intent(context, LoginActivity.class);
        // Closing all the Activities
        logout.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

        // Add new Flag to start new Activity
        logout.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

        context.startActivity(logout);


    }

    // Get Login State
    public boolean isLoggedIn() {
        return prefs.getBoolean(IS_LOGIN, false);
    }

    public static class UserData {

        public void setSession(Context context, String key, String value) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            session.edit().putString(key, value).apply();
        }

        public void setSession(Context context, String key, Boolean value) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            session.edit().putBoolean(key, value).apply();
        }

        public void setSession(Context context, String key, int value) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            session.edit().putInt(key, value).apply();
        }

        public void setSession(Context context, String key, double value) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            session.edit().putLong(key, java.lang.Double.doubleToRawLongBits(value)).apply();
        }

        public String getSession(Context context, String key) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            return session.getString(key, "");
        }

        public boolean getSessionBoolean(Context context, String key) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            return session.getBoolean(key, false);
        }

        public int getSessionInt(Context context, String key) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            return session.getInt(key, 0);
        }

        public double getSessionDouble(Context context, String key) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            return java.lang.Double.longBitsToDouble(session.getLong(key, 0));
        }

        public boolean isLogin(Context context) {
            SecureSharedPreferences session = new SecureSharedPreferences(context, BaseURL.PREFS_NAME, BaseURL.ENCRYPTED_PASSWORD);
            return session.getBoolean(IS_LOGIN, false);
        }

    }

    static class CommonData {

        public void setSession(Context context, String key, String value) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            session.edit().putString(key, value).apply();
        }

        public void setSession(Context context, String key, Boolean value) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            session.edit().putBoolean(key, value).apply();
        }

        public void setSession(Context context, String key, int value) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            session.edit().putInt(key, value).apply();
        }

        public void setSession(Context context, String key, double value) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            session.edit().putLong(key, java.lang.Double.doubleToRawLongBits(value)).apply();
        }

        public String getSession(Context context, String key) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            return session.getString(key, "");
        }

        public boolean getSessionBoolean(Context context, String key) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            return session.getBoolean(key, false);
        }

        public int getSessionInt(Context context, String key) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            return session.getInt(key, 0);
        }

        public double getSessionDouble(Context context, String key) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            return java.lang.Double.longBitsToDouble(session.getLong(key, 0));
        }

        public boolean isLogin(Context context) {
            SharedPreferences session = context.getSharedPreferences(BaseURL.PREFS_NAME, 0);
            return session.getBoolean(IS_LOGIN, false);
        }

    }

}
