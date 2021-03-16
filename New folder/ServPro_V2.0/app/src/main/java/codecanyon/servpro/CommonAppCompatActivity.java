package codecanyon.servpro;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

import util.DatabaseHandler;
import util.Session_management;

/**
 * Created by Rajesh on 2017-09-21.
 */

public class CommonAppCompatActivity extends AppCompatActivity {

    private TextView totalBudgetCount;
    private static DatabaseHandler dbcart;

    public CommonAppCompatActivity() {
    }

    @Override
    public boolean onCreateOptionsMenu(final Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getSupportActionBar().setHomeButtonEnabled(true);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setDisplayShowTitleEnabled(true);

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
        mActionBar.setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));

        final MenuItem cart = menu.findItem(R.id.action_cart);
        View count = cart.getActionView();
        count.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                menu.performIdentifierAction(cart.getItemId(), 0);
            }
        });

        totalBudgetCount = (TextView) count.findViewById(R.id.tv_action_cart);

        dbcart = new DatabaseHandler(this);

        updateCounter(this);

        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                //onBackPressed();
                this.finish();
                return true;
            case R.id.action_cart:
                if (!this.getClass().getSimpleName().equals("CartActivity")) {
                    if (dbcart.getCartCount() > 0) {
                        Intent cartIntent = new Intent(this, CartActivity.class);
                        startActivity(cartIntent);
                    } else {
                        showToast(this, getResources().getString(R.string.cart_empty));
                    }
                }
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

    // update actionbar cart items
    public void updateCounter(Activity context) {
        if (totalBudgetCount != null) {
            totalBudgetCount.setText("" + dbcart.getCartCount());
        } else {
            context.invalidateOptionsMenu();
        }
    }

    public static String getPriceWithCurrency(Context context, String price) {
        String currency = new Session_management.UserData().getSession(context, "currency");
        return price + " " + currency;
    }

    // this function return date in dd-MM-yyyy format
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

    public static String getConvertDateTime(String convertdate, int convertType) {
        try {
            String inputPattern = "yyyy-MM-dd HH:mm:ss";
            String outputPattern = "dd-MM-yyyy";
            String outputPattern2 = "dd-MM-yyyy hh:mm a";
            SimpleDateFormat inputFormat = new SimpleDateFormat(inputPattern, Locale.getDefault());
            SimpleDateFormat outputFormat = new SimpleDateFormat(outputPattern, Locale.getDefault());
            SimpleDateFormat outputFormat2 = new SimpleDateFormat(outputPattern2, Locale.getDefault());

            String str = "";

            Date date = inputFormat.parse(convertdate);
            switch (convertType) {
                case 1:
                    str = outputFormat.format(date);
                    break;
                case 2:
                    str = outputFormat2.format(date);
                    break;
            }

            return str;
        } catch (ParseException e) {
            e.printStackTrace();
            return "";
        }
    }

    // this function return 24 hour time to 12 hour time
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

    public static void setFullScreen(Activity activity) {
        Window window = activity.getWindow();
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(ContextCompat.getColor(activity, android.R.color.transparent));
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        updateCounter(this);
    }

}
