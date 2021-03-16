package fragment;

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
import adapter.Pros_adapter;
import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.R;
import model.Pros_list_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;

/**
 * Created by Rajesh on 2017-09-22.
 */

public class ProsFragment extends Fragment {

    private static String TAG = ProsFragment.class.getSimpleName();

    private RecyclerView rv_review;
    private TextView tv_items;

    public ProsFragment() {
        // Required empty public constructor
    }

    public static ProsFragment newInstance(String cat_id) {

        ProsFragment f = new ProsFragment();
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

            String cat_id = getArguments().getString("cat_id");

            // check internet connection is available or not
            if (ConnectivityReceiver.isConnected()) {
                makeGetPros(cat_id);
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
        View view = inflater.inflate(R.layout.fragment_pros, container, false);

        tv_items = (TextView) view.findViewById(R.id.tv_pros_items);
        rv_review = (RecyclerView) view.findViewById(R.id.rv_pros);
        rv_review.setLayoutManager(new LinearLayoutManager(getActivity()));

        return view;
    }

    private void makeGetPros(String cat_id) {

        ArrayList<NameValuePair> params = new ArrayList<>();
        params.add(new NameValuePair("cat_id", cat_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, params,
                BaseURL.GET_PROS_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                List<Pros_list_model> pros_list_modelList = new ArrayList<>();

                Gson gson = new Gson();
                Type listType = new TypeToken<List<Pros_list_model>>() {
                }.getType();

                // store gson data in list
                pros_list_modelList = gson.fromJson(response, listType);

                // bind adapter using list
                Pros_adapter adapter = new Pros_adapter(pros_list_modelList);
                rv_review.setAdapter(adapter);
                adapter.notifyDataSetChanged();

                tv_items.setText("" + pros_list_modelList.size());

                CommonAppCompatActivity.showListToast(getActivity(), pros_list_modelList.isEmpty());

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                CommonAppCompatActivity.showToast(getActivity(), responce);
            }
        }, true, getActivity());
        task.execute();
    }

}