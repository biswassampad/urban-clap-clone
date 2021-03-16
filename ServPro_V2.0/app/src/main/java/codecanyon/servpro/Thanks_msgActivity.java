package codecanyon.servpro;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;

import org.json.JSONException;
import org.json.JSONObject;

public class Thanks_msgActivity extends AppCompatActivity implements View.OnClickListener {

    private TextView tv_msg, tv_ref, tv_ondate, tv_visit_time, tv_amount;
    private Button tv_countinue, tv_track;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_thanks_msg);

        //tv_msg = (TextView) findViewById(R.id.tv_thanks_msg);
        tv_ref = (TextView) findViewById(R.id.tv_booking_ref);
        tv_ondate = (TextView) findViewById(R.id.tv_booking_ondate);
        tv_visit_time = (TextView) findViewById(R.id.tv_booking_visit_time);
        tv_amount = (TextView) findViewById(R.id.tv_booking_basic_amount);
        tv_countinue = (Button) findViewById(R.id.tv_thanks_countinue);
        tv_track = (Button) findViewById(R.id.tv_thanks_track);

        String msg = getIntent().getStringExtra("msg");

        try {
            JSONObject jsonObject = new JSONObject(getIntent().getStringExtra("response"));

            String id = jsonObject.getString("id");
            String user_id = jsonObject.getString("user_id");
            String address_id = jsonObject.getString("address_id");
            String appointment_date = jsonObject.getString("appointment_date");
            String start_time = jsonObject.getString("start_time");
            String time_token = jsonObject.getString("time_token");
            String visit_at = jsonObject.getString("visit_at");
            String promo_code = jsonObject.getString("promo_code");
            String status = jsonObject.getString("status");
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

            tv_ref.setText(id);
            tv_ondate.setText(appointment_date);
            //tv_visit_time.setText(visit_at);
            tv_amount.setText(CommonAppCompatActivity.getPriceWithCurrency(this, total_amount));

        } catch (JSONException e) {
            e.printStackTrace();
        }

        //tv_msg.setText(Html.fromHtml(msg));

        tv_countinue.setOnClickListener(this);
        tv_track.setOnClickListener(this);

    }

    @Override
    public void onClick(View view) {
        int id = view.getId();

        if (id == R.id.tv_thanks_countinue) {
            startMain();
        } else if (id == R.id.tv_thanks_track) {
            Intent i = new Intent(Thanks_msgActivity.this, My_servicesActivity.class);
            i.putExtra("startmain", "startmain");
            startActivity(i);
            finish();
        }
    }

    // redirect to main activity
    private void startMain() {
        Intent i = new Intent(Thanks_msgActivity.this, MainActivity.class);
        i.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(i);
        finish();
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
        ImageView imageView = (ImageView) mCustomView.findViewById(R.id.imageView);
        imageView.setVisibility(View.GONE);
        if (tv_title != null && getSupportActionBar().getTitle() != null) {
            tv_title.setText(getSupportActionBar().getTitle().toString());
        }

        mActionBar.setCustomView(mCustomView);
        mActionBar.setDisplayShowCustomEnabled(true);
        mActionBar.setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));

        getSupportActionBar().setDisplayShowTitleEnabled(false);

        MenuItem cart = menu.findItem(R.id.action_cart);
        cart.setVisible(false);

        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                startMain();
                return true;
            default:
                return super.onOptionsItemSelected(menuItem);
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        startMain();
    }

}
