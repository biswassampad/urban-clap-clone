package Config;

import codecanyon.servpro.BuildConfig;

/**
 * Created by Rajesh on 2017-09-20.
 */

public class BaseURL {

    private static String BASE_URL = BuildConfig.BASE_URL;

    public static String REGISTER_URL = BASE_URL + "index.php/rest/user/register_new";
    public static String LOGIN_URL = BASE_URL + "index.php/rest/user/login";
    public static String FORGOT_PASSWORD_URL = BASE_URL + "index.php/rest/user/forgotpassword";
    public static String CHANGE_PASSWORD_URL = BASE_URL + "index.php/rest/user/changepass";
    public static String EDIT_PROFILE_IMG_URL = BASE_URL + "index.php/rest/user/updatepicture";
    public static String EDIT_PROFILE_URL = BASE_URL + "index.php/rest/user/updateprofile";
    public static String GET_CATEGORY_LIST_URL = BASE_URL + "index.php/rest/category/list";
    public static String GET_SERVICE_LIST_URL = BASE_URL + "index.php/rest/services/list";
    public static String GET_REVIEW_LIST_URL = BASE_URL + "index.php/rest/reviews/list";
    public static String GET_OFFER_LIST_URL = BASE_URL + "index.php/rest/offer/list";
    public static String CHECK_OFFER_URL = BASE_URL + "index.php/rest/offer/offer_check";
    public static String ADD_DELIVERY_ADDRESS_URL = BASE_URL + "index.php/rest/address/add";
    public static String GET_DELIVERY_ADDRESS_URL = BASE_URL + "index.php/rest/address/list";
    public static String EDIT_DELIVERY_ADDRESS_URL = BASE_URL + "index.php/rest/address/edit_address";
    public static String DELETE_DELIVERY_ADDRESS_URL = BASE_URL + "index.php/rest/address/delete_address";
    public static String GET_SCHEDULE_URL = BASE_URL + "index.php/rest/schedule/slot";
    public static String ADD_APPOINTMENT_URL = BASE_URL + "index.php/rest/appointment/add";
    public static String GET_APPOINTMENT_URL = BASE_URL + "index.php/rest/appointment/list";
    public static String GET_PROS_URL = BASE_URL + "index.php/rest/pros/list";
    public static String ADD_REVIEW_URL = BASE_URL + "index.php/rest/reviews/add";
    public static String CANCEL_APPOINTMENT_URL = BASE_URL + "index.php/rest/appointment/cancel";
    public static String TEAM_URL = BASE_URL + "index.php/rest/pros/team";
    public static String CONTACT_US_URL = BASE_URL + "index.php/rest/user/contact";
    public static String ABOUT_US_URL = BASE_URL + "index.php/rest/user/aboutus";
    public static String REGISTER_FCM_URL = BASE_URL + "index.php/rest/user/registerfcm";
    public static String GET_SETTINGS_URL = BASE_URL + "index.php/rest/settings/list";
    public static String GET_BANNER_LIST_URL = BASE_URL + "index.php/rest/banners/list";

    public static String IMG_PROFILE_URL = BASE_URL + "uploads/profile/";
    public static String IMG_CATEGORY_URL = BASE_URL + "uploads/category/";
    public static String IMG_SERVICE_URL = BASE_URL + "uploads/services/";
    public static String IMG_PROS_URL = BASE_URL + "uploads/pros/";
    public static String IMG_BANNER_URL = BASE_URL + "uploads/banners/";

    public static final String GET = "get";
    public static final String POST = "post";

    public static final String ENCRYPTED_PASSWORD = BuildConfig.ENCRYPTED_PASSWORD;

    public static final String PREFS_NAME = "ServProLoginPrefs";

    public static final String IS_LOGIN = "isLogin";
    public static final String KEY_NAME = "user_fullname";
    public static final String KEY_EMAIL = "user_email";
    public static final String KEY_ID = "user_id";
    public static final String KEY_TYPE_ID = "user_type_id";
    public static final String KEY_MOBILE = "user_phone";
    public static final String KEY_BDATE = "user_bdate";
    public static final String KEY_IMAGE = "user_image";
    public static final String KEY_ADDRESS = "user_address";
    public static final String KEY_GENDER = "user_gender";
    public static final String KEY_CITY = "user_city";

    public static final String ID = "id";
    public static final String USER_ID = "user_id";
    public static final String DELIVERY_ZIPCODE = "delivery_zipcode";
    public static final String DELIVERY_ADDRESS = "delivery_address";
    public static final String DELIVERY_LANDMARK = "delivery_landmark";
    public static final String DELIVERY_FULLNAME = "delivery_fullname";
    public static final String DELIVERY_MOBILENUMBER = "delivery_mobilenumber";
    public static final String DELIVERY_CITY = "delivery_city";
    public static final String USER_FULLNAME = "user_fullname";
    public static final String USER_IMAGE = "user_image";

}
