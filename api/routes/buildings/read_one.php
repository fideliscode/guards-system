<?php

				// required headers
				header("Access-Control-Allow-Origin: *");
				header("Access-Control-Allow-Headers: access");
				header("Access-Control-Allow-Methods: GET");
				header("Access-Control-Allow-Credentials: true");
				header('Content-Type: application/json');
				 
				// include database and building files
				include_once '../../config/database.php';
				include_once '../../models/building.php';
				 
				// get database connection
				$database = new Database();
				$db = $database->getConnection();
				 
				// prepare Building model
				$building = new Building($db);
				 
				// set ID property of record to read
				$building->id = isset($_GET['id']) ? $_GET['id'] : die();
				 
				// read the details of the building
				$building->readOne();
				 
				if($building->name!=null){
				    // create array
				    $building_arr = array(
				        "id" =>  $building->id,
				        "name" => $building->name,
				        "location" => $building->location,
				        "size" => $building->size,
				        "floors" => $building->floors,
				        "created" => $building->created,
				        "modified" => $building->modified
				 
				    );
				 
				    // set response code - 200 OK
				    http_response_code(200);
				 
				    // make it json format
				    echo json_encode($building_arr);
				}
				 
				else{
				    // set response code - 404 Not found
				    http_response_code(404);
				 
				    // tell the user building does not exist
				    echo json_encode(array("message" => "Building does not exist."));
				}

?>