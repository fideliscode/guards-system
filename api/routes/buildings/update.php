<?php

			// required headers
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			 
			// include database and object files
			include_once '../../config/database.php';
			include_once '../../models/building.php';

			 
			// get database connection
			$database = new Database();
			$db = $database->getConnection();
			 
			// prepare building object
			$building = new Building($db);
			 
			// get id of building to be edited
			$data = json_decode(file_get_contents("php://input"));
			 
			// set ID property of building to be edited
			$building->id = $data->id;
			$building->readOne();
			 
			if ($building->name == null ) {
	                // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user building does not exist
				    echo json_encode(array("message" => "Building does not exist."));
			}
			else{
			 	   // set building property values
			 	   // $building->id = $data->id;
					$building->name = $data->name;
					$building->size = $data->size;
					$building->location = $data->location;
					$building->floors = $data->floors;
					$building->created = $building->created;
					$building->modified = date('Y-m-d H:i:s');
					 

					$checker = $building->update(); 
					// update the building
					if($checker){
					 
					    // set response code - 200 ok
					    http_response_code(200);
					 
					    // tell the user
					    echo json_encode(array("message" => "building was updated.", "checker"=>$checker));
					}
					 
					// if unable to update the building, tell the user
					else{
					 
					    // set response code - 503 service unavailable
					    http_response_code(503);
					 
					    // tell the user
                     echo json_encode(array("message" => "Unable to update building.","checker"=>$checker ));
					}

			}
 

?>