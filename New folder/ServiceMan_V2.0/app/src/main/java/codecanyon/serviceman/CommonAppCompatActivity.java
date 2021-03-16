package codecanyon.serviceman;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;

import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.TextView;
import android.widget.Toast;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

import util.Session_management;

/**
 * Created by Rajesh on 2017-10-28.
 */

@SuppressLint("Registered")
public class CommonAppCompatActivity extends AppCompatActivity {

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setFullScreen(this);
    }

    @Override
    public boolean onCreateOptionsMenu(final Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getSupportActionBar().setHomeButtonEnabled(true);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowTitleEnabled(true);

        getMenuInflater().inflate(R.menu.main, menu);

        androidx.appcompat.app.ActionBar mActionBar = getSupportActionBar();
        LayoutInflater mInflater = LayoutInflater.from(this);

        View mCustomView = mInflater.inflate(R.layout.actionbar_layout, null);
        TextView tv_title = (TextView) mCustomView.findViewById(R.id.tv_actionbar_title);
        if (tv_title != null && getSupportActionBar().getTitle() != null) {
            tv_title.setText(getSupportActionBar().getTitle().toString());
        }

        mActionBar.setCustomView(mCustomView);
        mActionBar.setDisplayShowCustomEnabled(true);
        mActionBar.setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));

        getSupportActionBar().setDisplayShowTitleEnabled(false);

        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                //onBackPressed();
                this.finish();
                return true;
            default:
                return super.onOptionsItemSelected(menuItem);
        }
    }

    // common toast for listing in app
    public static void showListToast(Context context, boolean isEmpty) {
        if (isEmpty) {
            Toast.makeText(context, context.getResources().getString(R.string.record_not_found), Toast.LENGTH_SHORT).show();
        }
    }

    // common toast for app
    public static void showToast(Context context, String msg) {
        Toast.makeText(context, msg, Toast.LENGTH_SHORT).show();
    }

    public static void setPaymentDone(Context context, boolean done) {
        SharedPreferences paymentdone = context.getSharedPreferences("paymentdone", 0);
        paymentdone.edit().putBoolean("done", done).apply();
    }

    public static Boolean getPaymentDone(Context context) {
        SharedPreferences paymentdone = context.getSharedPreferences("paymentdone", 0);
        return paymentdone.getBoolean("done", true);
    }

    public static String getPriceWithCurrency(Context context, String price) {
        String currency = new Session_management.UserData().getSession(context, "currency");
        return price + " " + currency;
    }

    public static String getTimeDiffrence(String time) {
        try {

            SimpleDateFormat simpleDateFormat = new SimpleDateFormat("HH:mm:ss", Locale.getDefault());
            Date date1 = simpleDateFormat.parse(time);
            Date date2 = simpleDateFormat.parse(simpleDateFormat.format(Calendar.getInstance().getTime()));

            long difference = date2.getTime() - date1.getTime();
            int days = (int) (difference / (1000 * 60 * 60 * 24));
            int hours = (int) ((difference - (1000 * 60 * 60 * 24 * days)) / (1000 * 60 * 60));
            int min = (int) (difference - (1000 * 60 * 60 * 24 * days) - (1000 * 60 * 60 * hours)) / (1000 * 60);
            hours = (hours < 0 ? -hours : hours);
            Log.i("======= Hours", " :: " + hours);

            return hours + " Hour " + min + " min";

        } catch (Exception e) {
            e.printStackTrace();
            return "";
        }
    }

    public static String getTime12(String timein24) {
        SimpleDateFormat sdf12 = new SimpleDateFormat("hh:mm a", Locale.getDefault());
        SimpleDateFormat sdf24 = new SimpleDateFormat("HH:mm:ss", Locale.getDefault());

        try {
            return sdf12.format(sdf24.parse(timein24)).replace("a.m.", "AM").replace("p.m.", "PM");
        } catch (ParseException e) {
            e.printStackTrace();
            return "";
        }
    }

    public static String getConvertDate(String convertdate) {
        try {
            String inputPattern = "yyyy-MM-dd";
            String outputPattern = "dd-MM-yyyy";
            SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern, Locale.getDefault());
            SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern, Locale.getDefault());

            Date date = inputFormat.parse(convertdate);
            String str = outputFormat.format(date);

            return str;
        } catch (ParseException e) {
            e.printStackTrace();
            return "";
        }
    }

    public static void setFullScreen(Activity activity) {
        Window window = activity.getWindow();
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(ContextCompat.getColor(activity, android.R.color.transparent));
        }
    }

}
