<?php

				// required headers
				header("Access-Control-Allow-Origin: *");
				header("Access-Control-Allow-Headers: access");
				header("Access-Control-Allow-Methods: GET");
				header("Access-Control-Allow-Credentials: true");
				header('Content-Type: application/json');
				 
				// include database and duty files
				include_once '../../config/database.php';
				include_once '../../models/duty.php';
				 
				// get database connection
				$database = new Database();
				$db = $database->getConnection();
				 
				// prepare duty model
				$duty = new Duty($db);
				 
				// set ID property of record to read
				$duty->id = isset($_GET['id']) ? $_GET['id'] : die();
				 
				// read the details of the duty
				$duty->readOne();
				 
				if($duty->guard !=null){
				    // create array
				    $duty_arr = array(
				        "id" =>  $duty->id,
				        "instructions" => $duty->instructions,
				        "guard" => $duty->guard,
				        "time_in" => $duty->time_in,
				        "time_out" => $duty->time_out
				 
				    );
				 
				    // set response code - 200 OK
				    http_response_code(200);
				 
				    // make it json format
				    echo json_encode($duty_arr);
				}
				 
				else{
				    // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user duty does not exist
				    echo json_encode(array("message" => "duty does not exist."));
				}

?>