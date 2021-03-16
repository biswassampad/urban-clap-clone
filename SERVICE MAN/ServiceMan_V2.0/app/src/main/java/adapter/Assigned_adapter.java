package adapter;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Build;

import androidx.appcompat.app.AlertDialog;
import androidx.recyclerview.widget.RecyclerView;

import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.util.Log;
import android.util.TypedValue;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import codecanyon.serviceman.CommonAppCompatActivity;
import codecanyon.serviceman.PaymentDetailActivity;
import codecanyon.serviceman.R;
import codecanyon.serviceman.Service_detailActivity;
import model.Assigned_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.GPSTracker;
import util.NameValuePair;
import util.Session_management;

/**
 * Created by Rajesh on 2017-11-02.
 */

public class Assigned_adapter extends RecyclerView.Adapter<Assigned_adapter.MyViewHolder> {

    private List<Assigned_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        public TextView title, tv_status, tv_approx_time, tv_total_time, tv_date, tv_start, tv_address, tv_time_status;
        public LinearLayout ll_time, ll_address;

        public MyViewHolder(View view) {
            super(view);
            title = (TextView) view.findViewById(R.id.tv_appointment_title);
            //tv_status = (TextView) view.findViewById(R.id.tv_appointment_status);
            tv_approx_time = (TextView) view.findViewById(R.id.tv_appointment_approx_time);
            tv_date = (TextView) view.findViewById(R.id.tv_appointment_date);
            tv_total_time = (TextView) view.findViewById(R.id.tv_appointment_total_time);
            tv_start = (TextView) view.findViewById(R.id.tv_assigned_start);
            tv_address = (TextView) view.findViewById(R.id.tv_appointment_address);
            tv_time_status = (TextView) view.findViewById(R.id.tv_appointment_time_title);
            ll_time = (LinearLayout) view.findViewById(R.id.ll_appointment_time);
            ll_address = (LinearLayout) view.findViewById(R.id.ll_appointment_address);

            view.findViewById(R.id.card_view).setOnClickListener(this);
            tv_start.setOnClickListener(this);

        }

