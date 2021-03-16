package fragment;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import adapter.Service_list_adapter;
import codecanyon.servpro.CartActivity;
import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.R;
import model.Service_list_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.DatabaseHandler;
import util.NameValuePair;

/**
 * Created by Rajesh on 2017-09-21.
 */

public class ServiceFragment extends Fragment {

    private static String TAG = ServiceFragment.class.getSimpleName();

    private TextView tv_total_time, tv_total_price, tv_book;
    private RecyclerView rv_service;

    private DatabaseHandler dbcart;

    public ServiceFragment() {
        // Required empty public constructor
    }

    public static ServiceFragment newInstance(String cat_id) {

        ServiceFragment f = new ServiceFragment();
        Bundle b = new Bundle();
        b.putString("cat_id", cat_id);

        f.setArguments(b);

        return f;
    }

    @Override
    public void setUserVisibleHint(boolean isVisibleToUser) {
        super.setUserVisibleHint(isVisibleToUser);
        if (isVisibleToUser) {
            // animate here
            Log.e(TAG, "animate here");

            if (dbcart != null) {
                dbcart.deleteUnBookItems();
            }

            String cat_id = getArguments().getString("cat_id");

            // check internet connection is available or not
            if (ConnectivityReceiver.isConnected()) {
                makeGetService(cat_id);
            } else {
                // display snackbar
                ConnectivityReceiver.showSnackbar(getActivity());
            }
        } else {
            Log.e(TAG, "fragment is no longer visible");
            // fragment is no longer visible
        }
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_service, container, false);

        dbcart = new DatabaseHandler(getActivity());

        dbcart.deleteUnBookItems();

        tv_total_price = (TextView) view.findViewById(R.id.tv_total_price);
        tv_total_time = (TextView) view.findViewById(R.id.tv_total_time);
        tv_book = (TextView) view.findViewById(R.id.tv_book);
        rv_service = (RecyclerView) view.findViewById(R.id.rv_service);
        rv_service.setLayoutManager(new LinearLayoutManager(getActivity()));

        String cat_id = getArguments().getString("cat_id");

        // check internet connection is available or not
        if (ConnectivityReceiver.isConnected()) {
            makeGetService(cat_id);
        } else {
            // display snackbar
            ConnectivityReceiver.showSnackbar(getActivity());
        }

        tv_book.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                /*dbcart.bookItems();
                updatePrice();
                CommonAppCompatActivity.showToast(getActivity(), getResources().getString(R.string.service_item_added_in_cart));*/
                Intent cartIntent = new Intent(getContext(), CartActivity.class);
                startActivity(cartIntent);
            }
        });

        updatePrice();

        return view;
    }

    // update price, time in cart database and actionbar
    private void updatePrice() {
        // get total discount price of all available items and set in textview
        tv_total_price.setText(CommonAppCompatActivity.getPriceWithCurrency(getContext(), dbcart.getTotalDiscountAmount()));

        String[] totaltime = dbcart.getTotalTime(false).split(":");

        String finalTime = "";
        if (!totaltime[0].equals("0") && !totaltime[0].equals("00")) {
            finalTime += totaltime[0] + "hr ";
        }
        if (!totaltime[1].equals("0") && !totaltime[1].equals("00")) {
            finalTime += totaltime[1] + "min ";
        }

        tv_total_time.setText(finalTime);

        // update actionbar cart icon
        CommonAppCompatActivity compatActivity = new CommonAppCompatActivity();
        compatActivity.updateCounter(getActivity());
    }

    private void makeGetService(String cat_id) {

        ArrayList<NameValuePair> params = new ArrayList<>();
        params.add(new NameValuePair("cat_id", cat_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, params,
                BaseURL.GET_SERVICE_LIST_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                List<Service_list_model> service_list_modelList = new ArrayList<>();

                Gson gson = new Gson();
                Type listType = new TypeToken<List<Service_list_model>>() {
                }.getType();

                // store gson data in list
                service_list_modelList = gson.fromJson(response, listType);

                // bind adapter using list
                Service_list_adapter adapter = new Service_list_adapter(service_list_modelList, getActivity());
                rv_service.setAdapter(adapter);
                adapter.notifyDataSetChanged();

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                CommonAppCompatActivity.showToast(getActivity(), responce);
            }
        }, true, getActivity());
        task.execute();
    }

    @Override
    public void onPause() {
        super.onPause();
        // unregister reciver
        getActivity().unregisterReceiver(mUpdatePrice);
    }

    @Override
    public void onResume() {
        super.onResume();
        // register reciver
        getActivity().registerReceiver(mUpdatePrice, new IntentFilter("ServPro_price"));

        if (dbcart != null) {
            dbcart.deleteUnBookItems();
        }
    }

    // broadcast reciver for receive data
    private BroadcastReceiver mUpdatePrice = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {

            String type = intent.getStringExtra("type");

            if (type.contentEquals("update_price")) {
                updatePrice();
            }
        }
    };

}
