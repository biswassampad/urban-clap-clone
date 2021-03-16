package adapter;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentStatePagerAdapter;
import androidx.viewpager2.adapter.FragmentStateAdapter;

import fragment.HomeSliderFragment;

/**
 * Created on 17-06-2020.
 */
public class HomeSliderPageAdapter extends FragmentStatePagerAdapter {

    private int mainSize;

    public HomeSliderPageAdapter(FragmentManager fm, int mainSize) {
        super(fm);
        this.mainSize = mainSize;
    }

    @NonNull
    @Override
    public Fragment getItem(int position) {
        int index = position % mainSize;
        return HomeSliderFragment.newInstance(index);
    }

    @Override
    public int getCount() {
        return 4000;
    }

}
