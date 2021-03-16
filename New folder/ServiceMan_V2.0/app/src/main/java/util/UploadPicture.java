package util;

import android.content.Context;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;

import Config.BaseURL;
import codecanyon.serviceman.R;

/**
 * Created by Rajesh on 2017-10-28.
 */

public class UploadPicture {
    Context context;

    public UploadPicture(Context context,String user_id,String file_path){
        this.context = context;

        new updateProfile(user_id, file_path).execute();
    }

    private class updateProfile extends AsyncTask<String, Integer, Void> {

        JSONParser jsonParser;
        ArrayList<NameValuePair> nameValuePairs;
        boolean response;
        String error_string, success_msg;
        String filePath = "";

        private updateProfile(String user_id, String filepath) {

            nameValuePairs = new ArrayList<NameValuePair>();
            nameValuePairs.add(new NameValuePair("user_id", user_id));

            this.filePath = filepath;
        }

        @Override
        protected void onPreExecute() {
            // TODO Auto-generated method stub
            super.onPreExecute();
            jsonParser = new JSONParser(context);
        }

        protected Void doInBackground(String... urls) {

            String json_responce = null;
            try {
                // call json parser class for make json request response
                json_responce = jsonParser.execMultiPartPostScriptJSON(BaseURL.EDIT_PROFILE_IMG_URL,
                        nameValuePairs, "image/png", filePath, "user_image");
                Log.e(context.toString(), json_responce + "," + filePath);

                JSONObject jObj = new JSONObject(json_responce);
                if (jObj.getBoolean("responce")) {
                    response = true;
                    success_msg = jObj.getString("data");
                } else {
                    response = false;
                    error_string = jObj.getString("error");
                    return null;
                }
            } catch (IOException e) {
                e.printStackTrace();
            } catch (JSONException e) {
                e.printStackTrace();
            }

            return null;
        }

        protected void onPostExecute(Void result) {
            if (response) {
                Session_management sessionManagement = new Session_management(context);
                sessionManagement.updateImage(success_msg);
                Toast.makeText(context, context.getString(R.string.profile_pic_updated), Toast.LENGTH_SHORT).show();
            } else {
                Toast.makeText(context, "" + error_string, Toast.LENGTH_SHORT).show();
            }
        }
    }

}
