package codecanyon.servpro;

import android.app.Activity;
import android.content.DialogInterface;
import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RatingBar;
import android.widget.TextView;

import androidx.appcompat.app.AlertDialog;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.android.material.textfield.TextInputLayout;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import adapter.My_service_detail_adapter;
import model.Service_detail_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;
import util.Session_management;

public class My_service_detailActivity extends CommonAppCompatActivity {

    private static String TAG = My_service_detailActivity.class.getSimpleName();

    private TextView tv_date, tv_price, tv_cancel, tv_name, tv_phone, tv_visit_time, tv_total_time, tv_time_status, tv_price_status, tv_start, tv_end;
    private LinearLayout ll_user, ll_visit, ll_start, ll_end;
    private RatingBar ratingBar;
    private RecyclerView rv_order_detail;

    private String status;
    private String ids;
    private String appointment_id;

    private Session_management sessionManagement;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_my_service_detail);

        sessionManagement = new Session_management(this);

        tv_name = (TextView) findViewById(R.id.tv_servicedetail_name);
        tv_phone = (TextView) findViewById(R.id.tv_servicedetail_phone);
        tv_date = (TextView) findViewById(R.id.tv_servicedetail_date);
        tv_visit_time = (TextView) findViewById(R.id.tv_servicedetail_visit_time);
        tv_price = (TextView) findViewById(R.id.tv_total_price);
        tv_total_time = (TextView) findViewById(R.id.tv_total_time);
        tv_time_status = (TextView) findViewById(R.id.tv_total_time_status);
        tv_price_status = (TextView) findViewById(R.id.tv_total_price_status);
        tv_cancel = (TextView) findViewById(R.id.tv_cancel);
        tv_start = (TextView) findViewById(R.id.tv_servicedetail_date_start);
        tv_end = (TextView) findViewById(R.id.tv_servicedetail_date_end);
        ratingBar = (RatingBar) findViewById(R.id.rb_servicedetail);
        ll_user = (LinearLayout) findViewById(R.id.ll_servicedetail_user);
        ll_visit = (LinearLayout) findViewById(R.id.ll_servicedetail_visit);
        ll_start = (LinearLayout) findViewById(R.id.ll_date_start);
        ll_end = (LinearLayout) findViewById(R.id.ll_date_end);
        rv_order_detail = (RecyclerView) findViewById(R.id.rv_order_detail);
        rv_order_detail.setLayoutManager(new LinearLayoutManager(this));

        appointment_id = getIntent().getStringExtra("appointment_id");

        getSupportActionBar().setTitle(getResources().getString(R.string.order_no) + appointment_id);

        tv_cancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (status.equals("0")) {
                    // show cancel order dialog
                    showCancleDialog(appointment_id);
                } else {
                    showDialog(ids);
                }
            }
        });

        // check internet connection available or not
        if (ConnectivityReceiver.isConnected()) {
            makeGetMyServiceDetail(appointment_id);
        } else {
            // display snackbar in activity
            ConnectivityReceiver.showSnackbar(this);
        }

    }

    // display alertdialog with custom view
    private void showCancleDialog(final String appointment_id) {
        AlertDialog.Builder alert = new AlertDialog.Builder(this);
        alert.setTitle(getResources().getString(R.string.are_you_sure_cancel_service));
        alert.setPositiveButton(getResources().getString(R.string.yes), new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                if (ConnectivityReceiver.isConnected()) {
                    Session_management sessionManagement = new Session_management(My_service_detailActivity.this);
                    String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                    makeCancelOrder(appointment_id, user_id);
                } else {
                    ConnectivityReceiver.showSnackbar(My_service_detailActivity.this);
                }
            }
        });
        alert.setNegativeButton(getResources().getString(R.string.no), new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {

            }
        });

        final AlertDialog dialog = alert.create();

        dialog.setOnShowListener(new DialogInterface.OnShowListener() {
            @Override
            public void onShow(DialogInterface arg0) {
                // change color of dialog button
                dialog.getButton(AlertDialog.BUTTON_NEGATIVE).setTextColor(getResources().getColor(R.color.colorPrimary));
                dialog.getButton(AlertDialog.BUTTON_POSITIVE).setTextColor(getResources().getColor(R.color.colorPrimary));
            }
        });
        dialog.show();

    }

    private void makeCancelOrder(String appointment_id, String user_id) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("appointment_id", appointment_id));
        params.add(new NameValuePair("user_id", user_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.CANCEL_APPOINTMENT_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);
                // display toast message
                CommonAppCompatActivity.showToast(My_service_detailActivity.this, response);
                finish();
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                // display toast message
                CommonAppCompatActivity.showToast(My_service_detailActivity.this, responce);
            }
        }, true, this);
        task.execute();
    }

    private void makeGetMyServiceDetail(String id) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("id", id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.GET_APPOINTMENT_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                try {
                    JSONObject jsonObject = new JSONObject(response);

                    ids = jsonObject.getString("id");
                    String user_id = jsonObject.getString("user_id");
                    String address_id = jsonObject.getString("address_id");
                    String appointment_date = jsonObject.getString("appointment_date");
                    String start_time = jsonObject.getString("start_time");
                    String time_token = jsonObject.getString("time_token");
                    String visit_at = jsonObject.getString("visit_at");
                    String promo_code = jsonObject.getString("promo_code");
                    status = jsonObject.getString("status");
                    String created_at = jsonObject.getString("created_at");
                    String payment_type = jsonObject.getString("payment_type");
                    String payment_ref = jsonObject.getString("payment_ref");
                    String payment_mode = jsonObject.getString("payment_mode");
                    String payment_amount = jsonObject.getString("payment_amount");
                    String discount = jsonObject.getString("discount");
                    String total_amount = jsonObject.getString("total_amount");
                    String extra_charges = jsonObject.getString("extra_charges");
                    String net_amount = jsonObject.getString("net_amount");
                    String total_time = jsonObject.getString("total_time");
                    String pros_id = jsonObject.getString("pros_id");
                    String start_at = jsonObject.getString("start_at");
                    String end_at = jsonObject.getString("end_at");
                    String start_lat = jsonObject.getString("start_lat");
                    String end_lat = jsonObject.getString("end_lat");
                    String start_lon = jsonObject.getString("start_lon");
                    String end_lon = jsonObject.getString("end_lon");
                    String user_fullname = jsonObject.getString("user_fullname");
                    String user_phone = jsonObject.getString("user_phone");
                    String user_email = jsonObject.getString("user_email");
                    String delivery_address = jsonObject.getString("delivery_address");
                    String delivery_mobilenumber = jsonObject.getString("delivery_mobilenumber");
                    String delivery_landmark = jsonObject.getString("delivery_landmark");
                    String delivery_city = jsonObject.getString("delivery_city");
                    String delivery_fullname = jsonObject.getString("delivery_fullname");
                    String delivery_zipcode = jsonObject.getString("delivery_zipcode");
                    String pros_name = jsonObject.getString("pros_name");
                    String service_count = jsonObject.getString("service_count");
                    String total_service_amount = jsonObject.getString("total_service_amount");
                    String approx_time = jsonObject.getString("approx_time");
                    String taken_time = jsonObject.getString("taken_time");
                    String avg_rating = jsonObject.getString("avg_rating");
                    String total_rating = jsonObject.getString("total_rating");
                    String review_count = jsonObject.getString("review_count");


                    if (status.equals("0")) {
                        ll_user.setVisibility(View.GONE);
                        tv_cancel.setText(getResources().getString(R.string.cancel));

                        String[] totaltime = total_time.split(":");

                        String finalTime = "";
                        if (!totaltime[0].equals("00")) {
                            finalTime += totaltime[0] + "hr ";
                        }
                        if (!totaltime[1].equals("00")) {
                            finalTime += totaltime[1] + "min ";
                        }

                        tv_total_time.setText(finalTime);

                        tv_visit_time.setText(CommonAppCompatActivity.getTime12(visit_at));
                        tv_price.setText(CommonAppCompatActivity.getPriceWithCurrency(My_service_detailActivity.this, net_amount));
                        tv_price_status.setText(getResources().getString(R.string.approx_price));
                        tv_time_status.setText(getResources().getString(R.string.approx_time));
                    } else if (status.equals("1")) {
                        tv_cancel.setVisibility(View.GONE);

                        String[] totaltime = total_time.split(":");

                        String finalTime = "";
                        if (!totaltime[0].equals("00")) {
                            finalTime += totaltime[0] + "hr ";
                        }
                        if (!totaltime[1].equals("00")) {
                            finalTime += totaltime[1] + "min ";
                        }

                        tv_total_time.setText(finalTime);

                        tv_visit_time.setText(CommonAppCompatActivity.getTime12(visit_at));
                        tv_price.setText(CommonAppCompatActivity.getPriceWithCurrency(My_service_detailActivity.this, net_amount));
                        tv_price_status.setText(getResources().getString(R.string.approx_price));
                        tv_time_status.setText(getResources().getString(R.string.approx_time));
                    } else if (status.equals("2")) {
                        tv_cancel.setVisibility(View.GONE);

                        String[] totaltime = total_time.split(":");

                        String finalTime = "";
                        if (!totaltime[0].equals("00")) {
                            finalTime += totaltime[0] + "hr ";
                        }
                        if (!totaltime[1].equals("00")) {
                            finalTime += totaltime[1] + "min ";
                        }

                        tv_total_time.setText(finalTime);

                        tv_visit_time.setText(CommonAppCompatActivity.getTime12(visit_at));
                        tv_price.setText(CommonAppCompatActivity.getPriceWithCurrency(My_service_detailActivity.this, net_amount));
                        tv_price_status.setText(getResources().getString(R.string.approx_price));
                        tv_time_status.setText(getResources().getString(R.string.approx_time));

                        /*tv_price.setText(" " + payment_amount);
                        tv_total_time.setText(" " + payment_type);
                        tv_price_status.setText(getResources().getString(R.string.paid_amount));
                        tv_time_status.setText(getResources().getString(R.string.paid_by));*/
                    } else if (status.equals("3")) {
                        ll_visit.setVisibility(View.GONE);
                        if (avg_rating.equals("0")) {
                            tv_cancel.setVisibility(View.VISIBLE);
                            tv_cancel.setText(getResources().getString(R.string.add_review));
                        } else {
                            tv_cancel.setVisibility(View.GONE);
                            ratingBar.setVisibility(View.VISIBLE);
                            ratingBar.setRating(Float.valueOf(avg_rating));
                        }

                        tv_price.setText(" " + CommonAppCompatActivity.getPriceWithCurrency(My_service_detailActivity.this, payment_amount));
                        tv_total_time.setText(" " + payment_type);
                        tv_price_status.setText(getResources().getString(R.string.paid_amount));
                        tv_time_status.setText(getResources().getString(R.string.paid_by));
                    }

                    if (status.equals("1")) {
                        ll_start.setVisibility(View.GONE);
                        ll_end.setVisibility(View.GONE);
                        tv_date.setText(CommonAppCompatActivity.getConvertDateTime(created_at, 1));
                    }
                    if (status.equals("2")) {
                        ll_start.setVisibility(View.VISIBLE);
                        ll_end.setVisibility(View.GONE);
                        tv_date.setText(CommonAppCompatActivity.getConvertDateTime(created_at, 1));
                        tv_start.setText(CommonAppCompatActivity.getConvertDateTime(start_at, 2));
                    }
                    if (status.equals("3")) {
                        ll_start.setVisibility(View.VISIBLE);
                        ll_end.setVisibility(View.VISIBLE);
                        tv_date.setText(CommonAppCompatActivity.getConvertDateTime(created_at, 1));
                        tv_start.setText(CommonAppCompatActivity.getConvertDateTime(start_at, 2));
                        tv_end.setText(CommonAppCompatActivity.getConvertDateTime(end_at, 2));
                    }

                    tv_name.setText(pros_name);
                    tv_phone.setText(user_phone);

                    List<Service_detail_model> service_detail_modelList = new ArrayList<>();

                    // gson library use for getting api response from api and store as over model
                    Gson gson = new Gson();
                    Type listType = new TypeToken<List<Service_detail_model>>() {
                    }.getType();

                    // store gson values in list
                    service_detail_modelList = gson.fromJson(jsonObject.getString("service"), listType);

                    My_service_detail_adapter adapter = new My_service_detail_adapter(service_detail_modelList);
                    rv_order_detail.setAdapter(adapter);
                    adapter.notifyDataSetChanged();

                    // display toast message
                    CommonAppCompatActivity.showListToast(My_service_detailActivity.this, service_detail_modelList.isEmpty());

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                // display toast message
                CommonAppCompatActivity.showToast(My_service_detailActivity.this, responce);
            }
        }, true, this);
        task.execute();
    }

    // show custom alertdialog with layout for rating service
    private void showDialog(final String appointment_id) {
        final RatingBar ratingBar;
        final EditText et_comment;
        final TextInputLayout ti_comment;

        final AlertDialog.Builder alertDialog = new AlertDialog.Builder(this);
        LayoutInflater inflater = (LayoutInflater) getSystemService(LAYOUT_INFLATER_SERVICE);

        View layout = inflater.inflate(R.layout.dialog_add_review,
                (ViewGroup) findViewById(R.id.ll_review_dialog));
        et_comment = (EditText) layout.findViewById(R.id.et_review_dialog_comment);
        ratingBar = (RatingBar) layout.findViewById(R.id.rb_review_dialog);
        ti_comment = (TextInputLayout) layout.findViewById(R.id.ti_review_dialog_comment);

        final AlertDialog dialog;

        alertDialog.setView(layout);

        alertDialog.setPositiveButton(getResources().getString(R.string.add_review), null);

        alertDialog.setNegativeButton(getResources().getString(R.string.cancel), new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                dialog.dismiss();
            }
        });

        dialog = alertDialog.create();

        dialog.setOnShowListener(new DialogInterface.OnShowListener() {
            @Override
            public void onShow(DialogInterface arg0) {
                dialog.getButton(AlertDialog.BUTTON_NEGATIVE).setTextColor(Color.BLACK);

                Button ok = dialog.getButton(AlertDialog.BUTTON_POSITIVE);
                ok.setTextColor(Color.BLACK);
                ok.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        String getcomment = et_comment.getText().toString();

                        ti_comment.setError(null);

                        if (!getcomment.isEmpty()) {
                            String getuser_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                            String getrating = String.valueOf(Math.round(ratingBar.getRating()));

                            dialog.dismiss();

                            makeAddReview(getuser_id, getcomment, getrating, appointment_id);
                        } else {
                            ti_comment.setError(getResources().getString(R.string.error_field_required));
                        }
                    }
                });
            }
        });

        dialog.show();
    }

    private void makeAddReview(String user_id, String reviews, String ratings, String appointment_ids) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("user_id", user_id));
        params.add(new NameValuePair("reviews", reviews));
        params.add(new NameValuePair("ratings", ratings));
        params.add(new NameValuePair("appointment_id", appointment_ids));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.ADD_REVIEW_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);
                CommonAppCompatActivity.showToast(My_service_detailActivity.this, getResources().getString(R.string.your_rating_successfully_submitted));

                // check internet connection available or not
                if (ConnectivityReceiver.isConnected()) {
                    makeGetMyServiceDetail(appointment_id);
                } else {
                    // display snackbar in activity
                    ConnectivityReceiver.showSnackbar(My_service_detailActivity.this);
                }

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                CommonAppCompatActivity.showToast(My_service_detailActivity.this, responce);
            }
        }, true, this);
        task.execute();
    }

}
