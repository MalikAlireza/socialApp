<?php

define('TITLE', 'Social App');

// server / database configuration
define('DB_USERNAME', '<your-database-username>');
define('DB_PASSWORD', '<your-database-password>');
define('DB_HOST', '<your-database-host>');
define('DB_NAME', '<your-database-name>');
define('HOST', '<your-host-ip-address>');
define('PROFILE_HOST', 'http://<your-host-ip-address>/socialapp-api/index.php/user/profilePhoto/');

// fcm configuration
define("FCM", "<fcm-id>");

// notification types
define('PUSH_TYPE_NOTIFICATION', 1);
define('PUSH_TYPE_REQUESTS', 2);
define('PUSH_TYPE_MESSAGE', 3);

// response/error codes
define('REQUEST_PASSED', 1);
define('REQUEST_FAILED', 2);
define('FCM_UPDATE_SUCCESSFUL', 3);
define('FCM_UPDATE_FAILED', 4);
define('USER_ALREADY_EXISTS', 5);
define('USER_INVALID', 6);
define('EMAIL_INVALID', 7);
define('UNKNOWN_ERROR', 404);
define('FAILED_MESSAGE_SEND', 8);
define('MESSAGE_SENT', 9);
define('PASSWORD_INCORRECT', 10);
define('ACCOUNT_DISABLED', 11);
define('SESSION_EXPIRED', 440);
define('EMAIL_ALREADY_EXISTS', 12);

?>
