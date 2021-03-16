package codecanyon.servpro;

import android.os.Bundle;
import android.util.Log;

import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import adapter.Offers_adapter;
import model.Offers_list_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;

public class OffersActivity extends CommonAppCompatActivity {

    private static String TAG = OffersActivity.class.getSimpleName();

    private RecyclerView rv_offers;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_offers);

        rv_offers = (RecyclerView) findViewById(R.id.rv_offers);
        rv_offers.setLayoutManager(new LinearLayoutManager(this));

        // check internet connection available or not
        if (ConnectivityReceiver.isConnected()) {
            makeGetOffers();
        } else {
            ConnectivityReceiver.showSnackbar(this);
        }

    }

    private void makeGetOffers() {

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, new ArrayList<NameValuePair>(),
                BaseURL.GET_OFFER_LIST_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                List<Offers_list_model> Offers_list_model = new ArrayList<>();

                Gson gson = new Gson();
                Type listType = new TypeToken<List<Offers_list_model>>() {
                }.getType();

                // store gson data in list
                Offers_list_model = gson.fromJson(response, listType);

                // bind adapter using list
                Offers_adapter adapter = new Offers_adapter(Offers_list_model);
                rv_offers.setAdapter(adapter);
                adapter.notifyDataSetChanged();

                // show toast message when api data is empty
                CommonAppCompatActivity.showListToast(OffersActivity.this, Offers_list_model.isEmpty());

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
            }
        }, true, this);
        task.execute();
    }

}
