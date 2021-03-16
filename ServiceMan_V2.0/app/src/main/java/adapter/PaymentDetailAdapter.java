package adapter;

import android.content.Context;
import androidx.recyclerview.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.List;

import codecanyon.serviceman.CommonAppCompatActivity;
import codecanyon.serviceman.R;
import model.PaymentDetailModel;

/**
 * Created by Rajesh on 2018-01-13.
 */

public class PaymentDetailAdapter extends RecyclerView.Adapter<PaymentDetailAdapter.MyViewHolder> {

    private List<PaymentDetailModel> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView title, tv_qty, tv_price;

        public MyViewHolder(View view) {
            super(view);
            title = (TextView) view.findViewById(R.id.tv_paymentdetail_title);
            tv_qty = (TextView) view.findViewById(R.id.tv_paymentdetail_qty);
            tv_price = (TextView) view.findViewById(R.id.tv_paymentdetail_price);
        }

    }

    public PaymentDetailAdapter(List<PaymentDetailModel> modelList) {
        this.modelList = modelList;
    }

    @Override
    public PaymentDetailAdapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_paymentdetail, parent, false);

        context = parent.getContext();

        return new PaymentDetailAdapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(PaymentDetailAdapter.MyViewHolder holder, int position) {
        PaymentDetailModel mList = modelList.get(position);

        holder.title.setText(mList.getTitle());
        holder.tv_qty.setText(mList.getQty());
        holder.tv_price.setText(CommonAppCompatActivity.getPriceWithCurrency(context,mList.getCharge()));

    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }
}
