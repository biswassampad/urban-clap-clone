package codecanyon.serviceman;

import android.content.Intent;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Config.BaseURL;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;
import util.Session_management;

public class SplashActivity extends AppCompatActivity {

    private Session_management sessionManagement;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);

        sessionManagement = new Session_management(SplashActivity.this);

        if (ConnectivityReceiver.isConnected()){
            makeGetSettings();
        }
    }

    public void go_next() {
        if (sessionManagement.isLoggedIn()) {
            Intent mainIntent = new Intent(SplashActivity.this, MainActivity.class);
            startActivity(mainIntent);
        } else {
            Intent loginIntent = new Intent(SplashActivity.this, LoginActivity.class);
            startActivity(loginIntent);
        }
        finish();
    }

    private void makeGetSettings() {
        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.GET_SETTINGS_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String responce) {
                try {
                    JSONObject jsonObject = new JSONObject(responce);
                    new Session_management.UserData().setSession(SplashActivity.this, "default_country", jsonObject.getString("default_country"));
                    new Session_management.UserData().setSession(SplashActivity.this, "currency", jsonObject.getString("currency"));
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                go_next();
            }

            @Override
            public void VError(String responce) {

            }
        }, false, this);
        task.execute();
    }

}
