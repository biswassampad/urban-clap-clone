package codecanyon.servpro;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.widget.TextView;

import androidx.viewpager.widget.ViewPager;

import com.google.android.material.tabs.TabLayout;

import adapter.Service_detail_fragment_adapter;

public class ServiceActivity extends CommonAppCompatActivity {

    private TabLayout tab_service;
    private ViewPager vp_service;
    private int[] tabIcons = {R.drawable.ic_menu_service_white,
            R.drawable.ic_menu_share_white,
            R.drawable.ic_menu_user_white};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_service);

        tab_service = (TabLayout) findViewById(R.id.tab_service);
        vp_service = (ViewPager) findViewById(R.id.vp_service);

        String cat_id = getIntent().getStringExtra("cat_id");

        vp_service.setAdapter(new Service_detail_fragment_adapter(getSupportFragmentManager(), cat_id));
        tab_service.setupWithViewPager(vp_service);
        setupViewPager();
    }

    private void setupViewPager() {
        // set tab title or text in tablayout
        TextView tabOne = (TextView) LayoutInflater.from(this).inflate(R.layout.custom_tab, null);
        tabOne.setText(getResources().getString(R.string.service));
        tabOne.setCompoundDrawablesWithIntrinsicBounds(tabIcons[0], 0, 0, 0);
        tab_service.getTabAt(0).setCustomView(tabOne);

        TextView tabTwo = (TextView) LayoutInflater.from(this).inflate(R.layout.custom_tab, null);
        tabTwo.setText(getResources().getString(R.string.reviews));
        tabTwo.setCompoundDrawablesWithIntrinsicBounds(tabIcons[1], 0, 0, 0);
        tab_service.getTabAt(1).setCustomView(tabTwo);

        TextView tabThree = (TextView) LayoutInflater.from(this).inflate(R.layout.custom_tab, null);
        tabThree.setText(getResources().getString(R.string.pros));
        tabThree.setCompoundDrawablesWithIntrinsicBounds(tabIcons[2], 0, 0, 0);
        tab_service.getTabAt(2).setCustomView(tabThree);

    }

}
