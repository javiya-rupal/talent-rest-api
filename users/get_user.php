<?php
require "../vendor/autoload.php";

// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
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

$headers = getallheaders();
$authToken = $headers['authToken'];

// if token is not empty
if($authToken){
    //Check if any parameter missing
    $userId = ($_GET['userId']) ?? '';
    if (empty($userId)) {
      http_response_code(401);
      echo json_encode(array("message" => "User id is required"));
      exit;
    }
    // if decode succeed, update user details
    try {
        // decode authToken
        $decoded = JWT::decode($authToken, $key, array('HS256'));

        if($decoded->data->id != $userId) {
          http_response_code(400);
          echo json_encode(array("message" => "Invalid User id"));
          exit;
        }
        $user->id = $userId;
        $userDetails = $user->getDetails();

        // Update user credentials
        if($userDetails){
            http_response_code(200);       
            echo json_encode(
                    [
                        "data" => $userDetails
                    ]
            );
      }
  }
  catch (Exception $e){
      http_response_code(401);
      echo json_encode([
          "message" => "Access denied.",
          "error" => $e->getMessage()
      ]);
  }
} 
// show error message if token is empty
else {
    http_response_code(401);
    echo json_encode(["message" => "Access denied."]);
}