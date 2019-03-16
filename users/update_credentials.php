<?php
require "../vendor/autoload.php";

// required headers
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
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

$headers = getallheaders();
$authToken = $headers['authToken']; //Get token

// if token is not empty
if($authToken){
	//Check if any parameter missing
	if (empty($data->mail) || empty($data->password) || empty($data->oldPassword || empty($_GET['userId']))) {
		http_response_code(401);
		echo json_encode(["message" => "Insufficient Parameters"]);
		exit;
	}

	$user->email = $data->mail;
	$email_exists = $user->emailExists(); 
	if(!$email_exists || !password_verify($data->oldPassword, $user->password)){		
		http_response_code(401);
		echo json_encode(["message" => "Wrong credentials"]);
		exit;
	}
    // if decode succeed, update user details
    try {
        // decode authToken
        $decoded = JWT::decode($authToken, $key, array('HS256'));

        // set user property values
		$user->password = $data->password;
		$user->id = $decoded->data->id;		 

		$updateCredentials = $user->updateCredentials();
		// Update user credentials
		if($updateCredentials){
		    // we need to re-generate token because user details might be different
			$authToken = array(
			   "iss" => $iss,
			   "aud" => $aud,
			   "iat" => $iat,
			   "nbf" => $nbf,
			   "data" => array(
			       "id" => $user->id,
			       "email" => $user->email
			   )
			);
			$authToken = JWT::encode($authToken, $key);
			 
			http_response_code(200);			 
			echo json_encode(
			        [
			            "message" => "User credentials updated.",
			            "token" => $authToken,
			            "data" => $updateCredentials 
			        ]
			);
		}		 
		// message if unable to update user
		else{
		    http_response_code(401);		 
		    echo json_encode(["message" => "Unable to update user."]);
		}
    } 
    // if decode fails, it means jwt is invalid
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