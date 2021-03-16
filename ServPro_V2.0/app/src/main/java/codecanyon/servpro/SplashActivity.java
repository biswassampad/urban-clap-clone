package codecanyon.servpro;

import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.provider.Settings;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import android.os.Bundle;
import android.view.Window;
import android.view.WindowManager;

import com.karumi.dexter.Dexter;
import com.karumi.dexter.MultiplePermissionsReport;
import com.karumi.dexter.PermissionToken;
import com.karumi.dexter.listener.PermissionRequest;
import com.karumi.dexter.listener.multi.MultiplePermissionsListener;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;
import util.Session_management;

public class SplashActivity extends AppCompatActivity {

    public static final int MY_PERMISSIONS_REQUEST_WRITE_FIELS = 102;
    private AlertDialog dialog;

    private Session_management sessionManagement;

    private boolean isSettingDone = false;
    private boolean isPermissionDone = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        CommonAppCompatActivity.setFullScreen(this);
        setContentView(R.layout.activity_splash);

        sessionManagement = new Session_management(SplashActivity.this);

        Thread background = new Thread() {
            public void run() {

                try {
                    // Thread will sleep for 5 seconds
                    sleep(2 * 1000);

                    // After 5 seconds redirect to another intent
                    checkAppPermissions();

                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        };

        // start thread
        background.start();

        if (ConnectivityReceiver.isConnected()) {
            makeGetSettings();
        }

    }

    // checking app permissions is granted or not
    public void checkAppPermissions() {

        Dexter.withActivity(this)
                .withPermissions(android.Manifest.permission.READ_EXTERNAL_STORAGE,
                        android.Manifest.permission.ACCESS_NETWORK_STATE,
                        android.Manifest.permission.CAMERA)
                .withListener(new MultiplePermissionsListener() {
                    @Override
                    public void onPermissionsChecked(MultiplePermissionsReport report) {
                        if (report.areAllPermissionsGranted()) {
                            isPermissionDone = true;
                            go_next();
                        } else {
                            AlertDialog.Builder builder = new AlertDialog.Builder(SplashActivity.this);
                            builder.setMessage("App required some permission please enable it")
                                    .setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                                        public void onClick(DialogInterface dialog, int id) {
                                            // FIRE ZE MISSILES!
                                            openPermissionScreen();
                                        }
                                    })
                                    .setNegativeButton(getResources().getString(R.string.cancel), new DialogInterface.OnClickListener() {
                                        public void onClick(DialogInterface dialog, int id) {
                                            // User cancelled the dialog
                                            dialog.dismiss();
                                        }
                                    });
                            dialog = builder.show();
                        }
                    }

                    @Override
                    public void onPermissionRationaleShouldBeShown(List<PermissionRequest> permissions, PermissionToken token) {
                        token.continuePermissionRequest();
                    }
                }).check();

    }

    public void go_next() {
        if (isSettingDone && isPermissionDone) {
            if (sessionManagement.isLoggedIn()) {
                Intent mainIntent = new Intent(SplashActivity.this, MainActivity.class);
                startActivity(mainIntent);
            } else {
                Intent loginIntent = new Intent(SplashActivity.this, LoginActivity.class);
                startActivity(loginIntent);
            }
            finish();
        }
    }

    public void openPermissionScreen() {
        Intent intent = new Intent(Settings.ACTION_APPLICATION_DETAILS_SETTINGS,
                Uri.fromParts("package", SplashActivity.this.getPackageName(), null));
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(intent);
    }

    private void makeGetSettings() {
        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.GET_SETTINGS_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String responce) {
                isSettingDone = true;

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
