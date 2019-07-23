<?php

				// required headers
				header("Access-Control-Allow-Origin: *");
				header("Access-Control-Allow-Headers: access");
				header("Access-Control-Allow-Methods: GET");
				header("Access-Control-Allow-Credentials: true");
				header('Content-Type: application/json');
				 
				// include database and guard files
				include_once '../../config/database.php';
				include_once '../../models/guard.php';
				 
				// get database connection
				$database = new Database();
				$db = $database->getConnection();
				 
				// prepare guard model
				$guard = new Guard($db);
				 
				// set ID property of record to read
				$guard->id = isset($_GET['id']) ? $_GET['id'] : die();
				 
				// read the details of the guard
				$guard->readOne();
				 
				if($guard->name!=null){
				    // create array
				    $guard_arr = array(
				        "id" =>  $guard->id,
				        "name" => $guard->name,
				        "address" => $guard->address,
				        "age" => $guard->age,
				        "building" => $guard->building,
				        "created" => $guard->created,
				        "modified" => $guard->modified
				 
				    );
				 
				    // set response code - 200 OK
				    http_response_code(200);
				 
				    // make it json format
				    echo json_encode($guard_arr);
				}
				 
				else{
				    // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user guard does not exist
				    echo json_encode(array("message" => "guard does not exist."));
				}

?>