package codecanyon.serviceman;

import android.content.Intent;
import android.os.Build;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;

import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.util.Log;
import android.util.TypedValue;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.WindowManager;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import adapter.ServiceDetailAdapter;
import model.Service_detail_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.GPSTracker;
import util.NameValuePair;
import util.Session_management;

public class Service_detailActivity extends CommonAppCompatActivity {

    private static String TAG = Service_detailActivity.class.getSimpleName();

    private TextView tv_name, tv_phone, tv_address, tv_total_price, tv_total_time, tv_start;
    private RecyclerView rv_detail;

    private String ids;
    private String status;
    private String total_time;
    private String net_amount;
    private String start_time;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_service_detail);

        String appointment_id = getIntent().getStringExtra("appointment_id");

        tv_name = (TextView) findViewById(R.id.tv_servicedetail_name);
        tv_phone = (TextView) findViewById(R.id.tv_servicedetail_phone);
        tv_address = (TextView) findViewById(R.id.tv_servicedetail_address);
        tv_total_price = (TextView) findViewById(R.id.tv_total_price);
        tv_total_time = (TextView) findViewById(R.id.tv_total_time);
        tv_start = (TextView) findViewById(R.id.tv_start);
        rv_detail = (RecyclerView) findViewById(R.id.rv_detail);
        rv_detail.setLayoutManager(new LinearLayoutManager(this));

        // check internet connection available or not
        if (ConnectivityReceiver.isConnected()) {
            makeGetServiceDetail(appointment_id);
        } else {
            // display snackbar in activity
            ConnectivityReceiver.showSnackbar(this);
        }

        tv_start.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (status.equals("1")) {
                    if (ConnectivityReceiver.isConnected()) {
                        if (tv_start.getText().toString().equals(getResources().getString(R.string.start))) {

                            Session_management sessionManagement = new Session_management(Service_detailActivity.this);
                            String userid = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);

                            Double cur_latitude = 0.0, cur_longitude = 0.0;

                            GPSTracker gpsTracker = new GPSTracker(Service_detailActivity.this);
                            if (gpsTracker.canGetLocation()) {
                                if (gpsTracker.getLatitude() != 0.0)
                                    cur_latitude = gpsTracker.getLatitude();
                                if (gpsTracker.getLongitude() != 0.0)
                                    cur_longitude = gpsTracker.getLongitude();

                            } /*else {
                                    gpsTracker.showSettingsAlert();
                                }*/

                            makeStartService(ids, userid, cur_latitude.toString(), cur_longitude.toString());
                        }
                    } else {
                        ConnectivityReceiver.showSnackbar(Service_detailActivity.this);
                    }
                } else if (status.equals("2")) {

                    CommonAppCompatActivity.setPaymentDone(Service_detailActivity.this, false);

                    showServiceDone(ids, start_time, net_amount);
                }
            }
        });

    }

    private void makeGetServiceDetail(String id) {

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
                    start_time = jsonObject.getString("start_time");
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
                    net_amount = jsonObject.getString("net_amount");
                    total_time = jsonObject.getString("total_time");
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

                    tv_name.setText(pros_name);
                    tv_phone.setText(user_phone);
                    tv_address.setText(delivery_address + ", " + delivery_landmark + ", " + delivery_city + ", " + delivery_zipcode);
                    tv_total_price.setText(CommonAppCompatActivity.getPriceWithCurrency(Service_detailActivity.this, net_amount));

                    String[] totaltime = total_time.split(":");

                    String finalTime = "";
                    if (!totaltime[0].equals("00")) {
                        finalTime += totaltime[0] + "hr ";
                    }
                    if (!totaltime[1].equals("00")) {
                        finalTime += totaltime[1] + "min ";
                    }

                    tv_total_time.setText(finalTime);

                    if (status.equals("0")) {
                        tv_start.setText(getResources().getString(R.string.start));
                    } else if (status.equals("1")) {
                        tv_start.setText(getResources().getString(R.string.start));
                    } else if (status.equals("2")) {
                        tv_start.setText(getResources().getString(R.string.complete));
                    } else if (status.equals("3")) {
                        tv_start.setText(getResources().getString(R.string.complete));
                    }


                    List<Service_detail_model> service_detail_modelList = new ArrayList<>();

                    // gson library use for getting api response from api and store as over model
                    Gson gson = new Gson();
                    Type listType = new TypeToken<List<Service_detail_model>>() {
                    }.getType();

                    // store gson values in list
                    service_detail_modelList = gson.fromJson(jsonObject.getString("service"), listType);

                    ServiceDetailAdapter adapter = new ServiceDetailAdapter(service_detail_modelList);
                    rv_detail.setAdapter(adapter);
                    adapter.notifyDataSetChanged();

                    // display toast message
                    CommonAppCompatActivity.showListToast(Service_detailActivity.this, service_detail_modelList.isEmpty());

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                // display toast message
                CommonAppCompatActivity.showToast(Service_detailActivity.this, responce);
            }
        }, true, this);
        task.execute();
    }

    private void makeStartService(String appointment_id, String user_id, String lat, String lon) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("appointment_id", appointment_id));
        params.add(new NameValuePair("user_id", user_id));
        params.add(new NameValuePair("lat", lat));
        params.add(new NameValuePair("lon", lon));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.START_SERVICE_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                status = "2";
                tv_start.setText(getResources().getString(R.string.complete));

                // display toast message
                CommonAppCompatActivity.showToast(Service_detailActivity.this, response);
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                // display toast message
                CommonAppCompatActivity.showToast(Service_detailActivity.this, responce);
            }
        }, true, this);
        task.execute();
    }

    private int count = 0;
    private boolean cancle = true;
    private AlertDialog alertDialog;
    private int finalExtraTotal = 0;

    // show image choosing dialog
    private void showServiceDone(final String appointment_id, String total_time, final String service_amount) {

        count = 0;
        cancle = true;

        AlertDialog.Builder dialogBuilder = new AlertDialog.Builder(this);

        // ...Irrelevant code for customizing the buttons and title
        LayoutInflater inflater = getLayoutInflater();
        final View dialogView = inflater.inflate(R.layout.dialog_service_done, null);
        dialogBuilder.setView(dialogView);

        ImageView fab_add = dialogView.findViewById(R.id.fab);
        final TextView tv_total_amount = dialogView.findViewById(R.id.tv_total_amount);
        TextView tv_paid = dialogView.findViewById(R.id.tv_service_paid);
        TextView tv_amount = dialogView.findViewById(R.id.tv_service_amount);
        final TextView tv_total_time = dialogView.findViewById(R.id.tv_service_total_time);
        final LinearLayout lm = dialogView.findViewById(R.id.ll_additional_charge);

        tv_total_time.setText(total_time);
        tv_amount.setText(CommonAppCompatActivity.getPriceWithCurrency(Service_detailActivity.this, service_amount));

        // create the layout params that will be used to define how your
        // button will be displayed
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
                LinearLayout.LayoutParams.WRAP_CONTENT, LinearLayout.LayoutParams.WRAP_CONTENT);

        alertDialog = dialogBuilder.create();
        alertDialog.show();
        alertDialog.getWindow().clearFlags(WindowManager.LayoutParams.FLAG_NOT_FOCUSABLE | WindowManager.LayoutParams.FLAG_ALT_FOCUSABLE_IM);
        alertDialog.getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_VISIBLE);

        final ArrayList<EditText> etPriceArray = new ArrayList<>();
        final ArrayList<EditText> etTextArray = new ArrayList<>();
        final ArrayList<EditText> etqtyArray = new ArrayList<>();

        fab_add.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                LinearLayout.LayoutParams layoutParams = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT);
                layoutParams.setMargins(0, 5, 0, 0);
                LinearLayout.LayoutParams layoutParams_name = new LinearLayout.LayoutParams(0, LinearLayout.LayoutParams.WRAP_CONTENT, 1);
                LinearLayout.LayoutParams layoutParams_price = new LinearLayout.LayoutParams(150, LinearLayout.LayoutParams.WRAP_CONTENT);
                layoutParams_price.setMargins(5, 0, 0, 0);
                LinearLayout.LayoutParams layoutParams_qty = new LinearLayout.LayoutParams(100, LinearLayout.LayoutParams.WRAP_CONTENT);
                layoutParams_qty.setMargins(5, 0, 0, 0);

                // fill in any details dynamically here
                LinearLayout linearLayout1 = new LinearLayout(Service_detailActivity.this);
                linearLayout1.setLayoutParams(layoutParams);
                linearLayout1.setGravity(Gravity.CENTER);
                linearLayout1.setOrientation(LinearLayout.HORIZONTAL);

                final EditText et_name = new EditText(Service_detailActivity.this);
                et_name.setLayoutParams(layoutParams_name);
                et_name.setTextColor(getResources().getColor(R.color.black));
                et_name.setTextSize(TypedValue.COMPLEX_UNIT_SP, 12F);
                et_name.setPadding(10, 10, 10, 10);
                et_name.setHint(getResources().getString(R.string.service));
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN) {
                    et_name.setBackground(getResources().getDrawable(R.drawable.xml_editext_border));
                }

                linearLayout1.addView(et_name);

                EditText et_price = new EditText(Service_detailActivity.this);
                et_price.setLayoutParams(layoutParams_price);
                et_price.setTextColor(getResources().getColor(R.color.black));
                et_price.setTextSize(TypedValue.COMPLEX_UNIT_SP, 12F);
                et_price.setInputType(InputType.TYPE_CLASS_NUMBER);
                et_price.setPadding(10, 10, 10, 10);
                et_price.setHint(getResources().getString(R.string.price));
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN) {
                    et_price.setBackground(getResources().getDrawable(R.drawable.xml_editext_border));
                }

                et_price.setFocusableInTouchMode(true);
                et_price.requestFocus();

                et_price.addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

                    }

                    @Override
                    public void onTextChanged(CharSequence charSequence, int ii, int i1, int i2) {
                        int totalamount = 0;
                        finalExtraTotal = 0;
                        cancle = false;

                        for (int i = 0; i < count; i++) {
                            if (!etPriceArray.get(i).getText().toString().isEmpty() && !etqtyArray.get(i).getText().toString().isEmpty()) {
                                int price = Integer.parseInt(etPriceArray.get(i).getText().toString());
                                int qty = Integer.parseInt(etqtyArray.get(i).getText().toString());
                                totalamount = totalamount + (price * qty);
                            } else {
                                cancle = true;
                            }

                            if (etTextArray.get(i).getText().toString().isEmpty()) {
                                cancle = true;
                            }

                            if (etqtyArray.get(i).getText().toString().isEmpty()) {
                                cancle = true;
                            }

                        }
                        //Log.e(TAG, "onClick: " + totalamount);
                        finalExtraTotal = totalamount;
                        tv_total_amount.setText(getResources().getString(R.string.total_amount) + CommonAppCompatActivity.getPriceWithCurrency(Service_detailActivity.this, String.format("%.1f", (Double.valueOf(totalamount) + Double.valueOf(service_amount)))));
                    }

                    @Override
                    public void afterTextChanged(Editable editable) {

                    }
                });

                linearLayout1.addView(et_price);


                final EditText et_qty = new EditText(Service_detailActivity.this);
                et_qty.setLayoutParams(layoutParams_qty);
                et_qty.setTextColor(getResources().getColor(R.color.black));
                et_qty.setTextSize(TypedValue.COMPLEX_UNIT_SP, 12F);
                et_qty.setInputType(InputType.TYPE_CLASS_NUMBER);
                et_qty.setPadding(10, 10, 10, 10);
                et_qty.setHint(getResources().getString(R.string.qty));
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN) {
                    et_qty.setBackground(getResources().getDrawable(R.drawable.xml_editext_border));
                }

                et_qty.addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

                    }

                    @Override
                    public void onTextChanged(CharSequence charSequence, int ii, int i1, int i2) {
                        int totalamount = 0;
                        finalExtraTotal = 0;
                        cancle = false;

                        for (int i = 0; i < count; i++) {
                            if (!etPriceArray.get(i).getText().toString().isEmpty() && !etqtyArray.get(i).getText().toString().isEmpty()) {
                                int price = Integer.parseInt(etPriceArray.get(i).getText().toString());
                                int qty = Integer.parseInt(etqtyArray.get(i).getText().toString());
                                totalamount = totalamount + (price * qty);
                            } else {
                                cancle = true;
                            }

                            if (etTextArray.get(i).getText().toString().isEmpty()) {
                                cancle = true;
                            }

                            if (etqtyArray.get(i).getText().toString().isEmpty()) {
                                cancle = true;
                            }

                        }
                        //Log.e(TAG, "onClick: " + totalamount);
                        finalExtraTotal = totalamount;
                        tv_total_amount.setText(getResources().getString(R.string.total_amount) + CommonAppCompatActivity.getPriceWithCurrency(Service_detailActivity.this, String.format("%.1f", (Double.valueOf(totalamount) + Double.valueOf(service_amount)))));
                    }

                    @Override
                    public void afterTextChanged(Editable editable) {

                    }
                });
                linearLayout1.addView(et_qty);

                etPriceArray.add(count, et_price);
                etTextArray.add(count, et_name);
                etqtyArray.add(count, et_qty);

                // add view in layout dynamically
                lm.addView(linearLayout1);

                count++;

                cancle = true;

            }
        });

        tv_paid.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (count > 0) {
                    if (cancle) {

                        cancle = false;

                        for (int i = 0; i < count; i++) {
                            if (etPriceArray.get(i).getText().toString().isEmpty()) {
                                etPriceArray.get(i).setError(getResources().getString(R.string.error_field_required));
                                cancle = true;
                            }

                            if (etTextArray.get(i).getText().toString().isEmpty()) {
                                etTextArray.get(i).setError(getResources().getString(R.string.error_field_required));
                                cancle = true;
                            }

                            if (etqtyArray.get(i).getText().toString().isEmpty()) {
                                etqtyArray.get(i).setError(getResources().getString(R.string.error_field_required));
                                cancle = true;
                            }

                        }
                    } else {

                        JSONArray passArray = new JSONArray();

                        for (int i = 0; i < count; i++) {

                            JSONObject jObjP = new JSONObject();

                            try {
                                jObjP.put("title", etTextArray.get(i).getText().toString());
                                jObjP.put("charge", etPriceArray.get(i).getText().toString());
                                jObjP.put("qty", etqtyArray.get(i).getText().toString());

                                passArray.put(jObjP);

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }

                        }

                        Intent paymentdetailIntent = new Intent(Service_detailActivity.this, PaymentDetailActivity.class);
                        paymentdetailIntent.putExtra("list", passArray.toString());
                        paymentdetailIntent.putExtra("appointment_id", appointment_id);
                        paymentdetailIntent.putExtra("service_amount", service_amount);
                        paymentdetailIntent.putExtra("total_amount", String.valueOf(finalExtraTotal));
                        paymentdetailIntent.putExtra("total_time", tv_total_time.getText().toString());
                        startActivity(paymentdetailIntent);

                    }
                } else {
                    Intent paymentdetailIntent = new Intent(Service_detailActivity.this, PaymentDetailActivity.class);
                    paymentdetailIntent.putExtra("list", "[]");
                    paymentdetailIntent.putExtra("appointment_id", appointment_id);
                    paymentdetailIntent.putExtra("service_amount", service_amount);
                    paymentdetailIntent.putExtra("total_amount", "00");
                    paymentdetailIntent.putExtra("total_time", total_time);
                    startActivity(paymentdetailIntent);
                }
            }
        });

    }

    @Override
    protected void onResume() {
        super.onResume();
        if (CommonAppCompatActivity.getPaymentDone(this)) {
            finish();
        }
    }
}
