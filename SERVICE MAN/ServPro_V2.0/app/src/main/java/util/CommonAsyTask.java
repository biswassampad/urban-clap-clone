package util;

import android.app.ProgressDialog;
import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Handler;
import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;

import Config.BaseURL;
import codecanyon.servpro.R;
import dialogs.LoaderDialog;

/**
 * Created by Rajesh on 2017-09-20.
 */

public class CommonAsyTask extends AsyncTask<String, Void, String> {

    private ArrayList<NameValuePair> _nameValuePairs = null;
    private String _baseUrl;
    private Context context;
    private String error_string = "";
    private ProgressDialog progressDialog;
    private LoaderDialog loaderDialog;
    private boolean is_progress_show;
    private boolean is_success;
    private JSONObject responceObj;
    private String PARM_RESPONCE = "responce";
    private String PARM_ERROR = "message";
    private String PARM_DATA = "data";
    private VJsonResponce vresponce;
    private String request_type;

    public CommonAsyTask(String request_type, ArrayList<NameValuePair> nameValuePairs, String baseUrl, VJsonResponce vresponce, boolean is_progress_show, Context activity) {
        responceObj = new JSONObject();
        this.vresponce = vresponce;
        _nameValuePairs = nameValuePairs;
        _baseUrl = baseUrl;
        context = activity;
        this.is_progress_show = is_progress_show;
        this.request_type = request_type;

    }

    public interface VJsonResponce {
        public void VResponce(String responce);

        public void VError(String responce);
    }

    // check internet connection is available or not
    private boolean is_internet_connected() {
        if (context != null) {
            ConnectivityManager cm =
                    (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);

            NetworkInfo activeNetwork = cm.getActiveNetworkInfo();
            return activeNetwork != null &&
                    activeNetwork.isConnectedOrConnecting();
        } else {
            return false;
        }
    }

    @Override
    protected void onPreExecute() {
        if (is_internet_connected()) {
            if (is_progress_show) {
                //progressDialog = ProgressDialog.show(context, "", "Process with data..", true);
                /*progressDialog = new ProgressDialog(context, R.style.AppCompatAlertDialogStyle);
                progressDialog.setMessage("Process with data..");
                progressDialog.setCancelable(false);
                progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
                progressDialog.show();*/
                loaderDialog = new LoaderDialog(context);
                loaderDialog.show();
            }
        } else {
            this.cancel(true);
        }
        super.onPreExecute();
    }

    @Override
    protected void onCancelled() {
        if (loaderDialog != null && loaderDialog.isShowing()) {
            loaderDialog.dismiss();
            loaderDialog = null;
        }
        super.onCancelled();
    }

    @Override
    protected String doInBackground(String... strings) {
        JSONParser jsonParser = new JSONParser(context);
        try {

            String json_responce;

            if (request_type.equals(BaseURL.GET)) {
                String get_value = "";
                if (_nameValuePairs.size() > 0) {
                    get_value = _nameValuePairs.get(0).name + "=" + _nameValuePairs.get(0).value;
                }
                json_responce = jsonParser.exeGetRequest(_baseUrl, get_value);
                Log.e(context.toString(), "GET");
            } else {
                json_responce = jsonParser.execPostScriptJSON(_baseUrl, _nameValuePairs);
                Log.e(context.toString(), "POST");
            }

            Log.e(context.toString(), json_responce);

            JSONObject jObj = new JSONObject(json_responce);
            responceObj = jObj;
            if (jObj.has(PARM_RESPONCE) && !jObj.getBoolean(PARM_RESPONCE)) {
                is_success = false;
                error_string = jObj.getString(PARM_ERROR);
                return null;
            } else {
                is_success = true;
                if (jObj.has(PARM_DATA)) {
                    return jObj.getString(PARM_DATA);
                } else {
                    return "" + jObj.getBoolean(PARM_RESPONCE);
                }
            }

        } catch (JSONException e) {
            // TODO Auto-generated catch block
            error_string = e.getMessage();
            e.printStackTrace();
        } catch (IOException e) {
            // TODO Auto-generated catch block
            error_string = e.getMessage();
            e.printStackTrace();
        }
        return null;
    }

    @Override
    protected void onPostExecute(String responce) {
        /*if (progressDialog != null && progressDialog.isShowing()) {
            progressDialog.dismiss();
            progressDialog = null;
        }*/
        try {
            if ((this.loaderDialog != null) && this.loaderDialog.isShowing()) {
                this.loaderDialog.dismiss();
            }
        } catch (final IllegalArgumentException e) {
            // Handle or log or ignore
        } catch (final Exception e) {
            // Handle or log or ignore
        } finally {
            this.loaderDialog = null;
        }

        if (responce != null) {
            vresponce.VResponce(responce);
        } else {
            vresponce.VError(error_string);
        }
        super.onPostExecute(responce);
    }

}
