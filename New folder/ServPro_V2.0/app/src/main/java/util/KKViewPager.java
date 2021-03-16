package util;
/*
 *
 * Copyright (C) 2018 Krishna Kumar Sharma
 *
 */

import android.content.Context;
import android.content.res.Resources;

import androidx.annotation.NonNull;
import androidx.viewpager.widget.PagerAdapter;
import androidx.viewpager.widget.ViewPager;

import android.util.AttributeSet;
import android.util.TypedValue;
import android.view.View;

import util.infiniteViewPager.InfinitePagerAdapter;


public class KKViewPager extends ViewPager implements ViewPager.PageTransformer {
    public static final String TAG = "KKViewPager";
    private float MAX_SCALE = 0.0f;
    private int mPageMargin;
    private boolean animationEnabled = true;
    private boolean fadeEnabled = false;
    private float fadeFactor = 0.5f;


    public KKViewPager(Context context) {
        this(context, null);
    }

    public KKViewPager(Context context, AttributeSet attrs) {
        super(context, attrs);
        // clipping should be off on the pager for its children so that they can scale out of bounds.
        /*setClipChildren(false);
        setClipToPadding(false);
        // to avoid fade effect at the end of the page
        setOverScrollMode(2);
        setPageTransformer(false, this);
        setOffscreenPageLimit(3);
        mPageMargin = dp2px(context.getResources(), 40);
        setPadding(mPageMargin, mPageMargin, mPageMargin, mPageMargin);*/
    }

    public int dp2px(Resources resource, int dp) {
        return (int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, dp, resource.getDisplayMetrics());
    }

    public void setAnimationEnabled(boolean enable) {
        this.animationEnabled = enable;
    }

    public void setFadeEnabled(boolean fadeEnabled) {
        this.fadeEnabled = fadeEnabled;
    }

    public void setFadeFactor(float fadeFactor) {
        this.fadeFactor = fadeFactor;
    }

    public void setmPageMargin(int mPageMargin) {
        this.mPageMargin = mPageMargin;
    }

    /*@Override
    public void transformPage(@NonNull View page, float position) {

    }*/

     @Override
    public void setPageMargin(int marginPixels) {
        mPageMargin = marginPixels;
        //setPadding(mPageMargin, mPageMargin, mPageMargin, mPageMargin);
        //setPadding(mPageMargin, 0, mPageMargin, 0);
    }

    @Override
    public void transformPage(View page, float position) {
        if (mPageMargin <= 0 || !animationEnabled)
            return;
        //page.setPadding(mPageMargin / 3, mPageMargin / 3, mPageMargin / 3, mPageMargin / 3);

        if (MAX_SCALE == 0.0f && position > 0.0f && position < 1.0f) {
            MAX_SCALE = position;
        }
        position = position - MAX_SCALE;
        float absolutePosition = Math.abs(position);
        if (position <= -1.0f || position >= 1.0f) {
            if (fadeEnabled)
                page.setAlpha(fadeFactor);
            // Page is not visible -- stop any running animations

        } else if (position == 0.0f) {

            // Page is selected -- reset any views if necessary
            page.setScaleX((1 + MAX_SCALE));
            page.setScaleY((1 + MAX_SCALE));
            page.setAlpha(1);
        } else {
            page.setScaleX(1 + MAX_SCALE * (1 - absolutePosition));
            page.setScaleY(1 + MAX_SCALE * (1 - absolutePosition));
            if (fadeEnabled)
                page.setAlpha(Math.max(fadeFactor, 1 - absolutePosition));
        }
    }

    @Override
    public void setAdapter(PagerAdapter adapter) {
        super.setAdapter(adapter);
        // offset first element so that we can scroll to the left
        setCurrentItem(0);
    }

    @Override
    public void setCurrentItem(int item) {
        // offset the current item to ensure there is space to scroll
        setCurrentItem(item, false);
    }

    @Override
    public void setCurrentItem(int item, boolean smoothScroll) {
        if (getAdapter().getCount() == 0) {
            super.setCurrentItem(item, smoothScroll);
            return;
        }
        item = getOffsetAmount() + (item % getAdapter().getCount());
        super.setCurrentItem(item, smoothScroll);
    }

    @Override
    public int getCurrentItem() {
        if (getAdapter().getCount() == 0) {
            return super.getCurrentItem();
        }
        int position = super.getCurrentItem();
        if (getAdapter() instanceof InfinitePagerAdapter) {
            InfinitePagerAdapter infAdapter = (InfinitePagerAdapter) getAdapter();
            // Return the actual item position in the data backing InfinitePagerAdapter
            return (position % infAdapter.getRealCount());
        } else {
            return super.getCurrentItem();
        }
    }

    private int getOffsetAmount() {
        if (getAdapter().getCount() == 0) {
            return 0;
        }
        if (getAdapter() instanceof InfinitePagerAdapter) {
            InfinitePagerAdapter infAdapter = (InfinitePagerAdapter) getAdapter();
            // allow for 100 back cycles from the beginning
            // should be enough to create an illusion of infinity
            // warning: scrolling to very high values (1,000,000+) results in
            // strange drawing behaviour
            return infAdapter.getRealCount() * 100;
        } else {
            return 0;
        }
    }

}
