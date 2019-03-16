<?php
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Asia/Kolkata');
 
define('DATABASE_HOST', 'localhost');
define('DB_NAME', 'talent_rest_api');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

// variables used for jwt
$key = "ut842ksb40iwq23a";
$iss = "http://localhost/talent-rest-api";
$aud = "";
$iat = 1356999524;
$nbf = 1357000000;
?>