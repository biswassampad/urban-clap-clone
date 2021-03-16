package adapter;

import android.content.ClipData;
import android.content.ClipboardManager;
import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.Offers_detailActivity;
import codecanyon.servpro.R;
import model.Offers_list_model;

import static android.content.Context.CLIPBOARD_SERVICE;

/**
 * Created by Rajesh on 2017-09-26.
 */

public class Offers_adapter extends RecyclerView.Adapter<Offers_adapter.MyViewHolder> {

    private List<Offers_list_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        public TextView title, date, code;
        private ImageView iv_copy;

        public MyViewHolder(View view) {
            super(view);
            title = (TextView) view.findViewById(R.id.tv_offer_title);
            date = (TextView) view.findViewById(R.id.tv_offer_date_from);
            code = (TextView) view.findViewById(R.id.tv_offer_code);
            iv_copy = (ImageView) view.findViewById(R.id.iv_offer_copy_code);

            iv_copy.setOnClickListener(this);
            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            int id = view.getId();

            if (id == R.id.iv_offer_copy_code) {
                //place your TextView's text in clipboard
                ClipboardManager clipboard = (ClipboardManager) context.getSystemService(CLIPBOARD_SERVICE);
                ClipData clip = ClipData.newPlainText("servproCode", code.getText().toString());
                clipboard.setPrimaryClip(clip);

                CommonAppCompatActivity.showToast(context, context.getResources().getString(R.string.text_copied));

            } else {
                Offers_list_model model = modelList.get(getAdapterPosition());

                ClipboardManager clipboard = (ClipboardManager) context.getSystemService(CLIPBOARD_SERVICE);
                ClipData clip = ClipData.newPlainText("servproCode", code.getText().toString());
                clipboard.setPrimaryClip(clip);

                Intent detailIntent = new Intent(context, Offers_detailActivity.class);
                detailIntent.putExtra("title", model.getOffer_title());
                detailIntent.putExtra("coupon", model.getOffer_coupon());
                detailIntent.putExtra("start_date", model.getOffer_start_date());
                detailIntent.putExtra("end_date", model.getOffer_end_date());
                detailIntent.putExtra("description", model.getOffer_description());
                context.startActivity(detailIntent);
            }

        }
    }

    public Offers_adapter(List<Offers_list_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public Offers_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_offers, parent, false);

        context = parent.getContext();

        return new Offers_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(Offers_adapter.MyViewHolder holder, int position) {
        Offers_list_model mList = modelList.get(position);

        holder.title.setText(mList.getOffer_title());
        holder.date.setText(CommonAppCompatActivity.getConvertDateTime(mList.getOffer_start_date(), 2) + context.getResources().getString(R.string.to) + CommonAppCompatActivity.getConvertDateTime(mList.getOffer_end_date(), 2));
        holder.code.setText(mList.getOffer_coupon());
    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

}