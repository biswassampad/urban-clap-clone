package Config;

import codecanyon.serviceman.BuildConfig;

/**
 * Created by Rajesh on 2017-10-27.
 */

public class BaseURL {

    private static String BASE_URL = BuildConfig.BASE_URL;

    public static String REGISTER_URL = BASE_URL + "index.php/rest/user/register";
    public static String LOGIN_URL = BASE_URL + "index.php/rest/user/login";
    public static String FORGOT_PASSWORD_URL = BASE_URL + "index.php/rest/user/forgotpassword";
    public static String CHANGE_PASSWORD_URL = BASE_URL + "index.php/rest/user/changepass";
    public static String EDIT_PROFILE_IMG_URL = BASE_URL + "index.php/rest/user/updatepicture";
    public static String EDIT_PROFILE_URL = BASE_URL + "index.php/rest/user/updateprofile";
    public static String GET_ASSIGNED_URL = BASE_URL + "index.php/rest/pros/pros_assigned";
    public static String GET_COMPLETED_URL = BASE_URL + "index.php/rest/pros/pros_completed";
    public static String EDIT_STATUS_URL = BASE_URL + "index.php/rest/appointment/status";
    public static String ADD_PAID_URL = BASE_URL + "index.php/rest/appointment/paid";
    public static String START_SERVICE_URL = BASE_URL + "index.php/rest/appointment/start";
    public static String GET_APPOINTMENT_URL = BASE_URL + "index.php/rest/appointment/list";
    public static String REGISTER_FCM_URL = BASE_URL + "index.php/rest/user/registerfcm";
    public static String GET_SETTINGS_URL = BASE_URL + "index.php/rest/settings/list";

    public static String IMG_PROFILE_URL = BASE_URL + "uploads/profile/";
    public static String IMG_CATEGORY_URL = BASE_URL + "uploads/origional/";
    public static String IMG_SERVICE_URL = BASE_URL + "uploads/services/origional/";
    public static String IMG_SERVICE_DETAIL_URL = BASE_URL + "uploads/services/";
    public static String IMG_PROS_URL = BASE_URL + "uploads/pros/";

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

}
