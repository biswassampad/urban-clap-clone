package adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.squareup.picasso.Picasso;

import java.util.List;

import Config.BaseURL;
import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.R;
import model.Service_detail_model;

/**
 * Created by Rajesh on 2017-09-29.
 */

public class My_service_detail_adapter extends RecyclerView.Adapter<My_service_detail_adapter.MyViewHolder> {

    private List<Service_detail_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView title, qty, price;
        private ImageView iv_img;

        public MyViewHolder(View view) {
            super(view);
            iv_img = (ImageView) view.findViewById(R.id.iv_service_detail_img);
            title = (TextView) view.findViewById(R.id.tv_service_detail_title);
            qty = (TextView) view.findViewById(R.id.tv_service_detail_qty);
            price = (TextView) view.findViewById(R.id.tv_service_detail_price);

        }

    }

    public My_service_detail_adapter(List<Service_detail_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public My_service_detail_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_my_service_detail, parent, false);

        context = parent.getContext();

        return new My_service_detail_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(My_service_detail_adapter.MyViewHolder holder, int position) {
        Service_detail_model mList = modelList.get(position);

        Picasso.with(context)
                .load(BaseURL.IMG_SERVICE_URL + mList.getService_icon())
                .placeholder(R.drawable.ic_loading)
                .error(R.drawable.ic_noimage)
                .into(holder.iv_img);

        holder.title.setText(mList.getService_title());
        holder.qty.setText(mList.getService_qty());
        holder.price.setText(CommonAppCompatActivity.getPriceWithCurrency(context, mList.getService_price()));
    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

}