<?php
			// required headers
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			 
			// include database and object file
			include_once '../../config/database.php';
			include_once '../../models/guard.php';
			 
			// get database connection
			$database = new Database();
			$db = $database->getConnection();
			 
			// prepare guard object
			$guard = new guard($db);
			 
			// get guard id
			$data = json_decode(file_get_contents("php://input"));
			 
			// set guard id to be deleted
			$guard->id = $data->id;
			$guard->readOne();
			 
			if ($guard->name == null ) {
	                // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user guard does not exist
				    echo json_encode(array("message" => "guard does not exist."));
			}
			else{
				// delete the guard
				if($guard->delete()){
				 
				    // set response code - 200 ok
				    http_response_code(200);
				 
				    // tell the user
				    echo json_encode(array("message" => "guard was deleted."));
				}
				 
				// if unable to delete the guard
				else{
				 
				    // set response code - 503 service unavailable
				    http_response_code(503);
				 
				    // tell the user
				    echo json_encode(array("message" => "Unable to delete guard."));
				}
			}
			 
			
?>