package fragment;

import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

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
import codecanyon.servpro.MainActivity;
import codecanyon.servpro.R;
import model.Pros_list_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;

/**
 * Created by Rajesh on 2017-09-30.
 */

public class TeamFragment extends Fragment {

    private static String TAG = TeamFragment.class.getSimpleName();

    private RecyclerView rv_team;

    public TeamFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view = inflater.inflate(R.layout.fragment_team, container, false);

        ((MainActivity) getActivity()).setTitle(getResources().getString(R.string.team));

        view.setFocusableInTouchMode(true);
        view.requestFocus();
        view.setOnKeyListener(new View.OnKeyListener() {
            @Override
            public boolean onKey(View v, int keyCode, KeyEvent event) {
                if (event.getAction() == KeyEvent.ACTION_UP && keyCode == KeyEvent.KEYCODE_BACK) {

                    Fragment fm1 = new HomeFragment();
                    FragmentManager fragmentManager = getFragmentManager();
                    fragmentManager.beginTransaction()
                            .replace(R.id.contentPanel, fm1, "Home_fragment")
                            .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN)
                            .commit();

                    return true;
                }
                return false;
            }
        });

        rv_team = (RecyclerView) view.findViewById(R.id.rv_team);
        rv_team.setLayoutManager(new LinearLayoutManager(getActivity()));

        // check internet connection is available or not
        if (ConnectivityReceiver.isConnected()) {
            makeGetTeam();
        } else {
            // display snackbar
            ConnectivityReceiver.showSnackbar(getActivity());
        }

        return view;
    }

    private void makeGetTeam() {

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, new ArrayList<NameValuePair>(),
                BaseURL.TEAM_URL, new CommonAsyTask.VJsonResponce() {
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
                rv_team.setAdapter(adapter);
                adapter.notifyDataSetChanged();

                // show toast message when list is empty
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
