<?php


		//required headers
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		//database and models
		include_once '../../config/database.php';
		include_once '../../models/guard.php';
		include_once '../../models/building.php';
		

		//database connection
		$database = new Database();
		$db = $database->getConnection();

		//initializing guard
		$guard = new Guard($db);
		$building = new Building($db);
		 

		// get building id
		$data = json_decode(file_get_contents("php://input"));
		$building->id = $data->building;
		$building->readOne();

		//find if the building exists
		if ($building->name == null) {
			 // set response code - 404 Not found
		    http_response_code(404);
		 
		    // tell the user no building found
		    echo json_encode(
		        array("message" => "No building found.")
		    );
		}
		//if the building exists
		else{
			 //querring guards
			 $guard->building = $data->building;
			 $read = $guard->readBuildingGuards();
			 $count = $read->rowCount();
	         // echo $count;
			 if ($count>0) {
			 	$guardArray=array();
			 	$guardArray['guards']=array();
			 	while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
			 		extract($row);
			 		$guardItem=array(
			 			"id"=>$id,
			 			"name"=>$name,
			 			"address"=>$address,
			 			"age"=>$age,
			 			"created"=>$created,
			 			"modified"=>$modified,
			 			"building"=>$building
			 		);
			 		  array_push($guardArray["guards"], $guardItem);
			 
			 	}
					http_response_code(200);
					echo json_encode($guardArray);
			 }
			 else{
			 
			    // set response code - 404 Not found
			    http_response_code(404);
			 
			    // tell the user no guards found
			    echo json_encode(
			        array("message" => "No guards found.","read"=>$count)
			    );
			}

		}
		


?>
