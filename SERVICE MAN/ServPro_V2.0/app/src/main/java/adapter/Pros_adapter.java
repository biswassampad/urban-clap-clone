package adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.squareup.picasso.Picasso;

import java.util.List;

import Config.BaseURL;
import codecanyon.servpro.R;
import model.Pros_list_model;

/**
 * Created by Rajesh on 2017-09-28.
 */

public class Pros_adapter extends RecyclerView.Adapter<Pros_adapter.MyViewHolder> {

    private List<Pros_list_model> modelList;
    private Context context;

    public class MyViewHolder extends RecyclerView.ViewHolder {
        public TextView name, tv_category, tv_hour, tv_experience, tv_degree, tv_qualified;
        public ImageView image;

        public MyViewHolder(View view) {
            super(view);
            image = (ImageView) view.findViewById(R.id.iv_pros_img);
            name = (TextView) view.findViewById(R.id.tv_pros_name);
            tv_category = (TextView) view.findViewById(R.id.tv_pros_category);
            tv_hour = (TextView) view.findViewById(R.id.tv_pros_hour_start);
            tv_experience = (TextView) view.findViewById(R.id.tv_pros_experience);
            tv_degree = (TextView) view.findViewById(R.id.tv_pros_degree);
            tv_qualified = (TextView) view.findViewById(R.id.tv_pros_qualified);
        }
    }

    public Pros_adapter(List<Pros_list_model> modelList) {
        this.modelList = modelList;
    }

    @Override
    public Pros_adapter.MyViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.row_pros, parent, false);

        context = parent.getContext();

        return new Pros_adapter.MyViewHolder(itemView);
    }

    @Override
    public void onBindViewHolder(Pros_adapter.MyViewHolder holder, int position) {
        Pros_list_model mList = modelList.get(position);

        Picasso.with(context)
                .load(BaseURL.IMG_CATEGORY_URL + mList.getPros_photo())
                .placeholder(R.drawable.ic_loading)
                .error(R.drawable.ic_noimage)
                .into(holder.image);

        holder.name.setText(mList.getPros_name());
        holder.tv_category.setText(mList.getPros_cats());
        holder.tv_hour.setText(context.getResources().getString(R.string.working_hour) + mList.getWorking_hour_start() + context.getResources().getString(R.string.to) + mList.getWorking_hour_end());
        holder.tv_experience.setText(mList.getPros_exp() + context.getResources().getString(R.string.year));
        holder.tv_degree.setText(mList.getPros_degree());

        if (mList.getIs_qualified().equals("1")) {
            holder.tv_qualified.setVisibility(View.VISIBLE);
        } else {
            holder.tv_qualified.setVisibility(View.GONE);
        }

    }

    @Override
    public int getItemCount() {
        return modelList.size();
    }

}