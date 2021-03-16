package codecanyon.servpro;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import adapter.My_appointment_adapter;
import model.Appointment_list_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.DatabaseHandler;
import util.NameValuePair;
import util.Session_management;

public class My_servicesActivity extends AppCompatActivity {

    private static String TAG = My_servicesActivity.class.getSimpleName();

    private RecyclerView rv_my_service;

    private boolean startmain = false;

    private DatabaseHandler dbcart;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_my_services);

        dbcart = new DatabaseHandler(this);

        rv_my_service = (RecyclerView) findViewById(R.id.rv_my_services);
        rv_my_service.setLayoutManager(new LinearLayoutManager(this));

        if (getIntent().getStringExtra("startmain") != null) {
            startmain = true;
        }

    }

    private void makeGetMyAppointment(String user_id) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("user_id", user_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.GET_APPOINTMENT_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                List<Appointment_list_model> appointment_list_modelList = new ArrayList<>();

                Gson gson = new Gson();
                Type listType = new TypeToken<List<Appointment_list_model>>() {
                }.getType();

                // store gson value in list
                appointment_list_modelList = gson.fromJson(response, listType);

                // bind adapter using list
                My_appointment_adapter adapter = new My_appointment_adapter(appointment_list_modelList);
                rv_my_service.setAdapter(adapter);
                adapter.notifyDataSetChanged();

                // display toast message
                CommonAppCompatActivity.showListToast(My_servicesActivity.this, appointment_list_modelList.isEmpty());
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                // display toast message
                CommonAppCompatActivity.showToast(My_servicesActivity.this, responce);
            }
        }, true, this);
        task.execute();
    }


    @Override
    public boolean onCreateOptionsMenu(final Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getSupportActionBar().setHomeButtonEnabled(true);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        getMenuInflater().inflate(R.menu.main, menu);

        ActionBar mActionBar = getSupportActionBar();
        LayoutInflater mInflater = LayoutInflater.from(this);

        View mCustomView = mInflater.inflate(R.layout.actionbar_layout, null);
        TextView tv_title = (TextView) mCustomView.findViewById(R.id.tv_actionbar_title);
        if (tv_title != null && getSupportActionBar().getTitle() != null) {
            tv_title.setText(getSupportActionBar().getTitle().toString());
        }

        mActionBar.setCustomView(mCustomView);
        mActionBar.setDisplayShowCustomEnabled(true);

        getSupportActionBar().setDisplayShowTitleEnabled(false);

        final MenuItem cart = menu.findItem(R.id.action_cart);

        // this is use for custom layout on actionbar item click event call
        View count = cart.getActionView();
        count.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                menu.performIdentifierAction(cart.getItemId(), 0);
            }
        });

        TextView totalBudgetCount = (TextView) count.findViewById(R.id.tv_action_cart);

        if (totalBudgetCount != null) {
            totalBudgetCount.setText("" + dbcart.getCartCount());
        }

        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                if (startmain) {
                    Intent i = new Intent(My_servicesActivity.this, MainActivity.class);
                    i.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(i);
                }
                finish();
                return true;
            case R.id.action_cart:
                if (dbcart.getCartCount() > 0) {
                    Intent cartIntent = new Intent(this, CartActivity.class);
                    startActivity(cartIntent);
                } else {
                    CommonAppCompatActivity.showToast(this, getResources().getString(R.string.cart_empty));
                }
                return true;
            default:
                return super.onOptionsItemSelected(menuItem);
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        if (startmain) {
            Intent i = new Intent(My_servicesActivity.this, MainActivity.class);
            i.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(i);
        }
        finish();
    }

    @Override
    protected void onResume() {
        super.onResume();

        // check internet connection is available or not
        if (ConnectivityReceiver.isConnected()) {
            Session_management sessionManagement = new Session_management(this);
            String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
            makeGetMyAppointment(user_id);
        } else {
            // show snackbar in activity
            ConnectivityReceiver.showSnackbar(this);
        }
    }


}