        @Override
        public void onClick(View view) {
            int id = view.getId();

            int position = getAdapterPosition();

            if (id == R.id.card_view) {
                Intent detailIntent = new Intent(context, Service_detailActivity.class);
                detailIntent.putExtra("appointment_id", modelList.get(getAdapterPosition()).getId());
                context.startActivity(detailIntent);
            } else if (id == R.id.tv_assigned_start) {

                if (modelList.get(position).getStatus().equals("1")) {
                    if (ConnectivityReceiver.isConnected()) {
                        if (tv_start.getText().toString().equals(context.getResources().getString(R.string.start))) {

                            Session_management sessionManagement = new Session_management(context);
                            String userid = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);

                            Double cur_latitude = 0.0, cur_longitude = 0.0;

                            GPSTracker gpsTracker = new GPSTracker(context);
                            if (gpsTracker.canGetLocation()) {
                                if (gpsTracker.getLatitude() != 0.0)
                                    cur_latitude = gpsTracker.getLatitude();
                                if (gpsTracker.getLongitude() != 0.0)
                                    cur_longitude = gpsTracker.getLongitude();

                            } /*else {
                                    gpsTracker.showSettingsAlert();
                                }*/

                            makeStartService(modelList.get(position).getId(), userid, cur_latitude.toString(), cur_longitude.toString(), position);
                        }
                    } else {
                        ConnectivityReceiver.showSnackbar(context);
                    }
                } else if (modelList.get(position).getStatus().equals("2")) {

                    CommonAppCompatActivity.setPaymentDone(context, false);

                    showServiceDone(modelList.get(position).getId(), position, modelList.get(position).getStart_time(), modelList.get(position).getNet_amount());
                }

            }
        }
    }

    public Assigned_adapter(List<Assigned_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public Assigned_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_assigned, parent, false);

        context = parent.getContext();

        return new Assigned_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(Assigned_adapter.MyViewHolder holder, int position) {
        Assigned_model mList = modelList.get(position);

        holder.title.setText(mList.getService_count());
        holder.tv_date.setText(CommonAppCompatActivity.getConvertDate(mList.getAppointment_date()));
        holder.tv_total_time.setText(context.getResources().getString(R.string.running_time) + CommonAppCompatActivity.getTimeDiffrence(mList.getStart_time()));

        if (mList.getStatus().equals("0")) {
            holder.ll_time.setVisibility(View.GONE);
            holder.tv_start.setText(context.getResources().getString(R.string.start));
            holder.ll_time.setVisibility(View.GONE);
            holder.ll_address.setVisibility(View.VISIBLE);
            holder.tv_address.setText(mList.getDelivery_address() + ", " + mList.getDelivery_landmark() + ", " + mList.getDelivery_city() + ", " + mList.getDelivery_zipcode());
            holder.tv_start.setBackgroundResource(R.drawable.xml_rounded_button_gray);
            holder.tv_time_status.setText(context.getResources().getString(R.string.visit_time));
            if (mList.getVisit_at().equals("00:00:00")) {
                holder.tv_approx_time.setText(CommonAppCompatActivity.getTime12(mList.getStart_time()));
            } else {
                holder.tv_approx_time.setText(CommonAppCompatActivity.getTime12(mList.getVisit_at()));
            }
        } else if (mList.getStatus().equals("1")) {
            holder.ll_time.setVisibility(View.GONE);
            holder.tv_start.setText(context.getResources().getString(R.string.start));
            holder.ll_time.setVisibility(View.GONE);
            holder.ll_address.setVisibility(View.VISIBLE);
            holder.tv_address.setText(mList.getDelivery_address() + ", " + mList.getDelivery_landmark() + ", " + mList.getDelivery_city() + ", " + mList.getDelivery_zipcode());
            holder.tv_start.setBackgroundResource(R.drawable.xml_rounded_button_gray);
            holder.tv_time_status.setText(context.getResources().getString(R.string.visit_time));
            if (mList.getVisit_at().equals("00:00:00")) {
                holder.tv_approx_time.setText(CommonAppCompatActivity.getTime12(mList.getStart_time()));
            } else {
                holder.tv_approx_time.setText(CommonAppCompatActivity.getTime12(mList.getVisit_at()));
            }
        } else if (mList.getStatus().equals("2")) {
            holder.ll_time.setVisibility(View.VISIBLE);
            holder.tv_start.setText(context.getResources().getString(R.string.complete));
            holder.ll_time.setVisibility(View.VISIBLE);
            holder.ll_address.setVisibility(View.GONE);
            holder.tv_start.setBackgroundResource(R.drawable.xml_rounded_button_orenge);
            holder.tv_time_status.setText(context.getResources().getString(R.string.start_time));
            holder.tv_approx_time.setText(CommonAppCompatActivity.getTime12(mList.getStart_time()));
        } else if (mList.getStatus().equals("3")) {
            holder.ll_time.setVisibility(View.VISIBLE);
            holder.tv_start.setText(context.getResources().getString(R.string.complete));
            holder.ll_time.setVisibility(View.VISIBLE);
            holder.ll_address.setVisibility(View.GONE);
            holder.tv_start.setBackgroundResource(R.drawable.xml_rounded_button_orenge);
            holder.tv_time_status.setText(context.getResources().getString(R.string.start_time));
            holder.tv_approx_time.setText(CommonAppCompatActivity.getTime12(mList.getStart_time()));
        }

    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

    private void makeStartService(String appointment_id, String user_id, String lat, String lon, final int position) {

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
                Log.e(context.toString(), response);

                modelList.get(position).setStatus("2");
                notifyItemChanged(position);

                // display toast message
                CommonAppCompatActivity.showToast(context, response);
            }

            @Override
            public void VError(String responce) {
                Log.e(context.toString(), responce);
                // display toast message
                CommonAppCompatActivity.showToast(context, responce);
            }
        }, true, context);
        task.execute();
    }

    private int count = 0;
    private boolean cancle = true;
    private AlertDialog alertDialog;
    private int finalExtraTotal = 0;

    // show image choosing dialog
    private void showServiceDone(final String appointment_id, final int position, String total_time, final String service_amount) {

        count = 0;
        cancle = true;

        AlertDialog.Builder dialogBuilder = new AlertDialog.Builder(context);

        // ...Irrelevant code for customizing the buttons and title
        LayoutInflater inflater = ((Activity) context).getLayoutInflater();
        final View dialogView = inflater.inflate(R.layout.dialog_service_done, null);
        dialogBuilder.setView(dialogView);

        ImageView fab_add = dialogView.findViewById(R.id.fab);
        final TextView tv_total_amount = dialogView.findViewById(R.id.tv_total_amount);
        TextView tv_paid = dialogView.findViewById(R.id.tv_service_paid);
        TextView tv_amount = dialogView.findViewById(R.id.tv_service_amount);
        final TextView tv_total_time = dialogView.findViewById(R.id.tv_service_total_time);
        final LinearLayout lm = dialogView.findViewById(R.id.ll_additional_charge);

        tv_total_time.setText(CommonAppCompatActivity.getTimeDiffrence(total_time));
        tv_amount.setText(CommonAppCompatActivity.getPriceWithCurrency(context, service_amount));

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
                LinearLayout linearLayout1 = new LinearLayout(context);
                linearLayout1.setLayoutParams(layoutParams);
                linearLayout1.setGravity(Gravity.CENTER);
                linearLayout1.setOrientation(LinearLayout.HORIZONTAL);

                final EditText et_name = new EditText(context);
                et_name.setLayoutParams(layoutParams_name);
                et_name.setTextColor(context.getResources().getColor(R.color.black));
                et_name.setTextSize(TypedValue.COMPLEX_UNIT_SP, 12F);
                et_name.setPadding(10, 10, 10, 10);
                et_name.setHint(context.getResources().getString(R.string.service));
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN) {
                    et_name.setBackground(context.getResources().getDrawable(R.drawable.xml_editext_border));
                }

                linearLayout1.addView(et_name);

                EditText et_price = new EditText(context);
                et_price.setLayoutParams(layoutParams_price);
                et_price.setTextColor(context.getResources().getColor(R.color.black));
                et_price.setTextSize(TypedValue.COMPLEX_UNIT_SP, 12F);
                et_price.setInputType(InputType.TYPE_CLASS_NUMBER);
                et_price.setPadding(10, 10, 10, 10);
                et_price.setHint(context.getResources().getString(R.string.price));
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN) {
                    et_price.setBackground(context.getResources().getDrawable(R.drawable.xml_editext_border));
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
                        tv_total_amount.setText(context.getResources().getString(R.string.total_amount) + CommonAppCompatActivity.getPriceWithCurrency(context, String.format("%.1f", (Double.valueOf(totalamount) + Double.valueOf(service_amount)))));
                    }

                    @Override
                    public void afterTextChanged(Editable editable) {

                    }
                });

                linearLayout1.addView(et_price);


                final EditText et_qty = new EditText(context);
                et_qty.setLayoutParams(layoutParams_qty);
                et_qty.setTextColor(context.getResources().getColor(R.color.black));
                et_qty.setTextSize(TypedValue.COMPLEX_UNIT_SP, 12F);
                et_qty.setInputType(InputType.TYPE_CLASS_NUMBER);
                et_qty.setPadding(10, 10, 10, 10);
                et_qty.setHint(context.getResources().getString(R.string.qty));
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN) {
                    et_qty.setBackground(context.getResources().getDrawable(R.drawable.xml_editext_border));
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
                        tv_total_amount.setText(context.getResources().getString(R.string.total_amount) + CommonAppCompatActivity.getPriceWithCurrency(context, String.format("%.1f", (Double.valueOf(totalamount) + Double.valueOf(service_amount)))));
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
                                etPriceArray.get(i).setError(context.getResources().getString(R.string.error_field_required));
                                cancle = true;
                            }

                            if (etTextArray.get(i).getText().toString().isEmpty()) {
                                etTextArray.get(i).setError(context.getResources().getString(R.string.error_field_required));
                                cancle = true;
                            }

                            if (etqtyArray.get(i).getText().toString().isEmpty()) {
                                etqtyArray.get(i).setError(context.getResources().getString(R.string.error_field_required));
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

                        Intent paymentdetailIntent = new Intent(context, PaymentDetailActivity.class);
                        paymentdetailIntent.putExtra("list", passArray.toString());
                        paymentdetailIntent.putExtra("appointment_id", appointment_id);
                        paymentdetailIntent.putExtra("service_amount", service_amount);
                        paymentdetailIntent.putExtra("total_amount", String.valueOf(finalExtraTotal));
                        paymentdetailIntent.putExtra("total_time", tv_total_time.getText().toString());
                        context.startActivity(paymentdetailIntent);

                    }
                } else {
                    Intent paymentdetailIntent = new Intent(context, PaymentDetailActivity.class);
                    paymentdetailIntent.putExtra("list", "[]");
                    paymentdetailIntent.putExtra("appointment_id", appointment_id);
                    paymentdetailIntent.putExtra("service_amount", service_amount);
                    paymentdetailIntent.putExtra("total_amount", "00");
                    paymentdetailIntent.putExtra("total_time", total_time);
                    context.startActivity(paymentdetailIntent);
                }
            }
        });

    }

    public void dissmisDialog() {
        if (alertDialog != null && alertDialog.isShowing()) {
            alertDialog.dismiss();
        }
    }

}
