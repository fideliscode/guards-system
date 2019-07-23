<?php


		//required headers
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		//database and models
		include_once '../../config/database.php';
		include_once '../../models/duty.php';
		include_once '../../models/guard.php';
		

		//database connection
		$database = new Database();
		$db = $database->getConnection();

		//initializing duty
		$duty = new duty($db);
		$guard = new guard($db);
		 

		// get guard id
		$data = json_decode(file_get_contents("php://input"));
		$guard->id = $data->guard;
		$guard->readOne();

		//find if the guard exists
		if ($guard->name == null) {
			 // set response code - 404 Not found
		    http_response_code(404);
		 
		    // tell the user no guard found
		    echo json_encode(
		        array("message" => "No guard found.")
		    );
		}
		//if the guard exists
		else{
			 //querring duties
			 $duty->guard = $data->guard;
			 $read = $duty->readGuardDuties();
			 $count = $read->rowCount();
	         // echo $count;
			 if ($count>0) {
			 	$dutyArray=array();
			 	$dutyArray['duties']=array();
			 	while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
			 		extract($row);
			 		$dutyItem=array(
			 			"id"=>$id,
			 			"instructions"=>$instructions,
			 			"time_in"=>$time_in,
			 			"time_out"=>$time_out,
			 			"guard"=>$guard
			 		);
			 		  array_push($dutyArray["duties"], $dutyItem);
			 
			 	}
					http_response_code(200);
					echo json_encode($dutyArray);
			 }
			 else{
			 
			    // set response code - 404 Not found
			    http_response_code(404);
			 
			    // tell the user no duties found
			    echo json_encode(
			        array("message" => "No duties found.")
			    );
			}

		}
		


?>
