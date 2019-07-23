<?php
			// required headers
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			 
			// include database and object file
			include_once '../../config/database.php';
			include_once '../../models/duty.php';
			 
			// get database connection
			$database = new Database();
			$db = $database->getConnection();
			 
			// prepare duty object
			$duty = new duty($db);
			 
			// get duty id
			$data = json_decode(file_get_contents("php://input"));
			 
			// set duty id to be deleted
			$duty->id = $data->id;
			$duty->readOne();
			 
			if ($duty->guard == null ) {
	                // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user duty does not exist
				    echo json_encode(array("message" => "duty does not exist."));
			}
			else{
				// delete the duty
				if($duty->delete()){
				 
				    // set response code - 200 ok
				    http_response_code(200);
				 
				    // tell the user
				    echo json_encode(array("message" => "duty was deleted."));
				}
				 
				// if unable to delete the duty
				else{
				 
				    // set response code - 503 service unavailable
				    http_response_code(503);
				 
				    // tell the user
				    echo json_encode(array("message" => "Unable to delete duty."));
				}
			}
			 
			
?>