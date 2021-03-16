package adapter;

import android.app.Activity;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RatingBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.RecyclerView;

import com.google.android.material.textfield.TextInputLayout;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import Config.BaseURL;
import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.My_service_detailActivity;
import codecanyon.servpro.R;
import model.Appointment_list_model;
import util.CommonAsyTask;
import util.NameValuePair;
import util.Session_management;

import static android.content.Context.LAYOUT_INFLATER_SERVICE;

/**
 * Created by Rajesh on 2017-09-28.
 */

public class My_appointment_adapter extends RecyclerView.Adapter<My_appointment_adapter.MyViewHolder> {

    private List<Appointment_list_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        public TextView title, tv_status, tv_visit_time, tv_price, tv_token_time,
                tv_add_review, tv_name, tv_mobile, tv_date, tv_time_status;
        public ImageView image;
        public RatingBar rb_review;
        public LinearLayout ll_visit_time, ll_amount, ll_time_token, ll_user;
        public CardView cardView;

        public MyViewHolder(View view) {
            super(view);
            image = (ImageView) view.findViewById(R.id.iv_appointment);
            title = (TextView) view.findViewById(R.id.tv_appointment_title);
            tv_status = (TextView) view.findViewById(R.id.tv_appointment_status);
            tv_visit_time = (TextView) view.findViewById(R.id.tv_appointment_visit_time);
            tv_price = (TextView) view.findViewById(R.id.tv_appointment_price);
            tv_token_time = (TextView) view.findViewById(R.id.tv_appointment_taken_time);
            tv_add_review = (TextView) view.findViewById(R.id.tv_appointment_add_review);
            tv_mobile = (TextView) view.findViewById(R.id.tv_appointment_mobile);
            tv_name = (TextView) view.findViewById(R.id.tv_appointment_name);
            tv_date = (TextView) view.findViewById(R.id.tv_appointment_date);
            tv_time_status = (TextView) view.findViewById(R.id.tv_appointment_time_status);
            rb_review = (RatingBar) view.findViewById(R.id.rb_appointment);
            ll_visit_time = (LinearLayout) view.findViewById(R.id.ll_appointment_visit_time);
            ll_amount = (LinearLayout) view.findViewById(R.id.ll_appointment_amount);
            ll_time_token = (LinearLayout) view.findViewById(R.id.ll_appointment_taken_time);
            ll_user = (LinearLayout) view.findViewById(R.id.ll_appointment_user);
            cardView = (CardView) view.findViewById(R.id.card_view);

            tv_add_review.setOnClickListener(this);
            cardView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            int position = getAdapterPosition();
            Appointment_list_model model = modelList.get(position);

            switch (view.getId()) {
                case R.id.tv_appointment_add_review:
                    showDialog(model.getId(), position, model);
                    break;
                default:
                    Intent detailIntent = new Intent(context, My_service_detailActivity.class);
                    detailIntent.putExtra("appointment_id", model.getId());
                    context.startActivity(detailIntent);
                    break;
            }
        }
    }

    public My_appointment_adapter(List<Appointment_list_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public My_appointment_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_appointment, parent, false);

        context = parent.getContext();

        return new My_appointment_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(My_appointment_adapter.MyViewHolder holder, int position) {
        Appointment_list_model mList = modelList.get(position);

        /*Picasso.with(context)
                .load(BaseURL.IMG_PROFILE_URL + mList.get())
                .placeholder(R.mipmap.ic_launcher)
                .into(holder.image);*/

        holder.title.setText(mList.getId());
        holder.tv_status.setText(mList.getStatus());
        holder.tv_visit_time.setText(CommonAppCompatActivity.getTime12(mList.getStart_time()));
        holder.tv_price.setText(CommonAppCompatActivity.getPriceWithCurrency(context, mList.getNet_amount()));
        holder.tv_token_time.setText(mList.getTime_token() + context.getResources().getString(R.string.hour));
        holder.tv_date.setText(CommonAppCompatActivity.getConvertDate(mList.getAppointment_date()));

        holder.tv_name.setText(mList.getPros_name());
        holder.tv_mobile.setText(mList.getUser_phone());

        if (mList.getStatus().equals("0")) {
            holder.tv_status.setText(context.getResources().getString(R.string.pending));

            holder.ll_user.setVisibility(View.GONE);
            holder.ll_time_token.setVisibility(View.GONE);
            holder.ll_amount.setVisibility(View.GONE);
            holder.ll_visit_time.setVisibility(View.GONE);
            holder.rb_review.setVisibility(View.GONE);
            holder.tv_add_review.setVisibility(View.GONE);

            String[] totaltime = mList.getTotal_time().split(":");

            String finalTime = "";
            if (!totaltime[0].equals("00")) {
                finalTime += totaltime[0] + "hr ";
            }
            if (!totaltime[1].equals("00")) {
                finalTime += totaltime[1] + "min ";
            }

            holder.tv_time_status.setText(context.getResources().getString(R.string.approx_time) + finalTime);
        } else if (mList.getStatus().equals("1")) {
            holder.tv_status.setText(context.getResources().getString(R.string.confirm));
            holder.tv_status.setTextColor(context.getResources().getColor(R.color.color_orenge_dark));

            holder.ll_user.setVisibility(View.VISIBLE);
            holder.ll_time_token.setVisibility(View.GONE);
            holder.ll_amount.setVisibility(View.GONE);
            holder.ll_visit_time.setVisibility(View.VISIBLE);
            holder.rb_review.setVisibility(View.GONE);
            holder.tv_add_review.setVisibility(View.GONE);

            String[] totaltime = mList.getTotal_time().split(":");

            String finalTime = "";
            if (!totaltime[0].equals("00")) {
                finalTime += totaltime[0] + "hr ";
            }
            if (!totaltime[1].equals("00")) {
                finalTime += totaltime[1] + "min ";
            }

            holder.tv_time_status.setText(context.getResources().getString(R.string.approx_time) + finalTime);
        } else if (mList.getStatus().equals("2")) {
            holder.tv_status.setText(context.getResources().getString(R.string.started));
            holder.tv_status.setTextColor(context.getResources().getColor(R.color.color_green));

            holder.ll_user.setVisibility(View.GONE);
            holder.ll_time_token.setVisibility(View.VISIBLE);
            holder.ll_amount.setVisibility(View.VISIBLE);
            holder.ll_visit_time.setVisibility(View.GONE);
            holder.rb_review.setVisibility(View.GONE);
            holder.tv_add_review.setVisibility(View.GONE);

            holder.tv_time_status.setText(context.getResources().getString(R.string.visit_time) + CommonAppCompatActivity.getTime12(mList.getVisit_at()));
        } else if (mList.getStatus().equals("3")) {
            holder.tv_status.setText(context.getResources().getString(R.string.completed));
            holder.tv_status.setTextColor(context.getResources().getColor(R.color.color_green));
            if (mList.getAvg_rating().equals("0")) {
                holder.tv_add_review.setVisibility(View.VISIBLE);
                holder.rb_review.setVisibility(View.GONE);
            } else {
                holder.tv_add_review.setVisibility(View.GONE);
                holder.rb_review.setRating(Float.valueOf(mList.getAvg_rating()));
                holder.rb_review.setVisibility(View.VISIBLE);
            }
            holder.ll_visit_time.setVisibility(View.GONE);
            holder.ll_user.setVisibility(View.GONE);

            holder.tv_time_status.setText(context.getResources().getString(R.string.visit_time) + CommonAppCompatActivity.getTime12(mList.getVisit_at()));
        } else if (mList.getStatus().equals("4")) {
            holder.tv_status.setText(context.getResources().getString(R.string.canceled));
            holder.tv_status.setTextColor(context.getResources().getColor(R.color.red));
            holder.ll_user.setVisibility(View.GONE);
            holder.ll_time_token.setVisibility(View.GONE);
            holder.ll_amount.setVisibility(View.GONE);
            holder.ll_visit_time.setVisibility(View.GONE);
            holder.rb_review.setVisibility(View.GONE);
            holder.tv_add_review.setVisibility(View.GONE);

            holder.tv_time_status.setText(context.getResources().getString(R.string.approx_time) + mList.getTime_token() + context.getResources().getString(R.string.hour));
        }

    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

    // show review dialog on "add review" button click
    private void showDialog(final String appointment_id, int position, Appointment_list_model appointment_list_model) {
        final RatingBar ratingBar;
        final EditText et_comment;
        final TextInputLayout ti_comment;

        final AlertDialog.Builder alertDialog = new AlertDialog.Builder(context);
        LayoutInflater inflater = (LayoutInflater) context.getSystemService(LAYOUT_INFLATER_SERVICE);

        View layout = inflater.inflate(R.layout.dialog_add_review,
                (ViewGroup) ((Activity) context).findViewById(R.id.ll_review_dialog));
        et_comment = (EditText) layout.findViewById(R.id.et_review_dialog_comment);
        ratingBar = (RatingBar) layout.findViewById(R.id.rb_review_dialog);
        ti_comment = (TextInputLayout) layout.findViewById(R.id.ti_review_dialog_comment);

        final AlertDialog dialog;

        alertDialog.setView(layout);

        alertDialog.setPositiveButton(context.getResources().getString(R.string.add_review), null);

        alertDialog.setNegativeButton(context.getResources().getString(R.string.cancel), new DialogInterface.OnClickListener() {
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
                            Session_management sessionManagement = new Session_management(context);
                            String getuser_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
                            String getrating = String.valueOf(Math.round(ratingBar.getRating()));

                            dialog.dismiss();

                            makeAddReview(getuser_id, getcomment, getrating, appointment_id, position, appointment_list_model);
                        } else {
                            ti_comment.setError(context.getResources().getString(R.string.error_field_required));
                        }
                    }
                });
            }
        });

        dialog.show();
    }

    // this function for add review on appointment
    private void makeAddReview(String user_id, String reviews, String ratings, String appointment_id,
                               int position, Appointment_list_model appointment_list_model) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("user_id", user_id));
        params.add(new NameValuePair("reviews", reviews));
        params.add(new NameValuePair("ratings", ratings));
        params.add(new NameValuePair("appointment_id", appointment_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.ADD_REVIEW_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(context.toString(), response);

                try {
                    JSONObject jsonObject = new JSONObject(response);
                    String reviews = jsonObject.getString("reviews");
                    String ratings = jsonObject.getString("ratings");

                    appointment_list_model.setAvg_rating(ratings);
                    appointment_list_model.setReview_count(ratings);

                    modelList.set(position, appointment_list_model);
                    notifyItemChanged(position);

                } catch (JSONException e) {
                    e.printStackTrace();
                }

                //CommonAppCompatActivity.showToast(context, response);
            }

            @Override
            public void VError(String responce) {
                Log.e(context.toString(), responce);
                CommonAppCompatActivity.showToast(context, responce);
            }
        }, true, context);
        task.execute();
    }

}