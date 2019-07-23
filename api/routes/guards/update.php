<?php

			// required headers
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			 
			// include database and model files
			include_once '../../config/database.php';
			include_once '../../models/guard.php';
			include_once '../../models/building.php';

			 
			// get database connection
			$database = new Database();
			$db = $database->getConnection();
			 
			// prepare guard model
			$guard = new Guard($db);
			$building = new Building($db);
			 
			// get id of guard to be edited
			$data = json_decode(file_get_contents("php://input"));


			 
			// set ID property of guard to be edited
			$guard->id = $data->id;
			$guard->readOne();
			 
			if ($guard->name == null ) {
	                // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user guard does not exist
				    echo json_encode(array("message" => "guard does not exist."));
			}
			else{
					$building->id = $data->building;
	                $building->readOne();
	                
	                if($building->name == null){
	                       // set response code - 400 bad request
	                    http_response_code(400);
	                 
	                    // tell the user
	                    echo json_encode(array("message" => "Unable to create guard. building does not exist."));

	                }
	                else{

				 	   // set guard property values
				 	   // $guard->id = $data->id;
						$guard->name = $data->name;
						$guard->age = $data->age;
						$guard->address = $data->address;
						$guard->building = $data->building;
						$guard->created = $guard->created;
						$guard->modified = date('Y-m-d H:i:s');
						 

						$checker = $guard->update(); 
						// update the guard
						if($checker){
						 $guard->readOne();
						    // set response code - 200 ok
						    http_response_code(200);
						 
						    // tell the user
						    echo json_encode(array("message" => "guard was updated.", 
						    "guard"=>$guard));
						}
						 
						// if unable to update the guard, tell the user
						else{
						 
						    // set response code - 503 service unavailable
						    http_response_code(503);
						 
						    // tell the user
	                        echo json_encode(array("message" => "Unable to update guard.",
	                        "checker"=>$checker ));
						}

   			        }
                	
            }

            
?>