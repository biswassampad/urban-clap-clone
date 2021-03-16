package codecanyon.servpro;

import android.content.BroadcastReceiver;
import android.content.ClipData;
import android.content.ClipboardManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.os.Handler;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.TextView;

import androidx.appcompat.app.AlertDialog;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.android.material.textfield.TextInputLayout;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;

import Config.BaseURL;
import adapter.Cart_adapter;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.DatabaseHandler;
import util.NameValuePair;
import util.Session_management;

import static android.content.ClipDescription.MIMETYPE_TEXT_PLAIN;

public class CartActivity extends CommonAppCompatActivity implements View.OnClickListener {

    private static String TAG = CartActivity.class.getSimpleName();

    private EditText et_voucher;
    private TextInputLayout ti_voucher;
    private TextView tv_total_time, tv_total_price, tv_continue, tv_clear, tv_total_items, tv_check_voucher, tv_view_offer;
    private RecyclerView rv_cart;

    private DatabaseHandler dbcart;
    private Session_management sessionManagement;

    private String offer_coupon, offer_discount;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cart);

        sessionManagement = new Session_management(CartActivity.this);
        dbcart = new DatabaseHandler(this);

        ClipboardManager clipboard = (ClipboardManager) getSystemService(CLIPBOARD_SERVICE);
        ClipData clip = ClipData.newPlainText("servproCode", "");
        clipboard.setPrimaryClip(clip);

        et_voucher = (EditText) findViewById(R.id.et_cart_voucher);
        ti_voucher = (TextInputLayout) findViewById(R.id.ti_cart_voucher);
        tv_check_voucher = (TextView) findViewById(R.id.tv_cart_check_voucher);
        tv_view_offer = (TextView) findViewById(R.id.tv_cart_view_offer);
        tv_total_items = (TextView) findViewById(R.id.tv_cart_items);
        tv_clear = (TextView) findViewById(R.id.tv_cart_clear);
        tv_total_price = (TextView) findViewById(R.id.tv_total_price);
        tv_total_time = (TextView) findViewById(R.id.tv_total_time);
        tv_continue = (TextView) findViewById(R.id.tv_book);
        rv_cart = (RecyclerView) findViewById(R.id.rv_cart);
        rv_cart.setLayoutManager(new LinearLayoutManager(this));

        tv_continue.setText(getResources().getString(R.string.Continue));

        // get all cart data from database and store in map list
        ArrayList<HashMap<String, String>> map = dbcart.getCartAll();

        Cart_adapter adapter = new Cart_adapter(this, map);
        rv_cart.setAdapter(adapter);
        adapter.notifyDataSetChanged();

        updatePrice();

        tv_clear.setOnClickListener(this);
        tv_check_voucher.setOnClickListener(this);
        tv_view_offer.setOnClickListener(this);
        tv_continue.setOnClickListener(this);

    }

    @Override
    public void onClick(View view) {

        // Check if no view has focus:
        View view2 = this.getCurrentFocus();
        if (view2 != null) {
            // hide keyboard on view
            InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
            imm.hideSoftInputFromWindow(view2.getWindowToken(), 0);
        }

        switch (view.getId()) {
            case R.id.tv_cart_check_voucher:
                ti_voucher.setError(null);

                String getcode = et_voucher.getText().toString();

                if (!TextUtils.isEmpty(getcode)) {

                    // check internet connection is available or not
                    if (ConnectivityReceiver.isConnected()) {
                        if (sessionManagement.isLoggedIn()) {
                            String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                            makeCheckOffer(getcode, user_id);
                        } else {
                            Intent loginIntent = new Intent(CartActivity.this, LoginActivity.class);
                            loginIntent.putExtra("setfinish", "true");
                            startActivity(loginIntent);
                        }
                    } else {
                        // show snackbar in activity
                        ConnectivityReceiver.showSnackbar(CartActivity.this);
                    }
                } else {
                    ti_voucher.setErrorTextAppearance(R.style.error_appearance_red);
                    ti_voucher.setError(getResources().getString(R.string.please_enter_valid_voucher_code));
                }

                break;
            case R.id.tv_cart_clear:
                showClearCartDialog();
                break;
            case R.id.tv_cart_view_offer:
                Intent viewIntent = new Intent(CartActivity.this, OffersActivity.class);
                startActivity(viewIntent);
                break;
            case R.id.tv_book:
                Intent continueIntent;
                // check user is login or not
                if (sessionManagement.isLoggedIn()) {
                    continueIntent = new Intent(CartActivity.this, Service_detailActivity.class);
                    continueIntent.putExtra("total_price", dbcart.getTotalDiscountAmount().toString());
                    continueIntent.putExtra("total_time", tv_total_time.getText().toString());
                    continueIntent.putExtra("promo_code", offer_coupon);
                    continueIntent.putExtra("offer_discount", offer_discount);

                } else {
                    continueIntent = new Intent(CartActivity.this, LoginActivity.class);
                    continueIntent.putExtra("setfinish", "true");
                }
                startActivity(continueIntent);
                break;
        }
    }

    // update price, time, total items in cart when listing data change
    private void updatePrice() {

        // check local database has at list 1 record or not
        if (dbcart.getCartCount() > 0) {
            // get total discount price of all available items and set in textview
            tv_total_price.setText(CommonAppCompatActivity.getPriceWithCurrency(this, dbcart.getTotalDiscountAmount()));
            tv_total_items.setText(String.valueOf(dbcart.getCartCount()));

            String[] totaltime = dbcart.getTotalTime(true).split(":");

            String finalTime = "";
            if (!totaltime[0].equals("0") && !totaltime[0].equals("00")) {
                finalTime += totaltime[0] + "hr ";
            }
            if (!totaltime[1].equals("0") && !totaltime[1].equals("00")) {
                finalTime += totaltime[1] + "min ";
            }

            tv_total_time.setText(finalTime);

            // update actionbar cart icon
            CommonAppCompatActivity compatActivity = new CommonAppCompatActivity();
            compatActivity.updateCounter(this);
        } else {
            finish();
        }
    }

    // display alertdialog with custom view
    private void showClearCartDialog() {
        AlertDialog.Builder alert = new AlertDialog.Builder(this);
        alert.setTitle(getResources().getString(R.string.are_you_sure));
        alert.setPositiveButton(getResources().getString(R.string.yes), new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                dbcart.clearCart();
                finish();
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

    // check offer request for check offer is valid or not
    private void makeCheckOffer(String offer_coupons, String user_id) {

        // adding post values in arraylist
        ArrayList<NameValuePair> params = new ArrayList<>();
        params.add(new NameValuePair("offer_coupon", offer_coupons));
        params.add(new NameValuePair("user_id", user_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.CHECK_OFFER_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                try {
                    JSONObject jsonObject = new JSONObject(response);

                    String offer_id = jsonObject.getString("offer_id");
                    String offer_title = jsonObject.getString("offer_title");
                    offer_coupon = jsonObject.getString("offer_coupon");
                    offer_discount = jsonObject.getString("offer_discount");

                    ti_voucher.setErrorTextAppearance(R.style.error_appearance_primery);
                    ti_voucher.setError(offer_title + ". " + offer_discount + "% Discount available");

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                ti_voucher.setErrorTextAppearance(R.style.error_appearance_red);
                ti_voucher.setError(responce);
            }
        }, true, this);
        task.execute();
    }

    @Override
    public void onPause() {
        super.onPause();
        // unregister reciver
        this.unregisterReceiver(mUpdatePrice);
    }

    @Override
    public void onResume() {
        super.onResume();
        // register reciver
        this.registerReceiver(mUpdatePrice, new IntentFilter("ServPro_price"));
    }

    private String getCopyData() {

        ClipboardManager clipboard = (ClipboardManager) getSystemService(CLIPBOARD_SERVICE);
        if (clipboard != null && clipboard.hasPrimaryClip()) {
            ClipData.Item item = clipboard.getPrimaryClip().getItemAt(0);
            CharSequence pastData = item.getText();

            if (pastData != null) {
                Log.d(TAG, "has text");
                return pastData.toString();
            } else {
                Log.d(TAG, "not text found");
                return "";
            }
        } else {
            Log.d(TAG, "clipboard null");
            return "";
        }
    }

    @Override
    public void onWindowFocusChanged(boolean hasFocus) {
        super.onWindowFocusChanged(hasFocus);
        if (hasFocus) {
            String pasteData = getCopyData();

            if (!pasteData.equals("")) {
                et_voucher.setText(pasteData);
                ClipboardManager clipboard = (ClipboardManager) getSystemService(CLIPBOARD_SERVICE);
                ClipData clip = ClipData.newPlainText("servproCode", "");
                clipboard.setPrimaryClip(clip);
                // check internet connection is available or not
                if (ConnectivityReceiver.isConnected()) {
                    if (sessionManagement.isLoggedIn()) {
                        String user_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                        makeCheckOffer(pasteData, user_id);
                    } else {
                        Intent loginIntent = new Intent(CartActivity.this, LoginActivity.class);
                        loginIntent.putExtra("setfinish", "true");
                        startActivity(loginIntent);
                    }
                } else {
                    // show snackbar in activity
                    ConnectivityReceiver.showSnackbar(CartActivity.this);
                }
            }
        }
    }

    // broadcast reciver for receive data
    private BroadcastReceiver mUpdatePrice = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {

            String type = intent.getStringExtra("type");

            if (type.contentEquals("update_price")) {
                updatePrice();
            }
        }
    };

}
