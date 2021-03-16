package model;

import java.io.Serializable;

/**
 * Created on 17-06-2020.
 */
public class BannerModel implements Serializable {

    String id;
    String title;
    String image;
    String type;
    String type_value;
    String status;
    String cat_title;


    public String getId() {
        return id;
    }

    public String getTitle() {
        return title;
    }

    public String getImage() {
        return image;
    }

    public String getType() {
        return type;
    }

    public String getType_value() {
        return type_value;
    }

    public String getStatus() {
        return status;
    }

    public String getCat_title() {
        return cat_title;
    }

}
