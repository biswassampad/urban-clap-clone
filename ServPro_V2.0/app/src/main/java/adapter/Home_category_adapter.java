package adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.squareup.picasso.Picasso;

import java.util.List;

import Config.BaseURL;
import codecanyon.servpro.R;
import model.Category_list_model;

/**
 * Created by Rajesh on 2017-09-25.
 */

public class Home_category_adapter extends RecyclerView.Adapter<Home_category_adapter.MyViewHolder> {

    private List<Category_list_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView title, tv_total_rating;
        public ImageView image;
        public RatingBar ratingBar;

        public MyViewHolder(View view) {
            super(view);
            image = (ImageView) view.findViewById(R.id.iv_home_img);
            title = (TextView) view.findViewById(R.id.tv_home_title);
            tv_total_rating = (TextView) view.findViewById(R.id.tv_home_total_rating);
            ratingBar = (RatingBar) view.findViewById(R.id.rb_home);
        }
    }

    public Home_category_adapter(List<Category_list_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public Home_category_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_home, parent, false);

        context = parent.getContext();

        return new Home_category_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(Home_category_adapter.MyViewHolder holder, int position) {
        Category_list_model mList = modelList.get(position);

        Picasso.with(context)
                .load(BaseURL.IMG_CATEGORY_URL + mList.getImage())
                .placeholder(R.drawable.ic_loading)
                .error(R.drawable.ic_noimage)
                .into(holder.image);

        holder.title.setText(mList.getTitle());
        holder.tv_total_rating.setText(mList.getTotal_rating());
        holder.ratingBar.setRating(Float.valueOf(mList.getAvg_rating()));

    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

}