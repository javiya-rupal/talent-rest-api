<?php
require "../vendor/autoload.php";

// required headers
header("Access-Control-Allow-Origin: http://localhost/talent-rest-api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// files needed to connect to database
include_once '../config/core.php';
include_once '../core/database.php';
include_once '../objects/user.php';

use Firebase\JWT\JWT;
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$user->email = $data->mail;
$user->password = $data->password;
$user->firstname = ($data->firstName) ?? '';
$user->lastname = ($data->lastName) ?? '';
 
// create the user
if($user->create()){
	$token = array(
       "iss" => $iss,
       "aud" => $aud,
       "iat" => $iat,
       "nbf" => $nbf,
       "data" => array(
           "id" => $user->id,
           "email" => $user->email
       )
    );

    // generate token
    $authToken = JWT::encode($token, $key);
    
    http_response_code(200);
    echo json_encode(["authToken" => $authToken]);
} 
else{
    http_response_code(400);
    echo json_encode(["message" => "Unexpected error"]);
}