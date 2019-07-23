<?php
			// required headers
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			 
			// include database and object file
			include_once '../../config/database.php';
			include_once '../../models/building.php';
			 
			// get database connection
			$database = new Database();
			$db = $database->getConnection();
			 
			// prepare building object
			$building = new building($db);
			 
			// get building id
			$data = json_decode(file_get_contents("php://input"));
			 
			// set building id to be deleted
			$building->id = $data->id;
			$building->readOne();
			 
			if ($building->name == null ) {
	                // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user building does not exist
				    echo json_encode(array("message" => "Building does not exist."));
			}
			else{
				// delete the building
				if($building->delete()){
				 
				    // set response code - 200 ok
				    http_response_code(200);
				 
				    // tell the user
				    echo json_encode(array("message" => "building was deleted."));
				}
				 
				// if unable to delete the building
				else{
				 
				    // set response code - 503 service unavailable
				    http_response_code(503);
				 
				    // tell the user
				    echo json_encode(array("message" => "Unable to delete building."));
				}
			}
			 
			
?>