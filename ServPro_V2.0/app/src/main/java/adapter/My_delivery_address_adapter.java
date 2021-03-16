package adapter;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import codecanyon.servpro.Add_delivery_addressActivity;
import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.R;
import model.Delivery_address_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;
import util.Session_management;

/**
 * Created by Rajesh on 2017-09-27.
 */

public class My_delivery_address_adapter extends RecyclerView.Adapter<My_delivery_address_adapter.MyViewHolder> {

    private List<Delivery_address_model> modelList;
    private Context context;
    private boolean is_select;

    public class MyViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        private TextView name, city, landmark, zipcode, address, mobile, edit, delete;

        private MyViewHolder(View view) {
            super(view);
            name = (TextView) view.findViewById(R.id.tv_address_name);
            city = (TextView) view.findViewById(R.id.tv_address_city);
            landmark = (TextView) view.findViewById(R.id.tv_address_landmark);
            zipcode = (TextView) view.findViewById(R.id.tv_address_zipcode);
            address = (TextView) view.findViewById(R.id.tv_address_address);
            mobile = (TextView) view.findViewById(R.id.tv_address_mobile);
            edit = (TextView) view.findViewById(R.id.tv_address_edit);
            delete = (TextView) view.findViewById(R.id.tv_address_delete);
            LinearLayout ll_address = (LinearLayout) view.findViewById(R.id.ll_address);

            edit.setOnClickListener(this);
            delete.setOnClickListener(this);
            ll_address.setOnClickListener(this);

        }

        @Override
        public void onClick(View view) {
            int id = view.getId();

            Delivery_address_model model = modelList.get(getAdapterPosition());

            if (id == R.id.tv_address_edit) {
                Intent i = new Intent(context, Add_delivery_addressActivity.class);
                i.putExtra("id", model.getId());
                i.putExtra("user_id", model.getUser_id());
                i.putExtra("delivery_zipcode", model.getDelivery_zipcode());
                i.putExtra("delivery_address", model.getDelivery_address());
                i.putExtra("delivery_landmark", model.getDelivery_landmark());
                i.putExtra("delivery_fullname", model.getDelivery_fullname());
                i.putExtra("delivery_mobilenumber", model.getDelivery_mobilenumber());
                i.putExtra("delivery_city", model.getDelivery_city());
                i.putExtra("user_fullname", model.getUser_fullname());
                i.putExtra("user_image", model.getUser_image());
                context.startActivity(i);
            } else if (id == R.id.tv_address_delete) {

                if (ConnectivityReceiver.isConnected()) {
                    makeDeleteDeliveryAddress(model.getId(), getAdapterPosition());
                }

            } else {
                if (is_select) {

                    Session_management sessionManagement = new Session_management(context);
                    // store address data in session
                    sessionManagement.createDeliveryaddress(model.getId(), model.getUser_id(), model.getDelivery_zipcode(), model.getDelivery_address(),
                            model.getDelivery_landmark(), model.getDelivery_fullname(), model.getDelivery_mobilenumber(), model.getDelivery_city(),
                            model.getUser_fullname(), model.getUser_image());

                    ((Activity) context).finish();
                }
            }
        }
    }

    public My_delivery_address_adapter(List<Delivery_address_model> modelList, boolean is_select) {
        this.modelList = modelList;
        this.is_select = is_select;
    }

    @Override
    public My_delivery_address_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_address, parent, false);

        context = parent.getContext();

        return new My_delivery_address_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(My_delivery_address_adapter.MyViewHolder holder, int position) {
        Delivery_address_model mList = modelList.get(position);

        holder.name.setText(mList.getDelivery_fullname());
        holder.city.setText(mList.getDelivery_city());
        holder.landmark.setText(mList.getDelivery_landmark());
        holder.zipcode.setText(mList.getDelivery_zipcode());
        holder.address.setText(mList.getDelivery_address());
        holder.mobile.setText(mList.getDelivery_mobilenumber());
    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

    // delete delivery address request
    private void makeDeleteDeliveryAddress(String id, final int position) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("id", id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.POST, params,
                BaseURL.DELETE_DELIVERY_ADDRESS_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(context.toString(), response);

                CommonAppCompatActivity.showToast(context, response);

                // remove item from adapter and notify removed item
                modelList.remove(position);
                notifyItemRemoved(position);
                notifyItemRangeChanged(position, modelList.size());

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