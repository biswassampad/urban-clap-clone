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
import adapter.Review_list_adapter;
import codecanyon.servpro.CommonAppCompatActivity;
import codecanyon.servpro.R;
import model.Review_list_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;

/**
 * Created by Rajesh on 2017-09-21.
 */

public class ReviewsFragment extends Fragment {

    private static String TAG = ReviewsFragment.class.getSimpleName();

    private RecyclerView rv_review;
    private TextView tv_items;

    public ReviewsFragment() {
        // Required empty public constructor
    }

    public static ReviewsFragment newInstance(String cat_id) {

        ReviewsFragment f = new ReviewsFragment();
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
                makeGetReview(cat_id);
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
        View view = inflater.inflate(R.layout.fragment_reviews, container, false);

        tv_items = (TextView) view.findViewById(R.id.tv_review_items);
        rv_review = (RecyclerView) view.findViewById(R.id.rv_reviews);
        rv_review.setLayoutManager(new LinearLayoutManager(getActivity()));

        return view;
    }

    private void makeGetReview(String cat_id) {

        ArrayList<NameValuePair> params = new ArrayList<>();
        params.add(new NameValuePair("cat_id", cat_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, params,
                BaseURL.GET_REVIEW_LIST_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                List<Review_list_model> review_list_modelList = new ArrayList<>();

                Gson gson = new Gson();
                Type listType = new TypeToken<List<Review_list_model>>() {
                }.getType();

                // store gson data in list
                review_list_modelList = gson.fromJson(response, listType);

                // bind adapter using list
                Review_list_adapter adapter = new Review_list_adapter(review_list_modelList);
                rv_review.setAdapter(adapter);
                adapter.notifyDataSetChanged();

                tv_items.setText("" + review_list_modelList.size());

                // show toast message when api data is empty
                CommonAppCompatActivity.showListToast(getActivity(), review_list_modelList.isEmpty());

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