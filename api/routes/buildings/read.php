<?php


		//required headers
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		//database and models
		include_once '../../config/database.php';
		include_once '../../models/building.php';
		

		//database connection
		$database = new Database();
		$db = $database->getConnection();

		//initializing building
		$building = new Building($db);
		 
		 //querring buildings
		 $read =$building->read();
		 $count = $read->rowCount();
         // echo $count;
		 if ($count>0) {
		 	$buildingArray=array();
		 	$buildingArray['buildings']=array();
		 	while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
		 		extract($row);
		 		$buildingItem=array(
		 			"id"=>$id,
		 			"name"=>$name,
		 			"location"=>$location,
		 			"size"=>$size,
		 			"created"=>$created,
		 			"modified"=>$modified,
		 			"floors"=>$floors
		 		);
		 		  array_push($buildingArray["buildings"], $buildingItem);
		 
		 	}
				http_response_code(200);
				echo json_encode($buildingArray);
		 }
		 else{
		 
		    // set response code - 404 Not found
		    http_response_code(404);
		 
		    // tell the user no buildings found
		    echo json_encode(
		        array("message" => "No buildings found.")
		    );
		}


?>