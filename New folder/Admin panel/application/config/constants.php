<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


defined('EXT')      OR define('EXT', ".php");
defined('DEFAULT_PASSWORD')      OR define('DEFAULT_PASSWORD', "");


defined('USER_ADMIN')      OR define('USER_ADMIN', 1);
defined('USER_PROS')      OR define('USER_PROS', 2);
defined('USER_APP')      OR define('USER_APP', 3);

defined('STATUS_PENDING')      OR define('STATUS_PENDING', 0);
defined('STATUS_ASSIGNED')      OR define('STATUS_ASSIGNED', 1);
defined('STATUS_STARTED')      OR define('STATUS_STARTED', 2);
defined('STATUS_COMPLETED')      OR define('STATUS_COMPLETED', 3);
defined('STATUS_CANCLED')      OR define('STATUS_CANCLED', 4);

defined('RESPONCE')      OR define('RESPONCE', 'responce');
defined('ERROR')      OR define('ERROR', 'error');
defined('MESSAGE')      OR define('MESSAGE', 'message');
defined('DATA')      OR define('DATA', 'data');

defined('COMMON_DATE_FORMATE') OR define('COMMON_DATE_FORMATE','Y-m-d H:i:s');
defined('DB_TIME_FORMATE') OR define('DB_TIME_FORMATE','H:i:s');
defined('TIMEPICKER_FORMATE') OR define('TIMEPICKER_FORMATE','h:i A');
defined('DB_DATE_FORMATE') OR define('DB_DATE_FORMATE','Y-m-d');

defined('DATE_FORMATE') OR define('DATE_FORMATE','Y-m-d');
defined('DATETIME_FORMATE') OR define('DATETIME_FORMATE','d-m-Y h:i A');
defined('JS_DATE_FORMATE') OR define('JS_DATE_FORMATE','dd-mm-yyyy');
defined('COM_DATE_FORMATE') OR define('COM_DATE_FORMATE','d-m-Y');
defined('USER_PATH')      OR define('USER_PATH', './uploads/profile');
defined('CATEGORY_PATH')      OR define('CATEGORY_PATH', './uploads/category');
defined('BANNER_PATH')      OR define('BANNER_PATH', './uploads/banners');
defined('PROS_PATH')      OR define('PROS_PATH', './uploads/pros');
defined('SERVICES_PATH')      OR define('SERVICES_PATH', './uploads/services');
defined('COMMON_HOURS_FORMATE') OR define('COMMON_HOURS_FORMATE','H:i:s');

defined('USER_FULLNAME')        OR define('USER_FULLNAME', 'user_fullname'); // no errors
defined('USER_EMAIL')        OR define('USER_EMAIL', 'user_email'); // no errors
defined('USER_ID')        OR define('USER_ID', 'user_id'); // no errors
defined('USER_TYPE_ID')        OR define('USER_TYPE_ID', 'user_type_id'); // no errors
defined('LOGGED_ID')        OR define('LOGGED_ID', 'logged_in'); // no errors
defined('USER_IMAGE')        OR define('USER_IMAGE', 'user_image'); // no errors
