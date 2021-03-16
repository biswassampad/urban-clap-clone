package adapter;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentPagerAdapter;

import fragment.AssignedFragment;
import fragment.CompletedFragment;

/**
 * Created by Rajesh on 2017-10-27.
 */

public class Fragment_page_adapter extends FragmentPagerAdapter {

    String user_id;

    public Fragment_page_adapter(FragmentManager fm, String user_id) {
        super(fm);
        this.user_id = user_id;
    }

    @Override
    public Fragment getItem(int pos) {
        // return fragment and pass data
        switch (pos) {
            case 0:
                return AssignedFragment.newInstance(user_id);
            case 1:
                return CompletedFragment.newInstance(user_id);
            default:
                return null;
        }
    }

    @Override
    public int getCount() {
        return 2;
    }

}