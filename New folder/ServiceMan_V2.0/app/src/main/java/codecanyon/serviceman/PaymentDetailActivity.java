package codecanyon.serviceman;

import com.google.android.material.tabs.TabLayout;

import android.os.Bundle;

import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.util.Log;
import android.view.View;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import adapter.PaymentDetailAdapter;
import model.PaymentDetailModel;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.GPSTracker;
import util.NameValuePair;
import util.Session_management;

public class PaymentDetailActivity extends CommonAppCompatActivity {

    private static String TAG = PaymentDetailActivity.class.getSimpleName();

    private TabLayout tabLayout;

    private String appointment_id;
    private String list;
    private String final_amount;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_payment_detail);

        list = getIntent().getStringExtra("list");
        appointment_id = getIntent().getStringExtra("appointment_id");
        String total_time = getIntent().getStringExtra("total_time");
        String service_amount = getIntent().getStringExtra("service_amount");
        String total_amount = getIntent().getStringExtra("total_amount");

        TextView tv_service_charge = (TextView) findViewById(R.id.tv_paymentdetail_service_charge);
        TextView tv_extra_charge = (TextView) findViewById(R.id.tv_paymentdetail_extra_charge);
        TextView tv_offer_discount = (TextView) findViewById(R.id.tv_paymentdetail_offer);
        TextView tv_total_payment = (TextView) findViewById(R.id.tv_paymentdetail_total_payment);
        TextView tv_amount = (TextView) findViewById(R.id.tv_paymentdetail_amount);
        TextView tv_total_time = (TextView) findViewById(R.id.tv_paymentdetail_spenttime);
        tabLayout = (TabLayout) findViewById(R.id.tl_paymentdetail_type);
        RecyclerView rv_paymentdetail = (RecyclerView) findViewById(R.id.rv_paymentdetail);
        rv_paymentdetail.setLayoutManager(new LinearLayoutManager(this));

        final_amount = "" + (Integer.parseInt(total_amount) + Integer.parseInt(service_amount));

        tv_service_charge.setText(CommonAppCompatActivity.getPriceWithCurrency(this, service_amount));
        tv_extra_charge.setText(CommonAppCompatActivity.getPriceWithCurrency(this, total_amount));
        tv_total_time.setText(total_time);
        tv_total_payment.setText(CommonAppCompatActivity.getPriceWithCurrency(this, final_amount));
        tv_amount.setText(CommonAppCompatActivity.getPriceWithCurrency(this, final_amount));

        try {
            JSONArray jsonArray = new JSONArray(list);

            List<PaymentDetailModel> paymentDetailModelList = new ArrayList<>();

            for (int i = 0; i < jsonArray.length(); i++) {
                JSONObject jsonObject = jsonArray.getJSONObject(i);

                PaymentDetailModel model = new PaymentDetailModel();
                model.setCharge(jsonObject.getString("charge"));
                model.setQty(jsonObject.getString("qty"));
                model.setTitle(jsonObject.getString("title"));

                paymentDetailModelList.add(model);
            }

            PaymentDetailAdapter adapter = new PaymentDetailAdapter(paymentDetailModelList);
            rv_paymentdetail.setAdapter(adapter);

        } catch (JSONException e) {
            e.printStackTrace();
        }

    }

    public void paymentClick(View view) {

        String payment_type = "";

        if (tabLayout.getSelectedTabPosition() == 0) {
            payment_type = "cash";
        } else if (tabLayout.getSelectedTabPosition() == 1) {
            payment_type = "card";
        }

        Double cur_latitude = 0.0, cur_longitude = 0.0;

        GPSTracker gpsTracker = new GPSTracker(this);
        if (gpsTracker.canGetLocation()) {
            if (gpsTracker.getLatitude() != 0.0)
                cur_latitude = gpsTracker.getLatitude();
            if (gpsTracker.getLongitude() != 0.0)
                cur_longitude = gpsTracker.getLongitude();

        }

        if (ConnectivityReceiver.isConnected()) {
            Session_management sessionManagement = new Session_management(this);
            String userid = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);

            makeAddPaid(appointment_id, userid, cur_latitude.toString(), cur_longitude.toString(), final_amount, payment_type, "xyz", list);
        } else {
            ConnectivityReceiver.showSnackbar(this);
        }

    }

    private void makeAddPaid(String appointment_id, String user_id, String lat, String lon, String payment_amount, String payment_type, String payment_ref, String extras) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("appointment_id", appointment_id));
        params.add(new NameValuePair("user_id", user_id));
        params.add(new NameValuePair("lat", lat));
        params.add(new NameValuePair("lon", lon));
        params.add(new NameValuePair("payment_amount", payment_amount));
        params.add(new NameValuePair("payment_type", payment_type));
        params.add(new NameValuePair("payment_ref", payment_ref));
        params.add(new NameValuePair("extras", extras));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.ADD_PAID_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                // display toast message
                CommonAppCompatActivity.showToast(PaymentDetailActivity.this, response);

                CommonAppCompatActivity.setPaymentDone(PaymentDetailActivity.this, true);
                finish();
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                // display toast message
                CommonAppCompatActivity.showToast(PaymentDetailActivity.this, responce);
            }
        }, true, this);
        task.execute();
    }

}
