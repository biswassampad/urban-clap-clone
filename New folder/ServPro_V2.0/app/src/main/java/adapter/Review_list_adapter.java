package adapter;

import android.content.Context;
import android.text.format.DateUtils;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.squareup.picasso.Picasso;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import Config.BaseURL;
import codecanyon.servpro.R;
import model.Review_list_model;

/**
 * Created by Rajesh on 2017-09-26.
 */

public class Review_list_adapter extends RecyclerView.Adapter<Review_list_adapter.MyViewHolder> {

    private List<Review_list_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView title, tv_date, tv_name;
        public ImageView image;
        public RatingBar rb_review;

        public MyViewHolder(View view) {
            super(view);
            image = (ImageView) view.findViewById(R.id.iv_review_img);
            title = (TextView) view.findViewById(R.id.tv_review_msg);
            tv_date = (TextView) view.findViewById(R.id.tv_review_date);
            tv_name = (TextView) view.findViewById(R.id.tv_review_name);
            rb_review = (RatingBar) view.findViewById(R.id.rb_review);
        }
    }

    public Review_list_adapter(List<Review_list_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public Review_list_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_reviews, parent, false);

        context = parent.getContext();

        return new Review_list_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(Review_list_adapter.MyViewHolder holder, int position) {
        Review_list_model mList = modelList.get(position);

        Picasso.with(context)
                .load(BaseURL.IMG_PROFILE_URL + mList.getUser_image())
                .placeholder(R.drawable.ic_loading)
                .error(R.drawable.ic_noimage)
                .into(holder.image);

        holder.title.setText(mList.getReviews());
        holder.tv_name.setText(mList.getUser_fullname());

        Float rating = Float.valueOf(mList.getRatings());
        holder.rb_review.setRating(rating);

        try {
            // it comes out like this 2013-08-31 10:55:22 so adjust the date format
            SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss", Locale.US);
            Date date = df.parse(mList.getOn_date());
            long epoch = date.getTime();

            String timePassedString = DateUtils.getRelativeTimeSpanString(epoch, System.currentTimeMillis(), DateUtils.SECOND_IN_MILLIS).toString();

            holder.tv_date.setText(timePassedString);
        } catch (ParseException e) {
            e.printStackTrace();
        }

    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

}