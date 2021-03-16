package adapter;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentPagerAdapter;

import fragment.ProsFragment;
import fragment.ReviewsFragment;
import fragment.ServiceFragment;

/**
 * Created by Rajesh on 2017-09-21.
 */

public class Service_detail_fragment_adapter extends FragmentPagerAdapter {

    String cat_id;

    public Service_detail_fragment_adapter(FragmentManager fm, String cat_id) {
        super(fm);
        this.cat_id = cat_id;
    }

    @Override
    public Fragment getItem(int pos) {
        // return fragment and pass data
        switch (pos) {
            case 0:
                return ServiceFragment.newInstance(cat_id);
            case 1:
                return ReviewsFragment.newInstance(cat_id);
            case 2:
                return ProsFragment.newInstance(cat_id);
            default:
                return null;
        }
    }

    @Override
    public int getCount() {
        return 3;
    }

}