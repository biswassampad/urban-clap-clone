package model;

/**
 * Created by Rajesh on 2017-09-25.
 */

public class Category_list_model {

    private String id;
    private String title;
    private String slug;
    private String parent;
    private String leval;
    private String description;
    private String image;
    private String status;
    private String avg_rating;
    private String total_rating;
    private String review_count;


    public String getId() {
        return id;
    }

    public String getTitle() {
        return title;
    }

    public String getSlug() {
        return slug;
    }

    public String getParent() {
        return parent;
    }

    public String getLeval() {
        return leval;
    }

    public String getDescription() {
        return description;
    }

    public String getImage() {
        return image;
    }

    public String getStatus() {
        return status;
    }

    public String getAvg_rating() {
        return avg_rating;
    }

    public String getTotal_rating() {
        return total_rating;
    }

    public String getReview_count() {
        return review_count;
    }

}
