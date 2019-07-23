<?php

			// required headers
			header("Access-Control-Allow-Origin: *");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			 
			// include database and model files
			include_once '../../config/database.php';
			include_once '../../models/duty.php';
			include_once '../../models/guard.php';

			 
			// get database connection
			$database = new Database();
			$db = $database->getConnection();
			 
			// prepare duty model
			$duty = new Duty($db);
			$guard = new Guard($db);
			 
			// get id of duty to be edited
			$data = json_decode(file_get_contents("php://input"));


			 
			// set ID property of duty to be edited
			$duty->id = $data->id;
			$duty->readOne();
			 
			if ($duty->guard == null ) {
	                // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user duty does not exist
				    echo json_encode(array("message" => "duty does not exist."));
			}
			else{
					$guard->id = $data->guard;
	                $guard->readOne();
	                
	                if($guard->name == null){
	                       // set response code - 400 bad request
	                    http_response_code(400);
	                 
	                    // tell the user
	                    echo json_encode(array("message" => "Unable to create duty. guard does not exist."));

	                }
	                else{

				 	   // set duty property values
				 	   // $duty->id = $data->id;
						$duty->instructions = $data->instructions;
						$duty->guard = $data->guard;
						$duty->time_in = $data->time_in;
						$duty->time_out = $data->time_out;
						
						
						 

						$checker = $duty->update(); 
						// update the duty
						if($checker){
						 
						    // set response code - 200 ok
						    http_response_code(200);
						 
						    // tell the user
						    echo json_encode(array("message" => "duty was updated.", 
						    "checker"=>$checker));
						}
						 
						// if unable to update the duty, tell the user
						else{
						 
						    // set response code - 503 service unavailable
						    http_response_code(503);
						 
						    // tell the user
	                        echo json_encode(array("message" => "Unable to update duty.",
	                        "checker"=>$checker ));
						}

   			        }
                	
            }

            
?>