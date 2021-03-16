package adapter;

import android.content.Context;
import android.graphics.Paint;

import androidx.recyclerview.widget.RecyclerView;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.squareup.picasso.Picasso;

import java.util.List;

import Config.BaseURL;
import codecanyon.serviceman.CommonAppCompatActivity;
import codecanyon.serviceman.R;
import model.Service_detail_model;

/**
 * Created by Rajesh on 2018-01-20.
 */

public class ServiceDetailAdapter extends RecyclerView.Adapter<ServiceDetailAdapter.MyViewHolder> {

    private List<Service_detail_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView title, time, price, tv_qty, tv_orignal_price;
        private ImageView iv_img;

        public MyViewHolder(View view) {
            super(view);
            iv_img = (ImageView) view.findViewById(R.id.iv_service_img);
            title = (TextView) view.findViewById(R.id.tv_service_title);
            time = (TextView) view.findViewById(R.id.tv_service_time);
            price = (TextView) view.findViewById(R.id.tv_service_price);
            tv_qty = (TextView) view.findViewById(R.id.tv_service_qty);
            tv_orignal_price = (TextView) view.findViewById(R.id.tv_service_pricemain);

        }

    }

    public ServiceDetailAdapter(List<Service_detail_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public ServiceDetailAdapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_detail, parent, false);

        context = parent.getContext();

        return new ServiceDetailAdapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(ServiceDetailAdapter.MyViewHolder holder, int position) {
        Service_detail_model mList = modelList.get(position);

        Picasso.with(context)
                .load(BaseURL.IMG_SERVICE_DETAIL_URL + mList.getService_icon())
                .placeholder(R.mipmap.ic_launcher_round)
                /*
                .error(R.drawable.ic_noimage)*/
                .into(holder.iv_img);

        holder.title.setText(mList.getService_title());
        holder.tv_qty.setText(mList.getService_qty());

        String[] time = mList.getService_approxtime().split(":");
        String finalTime = "";
        if (!time[0].equals("00")) {
            finalTime += time[0] + "hr ";
        }
        if (!time[1].equals("00")) {
            finalTime += time[1] + "min ";
        }

        holder.time.setText(finalTime + ",");
        holder.price.setText(CommonAppCompatActivity.getPriceWithCurrency(context, mList.getService_price()));

        Double discount = Double.valueOf(mList.getService_discount());

        if (discount > 0) {
            holder.tv_orignal_price.setVisibility(View.VISIBLE);

            holder.price.setText(CommonAppCompatActivity.getPriceWithCurrency(context, getDiscountPrice(discount.toString(), mList.getService_price(), true)));
            holder.tv_orignal_price.setText(CommonAppCompatActivity.getPriceWithCurrency(context, mList.getService_price()));
            holder.tv_orignal_price.setPaintFlags(holder.tv_orignal_price.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);
        } else {
            holder.tv_orignal_price.setVisibility(View.GONE);
        }

    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

    private String getDiscountPrice(String discount, String price, boolean getEffectedprice) {
        Double discount1 = Double.parseDouble(discount);
        Double price1 = Double.parseDouble(price);
        Double discount_amount = discount1 * price1 / 100;

        if (getEffectedprice) {
            Double effected_price = price1 - discount_amount;
            return effected_price.toString();
        } else {
            return discount_amount.toString();
        }
    }

}