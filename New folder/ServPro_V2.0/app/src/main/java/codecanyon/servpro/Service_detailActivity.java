package codecanyon.servpro;

import android.app.DatePickerDialog;
import android.content.ClipData;
import android.content.ClipboardManager;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.DatePicker;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;

import androidx.cardview.widget.CardView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;

import Config.BaseURL;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.DatabaseHandler;
import util.NameValuePair;
import util.Session_management;

public class Service_detailActivity extends CommonAppCompatActivity implements View.OnClickListener {

    private static String TAG = Service_detailActivity.class.getSimpleName();

    private TextView tv_add_address, tv_add_date, tv_address, tv_total_price, tv_total_time;
    private LinearLayout ll_book;
    private CardView cv_morning, cv_afternoon, cv_evening;
    private RadioButton rb_morning, rb_afternoon, rb_evening;
    private TextView tv_morning_start_time, tv_afternoon_start_time, tv_evening_start_time;
    private TextView tv_morning_end_time, tv_afternoon_end_time, tv_evening_end_time;
    private TextView tv_appointment_morning, tv_appointment_afternoon, tv_appointment_evening;
    private ImageView iv_edit;

    private String get_date;
    private String get_time_slot;
    private String get_time_token;
    private String promo_code = "";
    private String offer_discount;

    private String morning_time_slot, afternoon_time_slot, evening_time_slot;

