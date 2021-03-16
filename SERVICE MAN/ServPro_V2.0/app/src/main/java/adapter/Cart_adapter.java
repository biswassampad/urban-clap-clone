package adapter;

import android.app.Activity;
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
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;

import Config.BaseURL;
import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.R;
import util.DatabaseHandler;

/**
 * Created by Rajesh on 2017-09-26.
 */

public class Cart_adapter extends RecyclerView.Adapter<Cart_adapter.ProductHolder> {

    private ArrayList<HashMap<String, String>> list;
    private Activity activity;
    private DatabaseHandler dbcart;

    public Cart_adapter(Activity activity, ArrayList<HashMap<String, String>> list) {
        this.list = list;
        this.activity = activity;

        dbcart = new DatabaseHandler(activity);
    }

    @Override
    public ProductHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = null;
        view = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_services, parent, false);
        return new ProductHolder(view);
    }

    @Override
    public void onBindViewHolder(final ProductHolder holder, final int position) {
        final HashMap<String, String> map = list.get(position);

        Picasso.with(activity)
                .load(BaseURL.IMG_SERVICE_URL + map.get("service_icon"))
                .placeholder(R.drawable.ic_loading)
                .error(R.drawable.ic_noimage)
                .into(holder.iv_logo);

        holder.title.setText(map.get("service_title"));
        holder.price.setText(CommonAppCompatActivity.getPriceWithCurrency(activity, map.get("service_price")));

        String[] totaltime = map.get("service_approxtime").split(":");
        String finalTime = "";
        if (!totaltime[0].equals("0") && !totaltime[0].equals("00")) {
            finalTime += totaltime[0] + "hr ";
        }
        if (!totaltime[1].equals("0") && !totaltime[1].equals("00")) {
            finalTime += totaltime[1] + "min, ";
        }
        holder.time.setText(finalTime);

        Double discount = Double.valueOf(map.get("service_discount"));

        if (discount > 0) {
            holder.tv_orignal_price.setVisibility(View.VISIBLE);

            holder.price.setText(CommonAppCompatActivity.getPriceWithCurrency(activity, getDiscountPrice(discount.toString(), map.get("service_price"), true)));
            holder.tv_orignal_price.setText(CommonAppCompatActivity.getPriceWithCurrency(activity, map.get("service_price")));
            holder.tv_orignal_price.setPaintFlags(holder.tv_orignal_price.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);

        } else {
            holder.tv_orignal_price.setVisibility(View.GONE);
        }

        holder.tv_qty.setText(dbcart.getCartItemQty(map.get("id")));

        holder.iv_minus.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                int qty = 0;
                if (!holder.tv_qty.getText().toString().equalsIgnoreCase(""))
                    qty = Integer.valueOf(holder.tv_qty.getText().toString());

                if (qty > 0) {
                    qty = qty - 1;
                    holder.tv_qty.setText(String.valueOf(qty));

                    updateCart(holder.tv_qty, map, position);
                }

            }
        });

        holder.iv_plus.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                int qty = Integer.valueOf(holder.tv_qty.getText().toString());
                qty = qty + 1;
                holder.tv_qty.setText(String.valueOf(qty));

                updateCart(holder.tv_qty, map, position);

            }
        });

        holder.iv_cancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                dbcart.removeItemFromCart(map.get("id"));
                list.remove(position);
                notifyDataSetChanged();
                updateintent();
            }
        });

    }

    // this function use for update cart listing data like quntity, price etc..
    private void updateCart(TextView tv_qty, HashMap<String, String> map, int position) {

        if (!tv_qty.getText().toString().equalsIgnoreCase("0")) {

            Double items = Double.parseDouble(tv_qty.getText().toString());
            Double get_price = Double.parseDouble(map.get("service_price"));

            if (!map.get("service_discount").isEmpty() && !map.get("service_discount").equalsIgnoreCase("0")) {

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
            list.remove(position);
            notifyDataSetChanged();
        }

        updateintent();
    }

    // get discount price by discount amount and price
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

    @Override
    public int getItemCount() {
        return list.size();
    }

    class ProductHolder extends RecyclerView.ViewHolder {
        private TextView title, time, price, tv_qty, tv_orignal_price;
        private ImageView iv_logo, iv_plus, iv_minus, iv_cancel;

        private ProductHolder(View view) {
            super(view);

            iv_logo = (ImageView) view.findViewById(R.id.iv_service_img);
            iv_plus = (ImageView) view.findViewById(R.id.iv_service_plus);
            iv_minus = (ImageView) view.findViewById(R.id.iv_service_minus);
            title = (TextView) view.findViewById(R.id.tv_service_title);
            time = (TextView) view.findViewById(R.id.tv_service_time);
            price = (TextView) view.findViewById(R.id.tv_service_price);
            tv_qty = (TextView) view.findViewById(R.id.tv_service_qty);
            iv_cancel = (ImageView) view.findViewById(R.id.iv_service_cancel);
            tv_orignal_price = (TextView) view.findViewById(R.id.tv_service_pricemain);
        }
    }

    // update intent for update current activity using broadcast intent
    private void updateintent() {
        Intent updates = new Intent("ServPro_price");
        updates.putExtra("type", "update_price");
        activity.sendBroadcast(updates);
    }

}
