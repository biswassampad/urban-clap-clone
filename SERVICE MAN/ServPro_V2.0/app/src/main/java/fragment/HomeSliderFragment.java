package fragment;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Patterns;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.URLUtil;
import android.widget.ImageView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.squareup.picasso.Picasso;

import Config.BaseURL;
import codecanyon.servpro.R;
import codecanyon.servpro.ServiceActivity;
import model.BannerModel;

/**
 * Created on 17-06-2020.
 */
public class HomeSliderFragment extends Fragment {

    public static HomeSliderFragment newInstance(int position) {
        HomeSliderFragment f = new HomeSliderFragment();
        Bundle b = new Bundle();
        b.putInt("position", position);
        f.setArguments(b);
        return f;
    }


    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_home_slider, container, false);

        ImageView iv_img = (ImageView) rootView.findViewById(R.id.iv_home_slider);

        BannerModel bannerModel = HomeFragment.bannerModelArrayList.get(getArguments().getInt("position", 0));

        Picasso.with(getActivity())
                .load(BaseURL.IMG_BANNER_URL + bannerModel.getImage())
                .into(iv_img);

        iv_img.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                switch (bannerModel.getType()) {
                    case "link":
                        if (!TextUtils.isEmpty(bannerModel.getType_value())
                                && (URLUtil.isValidUrl(bannerModel.getType_value())
                                && Patterns.WEB_URL.matcher(bannerModel.getType_value()).matches())
                        ) {
                            Intent webIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(bannerModel.getType_value()));
                            startActivity(webIntent);
                        }
                        break;
                    case "category":
                        if (!bannerModel.getType_value().isEmpty()) {
                            Intent i = new Intent(getActivity(), ServiceActivity.class);
                            i.putExtra("cat_id", bannerModel.getType_value());
                            startActivity(i);
                        }
                        break;
                }
            }
        });

        return rootView;
    }

}
