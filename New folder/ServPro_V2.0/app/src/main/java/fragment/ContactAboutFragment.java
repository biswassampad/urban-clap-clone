package fragment;

import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.os.Bundle;
import android.text.Html;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebChromeClient;
import android.webkit.WebViewClient;
import android.widget.TextView;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import Config.BaseURL;
import codecanyon.servpro.MainActivity;
import codecanyon.servpro.R;
import codecanyon.servpro.databinding.FragmentContactBinding;
import util.CommonAsyTask;
import util.ConnectivityReceiver;
import util.NameValuePair;

/**
 * Created by Rajesh on 2017-09-30.
 */

public class ContactAboutFragment extends Fragment {

    private static String TAG = ContactAboutFragment.class.getSimpleName();

    private FragmentContactBinding binding;

    public ContactAboutFragment() {
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
        binding = FragmentContactBinding.inflate(inflater, container, false);

        binding.getRoot().setFocusableInTouchMode(true);
        binding.getRoot().requestFocus();
        binding.getRoot().setOnKeyListener(new View.OnKeyListener() {
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

        String gettitle = getArguments().getString("title");

        binding.webContact.getSettings().setJavaScriptEnabled(true);
        binding.webContact.setWebViewClient(new WebViewClient());
        binding.webContact.setWebChromeClient(new WebChromeClient());

        // check internet connection is available or not
        if (ConnectivityReceiver.isConnected()) {
            if (gettitle.equals("About")) {
                ((MainActivity) getActivity()).setTitle(getResources().getString(R.string.about_us));
                makeGetContact(BaseURL.ABOUT_US_URL);
            } else {
                ((MainActivity) getActivity()).setTitle(getResources().getString(R.string.contact_us));
                makeGetContact(BaseURL.CONTACT_US_URL);
            }
        } else {
            // display snackbar
            ConnectivityReceiver.showSnackbar(getActivity());
        }

        return binding.getRoot();
    }

    private void makeGetContact(String url) {

        // CommonAsyTask class for load data from api and manage response and api
        CommonAsyTask task = new CommonAsyTask(BaseURL.GET, new ArrayList<NameValuePair>(),
                url, new CommonAsyTask.VJsonResponce() {
            @Override
            public void VResponce(String response) {
                Log.e(TAG, response);

                try {
                    // convert string to jsonobject
                    JSONObject jsonObject = new JSONObject(response);

                    // getting string from jsonobject
                    String id = jsonObject.getString("id");
                    String pg_title = jsonObject.getString("pg_title");
                    String pg_slug = jsonObject.getString("pg_slug");
                    String pg_descri = jsonObject.getString("pg_descri");
                    String pg_status = jsonObject.getString("pg_status");
                    String pg_foot = jsonObject.getString("pg_foot");
                    String crated_date = jsonObject.getString("crated_date");

                    //tv_contact.setText(Html.fromHtml(pg_descri));
                    binding.webContact.loadData(pg_descri, "text/html; charset=UTF-8", null);

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }

            @Override
            public void VError(String responce) {
                Log.e(TAG, responce);
            }
        }, true, getActivity());
        task.execute();
    }
}