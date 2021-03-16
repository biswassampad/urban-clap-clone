package adapter;

import android.content.Context;
import androidx.recyclerview.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RatingBar;
import android.widget.TextView;

import java.util.List;

import codecanyon.serviceman.CommonAppCompatActivity;
import codecanyon.serviceman.R;
import model.Assigned_model;

/**
 * Created by Rajesh on 2018-01-12.
 */

public class CompletedAdapter extends RecyclerView.Adapter<CompletedAdapter.MyViewHolder> {

    private List<Assigned_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView title, tv_status, tv_approx_time, tv_date, tv_price, tv_taken_time;
        public RatingBar ratingBar;

        public MyViewHolder(View view) {
            super(view);
            title = (TextView) view.findViewById(R.id.tv_appointment_title);
            tv_status = (TextView) view.findViewById(R.id.tv_appointment_status);
            tv_approx_time = (TextView) view.findViewById(R.id.tv_appointment_approx_time);
            tv_date = (TextView) view.findViewById(R.id.tv_appointment_date);
            tv_price = (TextView) view.findViewById(R.id.tv_appointment_price);
            tv_taken_time = (TextView) view.findViewById(R.id.tv_appointment_taken_time);
            ratingBar = (RatingBar) view.findViewById(R.id.rb_appointment);
        }

    }

    public CompletedAdapter(List<Assigned_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public CompletedAdapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_completed, parent, false);

        context = parent.getContext();

        return new CompletedAdapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(CompletedAdapter.MyViewHolder holder, int position) {
        Assigned_model mList = modelList.get(position);

        holder.title.setText(mList.getService_count());
        holder.tv_approx_time.setText(CommonAppCompatActivity.getTime12(mList.getStart_time()));
        holder.tv_date.setText(CommonAppCompatActivity.getConvertDate(mList.getAppointment_date()));
        holder.tv_taken_time.setText(CommonAppCompatActivity.getTime12(mList.getTotal_time()));
        holder.tv_price.setText(CommonAppCompatActivity.getPriceWithCurrency(context,mList.getPayment_amount()));

        if (mList.getStatus().equals("0")) {
            holder.tv_status.setText(context.getResources().getString(R.string.pending));
        } else if (mList.getStatus().equals("1")) {
            holder.tv_status.setText(context.getResources().getString(R.string.confirm));
        } else if (mList.getStatus().equals("2")) {
            holder.tv_status.setText(context.getResources().getString(R.string.start));
        } else if (mList.getStatus().equals("3")) {
            holder.tv_status.setText(context.getResources().getString(R.string.complete));
            holder.tv_status.setTextColor(context.getResources().getColor(R.color.color_cyan));
        }

    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

}
