package fragment;

import android.app.Activity;
import android.app.Fragment;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.fragment.app.FragmentActivity;
import androidx.recyclerview.widget.LinearLayoutManager;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import Config.BaseURL;
import adapter.HomeSliderPageAdapter;
import adapter.Home_category_adapter;
import codecanyon.servpro.MainActivity;
import codecanyon.servpro.R;
import codecanyon.servpro.ServiceActivity;
import codecanyon.servpro.databinding.FragmentHomeBinding;
import model.BannerModel;
import model.Category_list_model;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;
import util.RecyclerTouchListener;

import static java.lang.Math.abs;

/**
 * Created by Rajesh on 2017-09-20.
 */

public class HomeFragment extends Fragment {

    private static String TAG = HomeFragment.class.getSimpleName();

    private List<Category_list_model> category_list_modelList = new ArrayList<>();
    public static ArrayList<BannerModel> bannerModelArrayList = new ArrayList<>();

    private Timer timer;

    private Context context;
    private FragmentHomeBinding binding;

    public HomeFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        binding = FragmentHomeBinding.inflate(inflater, container, false);

        ((MainActivity) context).setTitle(getResources().getString(R.string.app_name));

        binding.getRoot().setFocusableInTouchMode(true);
        binding.getRoot().requestFocus();
        binding.getRoot().setOnKeyListener(new View.OnKeyListener() {
            @Override
            public boolean onKey(View v, int keyCode, KeyEvent event) {
                if (event.getAction() == KeyEvent.ACTION_UP && keyCode == KeyEvent.KEYCODE_BACK) {

                    ((MainActivity) context).setFinish();

                    return true;
                }
                return false;
            }
        });

        ((MainActivity) context).setBottomSelection();

        binding.rvHome.setLayoutManager(new LinearLayoutManager(context));

        binding.viewpager.setClipToPadding(false);
        binding.viewpager.setPadding(150, 0, 150, 0);
        binding.viewpager.setPageMargin(40);

        // check internet connection is available or not
        if (ConnectivityReceiver.isConnected()) {
            makeGetBannerList();
            makeGetCategory();
        } else {
            // display snackbar
            ConnectivityReceiver.showSnackbar(context);
        }

        binding.rvHome.addOnItemTouchListener(new RecyclerTouchListener(context, binding.rvHome, new RecyclerTouchListener.OnItemClickListener() {
            @Override
            public void onItemClick(View view, int position) {
                Category_list_model model = category_list_modelList.get(position);

                Intent i = new Intent(context, ServiceActivity.class);
                i.putExtra("cat_id", model.getId());
                startActivity(i);
            }

            @Override
            public void onLongItemClick(View view, int position) {

            }
        }));

        return binding.getRoot();
    }

    private void makeGetBannerList() {
        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, new ArrayList<NameValuePair>(),
                BaseURL.GET_BANNER_LIST_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                bannerModelArrayList.clear();

                Gson gson = new Gson();
                Type listType = new TypeToken<List<BannerModel>>() {
                }.getType();

                // store gson data in list
                bannerModelArrayList.addAll(gson.fromJson(response, listType));

                HomeSliderPageAdapter homeSliderPageAdapter = new HomeSliderPageAdapter(((FragmentActivity) context).getSupportFragmentManager(), bannerModelArrayList.size());
                binding.viewpager.setAdapter(homeSliderPageAdapter);
                binding.dotsIndicator.setViewPager(binding.viewpager, bannerModelArrayList.size());

                binding.viewpager.post(new Runnable() {
                    @Override
                    public void run() {
                        binding.viewpager.setCurrentItem(closestNumber(2000, bannerModelArrayList.size()), false);
                        binding.dotsIndicator.doAnimation = false;
                        binding.dotsIndicator.removeDots(bannerModelArrayList.size());
                        binding.dotsIndicator.refreshDots();
                        binding.dotsIndicator.onPageChange(0, 0F, 0, 0);
                        binding.dotsIndicator.currentPage = 1;
                    }
                });

                if (timer != null) {
                    timer.purge();
                    timer.cancel();
                    timer = null;
                }
                timer = new Timer();
                timer.scheduleAtFixedRate(new SliderTimer(), 5000, 5000);

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
            }
        }, true, context);
        task.execute();
    }

    private void makeGetCategory() {

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, new ArrayList<NameValuePair>(),
                BaseURL.GET_CATEGORY_LIST_URL, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                Gson gson = new Gson();
                Type listType = new TypeToken<List<Category_list_model>>() {
                }.getType();

                // store gson data in list
                category_list_modelList = gson.fromJson(response, listType);

                // bind adapter using list
                Home_category_adapter adapter = new Home_category_adapter(category_list_modelList);
                binding.rvHome.setAdapter(adapter);
                adapter.notifyDataSetChanged();

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
            }
        }, true, context);
        task.execute();
    }

    private class SliderTimer extends TimerTask {
        @Override
        public void run() {
            ((Activity) context).runOnUiThread(new Runnable() {
                @Override
                public void run() {
                    binding.viewpager.setCurrentItem((binding.viewpager.getCurrentItem()) + 1, true);
                }
            });
        }
    }

    private int closestNumber(int n, int m) { // find the quotient
        int q = n / m;
        // 1st possible closest number
        int n1 = m * q;
        // 2nd possible closest number
        int n2;
        if (n * m > 0) {
            n2 = m * (q + 1);
        } else {
            n2 = m * (q - 1);
        }
        // if true, then n1 is the required closest number
        if (abs(n - n1) < abs(n - n2))
            return n1;
        else
            return n2;
        // else n2 is the required closest number
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        context = getActivity();
    }
}
