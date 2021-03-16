package adapter;

import android.content.Context;
import android.content.Intent;
import android.graphics.Paint;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.squareup.picasso.Picasso;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;

import Config.BaseURL;
import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.R;
import model.Service_list_model;
import util.DatabaseHandler;

/**
 * Created by Rajesh on 2017-09-26.
 */

public class Service_list_adapter extends RecyclerView.Adapter<Service_list_adapter.MyViewHolder> {

    private List<Service_list_model> modelList;
    private Context context;
    private DatabaseHandler dbcart;

    public class MyViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        public TextView title, time, price, tv_qty, tv_orignal_price;
        public ImageView image, iv_plus, iv_minus, iv_cancel;

        public MyViewHolder(View view) {
            super(view);
            image = (ImageView) view.findViewById(R.id.iv_service_img);
            iv_plus = (ImageView) view.findViewById(R.id.iv_service_plus);
            iv_minus = (ImageView) view.findViewById(R.id.iv_service_minus);
            title = (TextView) view.findViewById(R.id.tv_service_title);
            time = (TextView) view.findViewById(R.id.tv_service_time);
            price = (TextView) view.findViewById(R.id.tv_service_price);
            tv_qty = (TextView) view.findViewById(R.id.tv_service_qty);
            tv_orignal_price = (TextView) view.findViewById(R.id.tv_service_pricemain);
            iv_cancel = (ImageView) view.findViewById(R.id.iv_service_cancel);

            iv_cancel.setVisibility(View.GONE);

            iv_plus.setOnClickListener(this);
            iv_minus.setOnClickListener(this);

        }

        @Override
        public void onClick(View view) {
            int id = view.getId();
            int position = getAdapterPosition();
            Service_list_model model = modelList.get(position);

            if (id == R.id.iv_service_plus) {

                int qty = Integer.valueOf(tv_qty.getText().toString());
                qty = qty + 1;

                tv_qty.setText(String.valueOf(qty));

                updateCart(position);

            } else if (id == R.id.iv_service_minus) {

                int qty = 0;

                if (!tv_qty.getText().toString().equalsIgnoreCase(""))
                    qty = Integer.valueOf(tv_qty.getText().toString());

                if (qty > 0) {
                    qty = qty - 1;
                    tv_qty.setText(String.valueOf(qty));

                    updateCart(position);
                }

            }

        }

        // this function use for add, remove services from cart database
        private void updateCart(int position) {
            Service_list_model model = modelList.get(position);

            HashMap<String, String> map = new HashMap<>();
            map.put("id", model.getId());
            map.put("cat_id", model.getCat_id());
            map.put("title", model.getTitle());
            map.put("service_title", model.getService_title());
            map.put("service_icon", model.getService_icon());
            map.put("service_price", model.getService_price());
            map.put("service_discount", model.getService_discount());
            map.put("service_approxtime", model.getService_approxtime());
            map.put("book_status", "0");

            if (!tv_qty.getText().toString().equalsIgnoreCase("0")) {

                Double items = Double.parseDouble(tv_qty.getText().toString());
                Double get_price = Double.parseDouble(model.getService_price());

                if (!model.getService_discount().isEmpty() && !model.getService_discount().equals("0")) {

                    dbcart.setCart(map, Float.valueOf(tv_qty.getText().toString()),
                            Double.parseDouble(getDiscountPrice(map.get("service_discount"), "" + get_price * items, false)),
                            Double.parseDouble(getDiscountPrice(map.get("service_discount"), "" + get_price * items, true)));
                } else {
                    dbcart.setCart(map, Float.valueOf(tv_qty.getText().toString()),
                            get_price * items,
                            get_price * items);
                }

            } else {
                dbcart.removeItemFromCart(map.get("id"));
            }

            dbcart.bookItems();
            updatePriceIntent();

        }
    }

    public Service_list_adapter(List<Service_list_model> modelList, Context context) {
        this.modelList = modelList;
        dbcart = new DatabaseHandler(context);
    }

    @Override
    public Service_list_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_services, parent, false);

        context = parent.getContext();

        return new Service_list_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(Service_list_adapter.MyViewHolder holder, int position) {
        Service_list_model mList = modelList.get(position);

        Picasso.with(context)
                .load(BaseURL.IMG_SERVICE_URL + mList.getService_icon())
                .placeholder(R.drawable.ic_loading)
                .error(R.drawable.ic_noimage)
                .into(holder.image);

        holder.title.setText(mList.getService_title());

        String[] totaltime = mList.getService_approxtime().split(":");

        String finalTime = "";
        if (!totaltime[0].equals("0") && !totaltime[0].equals("00")) {
            finalTime += totaltime[0] + "hr ";
        }
        if (!totaltime[1].equals("0") && !totaltime[1].equals("00")) {
            finalTime += totaltime[1] + "min, ";
        }

        holder.time.setText(finalTime);
        holder.price.setText(CommonAppCompatActivity.getPriceWithCurrency(context, mList.getService_price()));

        /*try {
            // it comes out like this 2013-08-31 10:55:22 so adjust the date format
            SimpleDateFormat df = new SimpleDateFormat("hh:mm:ss", Locale.getDefault());
            Date date = df.parse(mList.getService_approxtime());
            holder.time.setText("" + date.getMinutes());
        } catch (ParseException e) {
            e.printStackTrace();
        }*/

        if (dbcart.isInCart(mList.getId())) {
            holder.tv_qty.setText(dbcart.getCartItemQty(mList.getId()));
        } else {
            holder.tv_qty.setText("0");
        }

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

    // this function return discount price and effected price
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

    // update intent for update current activity using broadcast intent
    private void updatePriceIntent() {
        Intent updates = new Intent("ServPro_price");
        updates.putExtra("type", "update_price");
        context.sendBroadcast(updates);
    }

}