    private Session_management sessionManagement;
    private DatabaseHandler dbcart;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_service_detail);

        sessionManagement = new Session_management(this);
        dbcart = new DatabaseHandler(this);

        String getTotal_price = getIntent().getStringExtra("total_price");
        String getTotal_time = getIntent().getStringExtra("total_time");
        if (getIntent().getStringExtra("promo_code") != null) {
            promo_code = getIntent().getStringExtra("promo_code");
            offer_discount = getIntent().getStringExtra("offer_discount");
        }

        tv_add_date = (TextView) findViewById(R.id.tv_address_detail_date);
        tv_add_address = (TextView) findViewById(R.id.tv_service_detail_address_edit);
        tv_address = (TextView) findViewById(R.id.tv_service_detail_address);
        tv_total_price = (TextView) findViewById(R.id.tv_total_price);
        tv_total_time = (TextView) findViewById(R.id.tv_total_time);
        iv_edit = (ImageView) findViewById(R.id.iv_service_detail_edit);
        cv_morning = (CardView) findViewById(R.id.cv_service_detail_schedule_morning);
        cv_afternoon = (CardView) findViewById(R.id.cv_service_detail_schedule_afternoon);
        cv_evening = (CardView) findViewById(R.id.cv_service_detail_schedule_evening);
        LinearLayout ll_date = (LinearLayout) findViewById(R.id.ll_service_detail_delivery_date);
        ll_book = (LinearLayout) findViewById(R.id.ll_book);
        TextView tv_discount_text = (TextView) findViewById(R.id.tv_discount_text);
        TextView tv_discount = (TextView) findViewById(R.id.tv_total_discount);
        TextView tv_final_price = (TextView) findViewById(R.id.tv_total_final_price);
        LinearLayout ll_discount = (LinearLayout) findViewById(R.id.ll_discount);
        LinearLayout ll_finalprice = (LinearLayout) findViewById(R.id.ll_finalprice);

        rb_morning = (RadioButton) findViewById(R.id.rb_morning);
        rb_afternoon = (RadioButton) findViewById(R.id.rb_afternoon);
        rb_evening = (RadioButton) findViewById(R.id.rb_evening);
        tv_morning_start_time = (TextView) findViewById(R.id.tv_morning_time_start);
        tv_afternoon_start_time = (TextView) findViewById(R.id.tv_afternoon_time_start);
        tv_evening_start_time = (TextView) findViewById(R.id.tv_evening_time_start);
        tv_morning_end_time = (TextView) findViewById(R.id.tv_morning_time_end);
        tv_afternoon_end_time = (TextView) findViewById(R.id.tv_afternoon_time_end);
        tv_evening_end_time = (TextView) findViewById(R.id.tv_evening_time_end);
        tv_appointment_morning = (TextView) findViewById(R.id.tv_morning_appointment);
        tv_appointment_afternoon = (TextView) findViewById(R.id.tv_afternoon_appointment);
        tv_appointment_evening = (TextView) findViewById(R.id.tv_evening_appointment);

        cv_morning.setVisibility(View.GONE);
        cv_afternoon.setVisibility(View.GONE);
        cv_evening.setVisibility(View.GONE);

        if (offer_discount != null) {
            ll_discount.setVisibility(View.VISIBLE);
            ll_finalprice.setVisibility(View.VISIBLE);

            tv_discount_text.setText(getResources().getString(R.string.offer_code) + " " + promo_code + " " + offer_discount + "% ):");

            String discount = String.format(Locale.US, "%.2f", getDiscountPrice(offer_discount, getTotal_price, false));
            String finalprice = String.format(Locale.US, "%.2f", getDiscountPrice(offer_discount, getTotal_price, true));

            tv_total_price.setText(CommonAppCompatActivity.getPriceWithCurrency(this, getTotal_price));
            tv_discount.setText(CommonAppCompatActivity.getPriceWithCurrency(this, discount));
            tv_final_price.setText(CommonAppCompatActivity.getPriceWithCurrency(this, getTotal_price + " - " + discount + " = " + finalprice));
        } else {
            tv_total_price.setText(CommonAppCompatActivity.getPriceWithCurrency(this, getTotal_price));
        }

        tv_total_time.setText(getTotal_time);

        ll_date.setOnClickListener(this);
        tv_add_address.setOnClickListener(this);
        rb_morning.setOnClickListener(this);
        rb_afternoon.setOnClickListener(this);
        rb_evening.setOnClickListener(this);
        ll_book.setOnClickListener(this);

        String date = new SimpleDateFormat("yyyy-MM-dd").format(new Date());
        get_date = date;
        displayDate(date);

    }

    @Override
    public void onClick(View view) {
        Intent i = null;

        switch (view.getId()) {
            case R.id.tv_service_detail_address_edit:
                i = new Intent(Service_detailActivity.this, My_delivery_addressActivity.class);
                i.putExtra("select", "true");
                break;
            case R.id.ll_service_detail_delivery_date:
                setDate();
                break;
            case R.id.rb_morning:
                rb_afternoon.setChecked(false);
                rb_evening.setChecked(false);
                get_time_token = "1";
                get_time_slot = morning_time_slot;
                break;
            case R.id.rb_afternoon:
                rb_morning.setChecked(false);
                rb_evening.setChecked(false);
                get_time_token = "2";
                get_time_slot = afternoon_time_slot;
                break;
            case R.id.rb_evening:
                rb_afternoon.setChecked(false);
                rb_morning.setChecked(false);
                get_time_token = "3";
                get_time_slot = evening_time_slot;
                break;
            case R.id.ll_book:
                if (rb_afternoon.isChecked() || rb_morning.isChecked() || rb_evening.isChecked()) {
                    if (sessionManagement.getDeliveryAddress().get(BaseURL.ID) != null) {
                        attemptAppointment();
                    } else {
                        CommonAppCompatActivity.showToast(Service_detailActivity.this, getResources().getString(R.string.please_select_delivery_address));
                    }
                } else {
                    CommonAppCompatActivity.showToast(Service_detailActivity.this, getResources().getString(R.string.please_select_time));
                }
                break;
        }

        if (i != null) {
            startActivity(i);
        }
    }

    private void setDate() {
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

                        get_date = "" + year + "-" + (monthOfYear + 1) + "-" + dayOfMonth;
                        displayDate(get_date);

                    }
                }, mYear, mMonth, mDay);
        datePickerDialog.getDatePicker().setMinDate(System.currentTimeMillis() - 1000);
        datePickerDialog.show();
    }

    private void displayDate(String get_dob) {
        try {
            String inputPattern = "yyyy-MM-dd";
            String outputPattern = "dd-MM-yyyy";
            SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern);
            SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern);

            Date date = inputFormat.parse(get_dob);
            String str = outputFormat.format(date);

            get_dob = inputFormat.format(date);

            tv_add_date.setText(str);

            // check internet connection is available or not
            if (ConnectivityReceiver.isConnected()) {
                makeGetSchedule(get_dob, dbcart.getTotalTime(true));
            } else {
                // display snackbar
                ConnectivityReceiver.showSnackbar(Service_detailActivity.this);
            }

        } catch (ParseException e) {
            e.printStackTrace();
            tv_add_date.setText(get_dob);
        }
    }

    @Override
    protected void onResume() {
        super.onResume();

        if (sessionManagement.getDeliveryAddress().get(BaseURL.ID) != null) {

            StringBuilder sb = new StringBuilder();

            sb.append(sessionManagement.getDeliveryAddress().get(BaseURL.DELIVERY_FULLNAME) + "\n");
            sb.append(sessionManagement.getDeliveryAddress().get(BaseURL.DELIVERY_MOBILENUMBER) + "\n");
            sb.append(sessionManagement.getDeliveryAddress().get(BaseURL.DELIVERY_ADDRESS) + "\n");
            sb.append(sessionManagement.getDeliveryAddress().get(BaseURL.DELIVERY_LANDMARK) + "\n");
            sb.append(sessionManagement.getDeliveryAddress().get(BaseURL.DELIVERY_ZIPCODE) + "\n");
            sb.append(sessionManagement.getDeliveryAddress().get(BaseURL.DELIVERY_CITY) + "\n");

            tv_address.setText(sb.toString());

            iv_edit.setVisibility(View.VISIBLE);
            tv_add_address.setText(getResources().getString(R.string.edit));
        } else {
            iv_edit.setVisibility(View.GONE);
            tv_add_address.setText("+ " + getResources().getString(R.string.add));
        }

    }

    private void makeGetSchedule(String date, String total_time) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("date", date));
        params.add(new NameValuePair("total_time", total_time));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.GET_SCHEDULE_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                try {
                    // convert string to jsonobject
                    JSONObject jsonObject = new JSONObject(response);

                    // getting string from jsonobject
                    if (jsonObject.has("morning_appointment")) {
                        cv_morning.setVisibility(View.VISIBLE);

                        int morning_appointment = Integer.parseInt(jsonObject.getString("morning_appointment"));
                        morning_time_slot = jsonObject.getString("morning_time_slot");
                        String morning_time_end = jsonObject.getString("morning_time_end");

                        tv_morning_start_time.setText(CommonAppCompatActivity.getTime12(morning_time_slot));
                        tv_morning_end_time.setText(CommonAppCompatActivity.getTime12(morning_time_end));
                        tv_appointment_morning.setText(String.valueOf(morning_appointment + 1));
                    }

                    if (jsonObject.has("afternoon_appointment")) {
                        cv_afternoon.setVisibility(View.VISIBLE);

                        int afternoon_appointment = Integer.parseInt(jsonObject.getString("afternoon_appointment"));
                        afternoon_time_slot = jsonObject.getString("afternoon_time_slot");
                        String afternoon_time_end = jsonObject.getString("afternoon_time_end");

                        tv_afternoon_start_time.setText(CommonAppCompatActivity.getTime12(afternoon_time_slot));
                        tv_afternoon_end_time.setText(CommonAppCompatActivity.getTime12(afternoon_time_end));
                        tv_appointment_afternoon.setText(String.valueOf(afternoon_appointment + 1));
                    }

                    if (jsonObject.has("evening_appointment")) {
                        cv_evening.setVisibility(View.VISIBLE);

                        int evening_appointment = Integer.parseInt(jsonObject.getString("evening_appointment"));
                        evening_time_slot = jsonObject.getString("evening_time_slot");
                        String evening_time_end = jsonObject.getString("evening_time_end");

                        tv_evening_start_time.setText(CommonAppCompatActivity.getTime12(evening_time_slot));
                        tv_evening_end_time.setText(CommonAppCompatActivity.getTime12(evening_time_end));
                        tv_appointment_evening.setText(String.valueOf(evening_appointment + 1));
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

                ll_book.setVisibility(View.VISIBLE);
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                CommonAppCompatActivity.showToast(Service_detailActivity.this, responce);
                ll_book.setVisibility(View.GONE);
            }
        }, true, this);
        task.execute();
    }

    // this will get the data from database and put those data in arraylist with hasemap
    private void attemptAppointment() {
        JSONArray passArray = null;

        // retrive data from cart database
        ArrayList<HashMap<String, String>> items = dbcart.getCartAll();
        if (items.size() > 0) {
            passArray = new JSONArray();
            for (int i = 0; i < items.size(); i++) {
                HashMap<String, String> map = items.get(i);

                JSONObject jObjP = new JSONObject();

                try {
                    jObjP.put("service_id", map.get("id"));
                    jObjP.put("service_qty", map.get("qty"));
                    jObjP.put("service_time", map.get("service_approxtime"));
                    jObjP.put("service_amount", map.get("service_price"));

                    // put json data in arraylist
                    passArray.put(jObjP);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }

        // check internet connection is available or not
        if (ConnectivityReceiver.isConnected()) {
            String getuser_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
            String getaddress_id = sessionManagement.getDeliveryAddress().get(BaseURL.ID);
            String total_time = tv_total_time.getText().toString().replace("hr ", ":").replace("min ", ":00");
            makeAddAppointment(getuser_id, getaddress_id, get_date, get_time_slot,
                    get_time_token, promo_code, passArray, total_time);
        } else {
            // display snackbar
            ConnectivityReceiver.showSnackbar(Service_detailActivity.this);
        }

    }

    private void makeAddAppointment(String user_id, String address, String date, String start_time,
                                    String time_token, String promo_code, JSONArray passArray, String total_time) {

        // adding parameters in arraylist
        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("user_id", user_id));
        params.add(new NameValuePair("address", address));
        params.add(new NameValuePair("date", date));
        params.add(new NameValuePair("start_time", start_time));
        params.add(new NameValuePair("time_token", time_token));
        params.add(new NameValuePair("promo_code", promo_code));
        params.add(new NameValuePair("services", passArray.toString()));
        params.add(new NameValuePair("total_time", total_time));

        StringBuilder postdata = new StringBuilder();
        for (NameValuePair pair : params) {
            postdata.append(",").append(pair.name + "=" + pair.value);
        }

        Log.i(TAG, "makeAddAppointment: " + postdata);

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task2 = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.ADD_APPOINTMENT_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                try {
                    // convert string to jsonobject
                    JSONObject jsonObject = new JSONObject(response);

                    // getting string from jsonobject
                    String id = jsonObject.getString("id");
                    String appointment_date = jsonObject.getString("appointment_date");
                    String start_time = jsonObject.getString("start_time");

                    String msg = getResources().getString(R.string.your_appointment) + id +
                            getResources().getString(R.string.has_been) + appointment_date +
                            getResources().getString(R.string.at) + start_time + getResources().getString(R.string.update_soon);

                    dbcart.clearCart();

                    Intent i = new Intent(Service_detailActivity.this, Thanks_msgActivity.class);
                    i.putExtra("msg", msg);
                    i.putExtra("response", response);
                    startActivity(i);
                    finish();

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                CommonAppCompatActivity.showToast(Service_detailActivity.this, responce);
            }
        }, true, this);
        task2.execute();
    }


    // get discount by price and discount amount
    private Double getDiscountPrice(String discount, String price, boolean getEffectedprice) {
        Double discount1 = Double.parseDouble(discount);
        Double price1 = Double.parseDouble(price);
        Double discount_amount = discount1 * price1 / 100;

        if (getEffectedprice) {
            Double effected_price = price1 - discount_amount;
            return effected_price;
        } else {
            return discount_amount;
        }
    }


}
