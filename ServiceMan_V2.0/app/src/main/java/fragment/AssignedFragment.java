package fragment;

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;

import Config.BaseURL;
import adapter.Assigned_adapter;
import codecanyon.serviceman.CommonAppCompatActivity;
import codecanyon.serviceman.MainActivity;
import codecanyon.serviceman.R;
import model.Assigned_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;
import util.Session_management;

/**
 * Created by Rajesh on 2017-10-27.
 */

public class AssignedFragment extends Fragment {

    private static String TAG = AssignedFragment.class.getSimpleName();

    private List<Assigned_model> appointment_list_modelList = new ArrayList<>();

    private Context context;

    private RecyclerView rv_assigned;
    private Assigned_adapter adapter;

    private Session_management sessionManagement;

    public AssignedFragment() {
        // Required empty public constructor
    }

    public static AssignedFragment newInstance(String user_id) {

        AssignedFragment f = new AssignedFragment();
        Bundle b = new Bundle();
        b.putString("user_id", user_id);

        f.setArguments(b);

        return f;
    }

    @Override
    public void setUserVisibleHint(boolean isVisibleToUser) {
        super.setUserVisibleHint(isVisibleToUser);
        if (isVisibleToUser) {
            // animate here
            Log.e(TAG, "animate here");

            if (ConnectivityReceiver.isConnected()) {
                makeGetAssigned(getArguments().getString("user_id"));
            } else {
                ConnectivityReceiver.showSnackbar(context);
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
        View view = inflater.inflate(R.layout.fragment_assigned, container, false);

        sessionManagement = new Session_management(context);

        rv_assigned = view.findViewById(R.id.rv_assigned);
        rv_assigned.setLayoutManager(new LinearLayoutManager(getActivity()));

        adapter = new Assigned_adapter(appointment_list_modelList);
        rv_assigned.setAdapter(adapter);

        if (ConnectivityReceiver.isConnected()) {
            String pros_id = sessionManagement.getUserDetails().get(BaseURL.KEY_ID);
            makeGetAssigned(pros_id);
        } else {
            ConnectivityReceiver.showSnackbar(context);
        }

        return view;
    }

    private void makeGetAssigned(String pros_id) {

        ArrayList<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new NameValuePair("pros_id", pros_id));

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, params,
                BaseURL.GET_ASSIGNED_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                appointment_list_modelList.clear();

                Gson gson = new Gson();
                Type listType = new TypeToken<List<Assigned_model>>() {
                }.getType();

                // store gson value in list
                appointment_list_modelList.addAll(gson.fromJson(response, listType));

                // bind adapter using list
                adapter.notifyDataSetChanged();

                // display toast message
                CommonAppCompatActivity.showListToast(context, appointment_list_modelList.isEmpty());
            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
                // display toast message
                CommonAppCompatActivity.showToast(context, responce);
                appointment_list_modelList.clear();
                adapter.notifyDataSetChanged();
            }
        }, true, context);
        task.execute();
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        context = getActivity();
    }

    @Override
    public void onResume() {
        super.onResume();
        if (CommonAppCompatActivity.getPaymentDone(context)) {
            if (adapter != null) {
                adapter.dissmisDialog();
                CommonAppCompatActivity.setPaymentDone(context, false);
                ((MainActivity) context).setPagePosition(1);
            }
        } else if (getArguments().containsKey("user_id")) {
            if (ConnectivityReceiver.isConnected()) {
                makeGetAssigned(getArguments().getString("user_id"));
            } else {
                ConnectivityReceiver.showSnackbar(context);
            }
        }
    }
}
