<?php


        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
         
        // get database connection
        include_once '../../config/database.php';
         
        // instantiate building model
        include_once '../../models/building.php'; 
        $database = new Database();
        $db = $database->getConnection();
        $building = new Building($db);
         
        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        $data->id = uniqid('', true);
        $data->created = date('Y-m-d H:i:s');
        $data->modified = date('Y-m-d H:i:s');

        // make sure data is not empty 
        if(
          
            !empty($data->name) &&
            !empty($data->floors) &&
            !empty($data->location) &&
            !empty($data->size) &&
            !empty($data->location)
        ){
         
            // set product property values
            $building->id = $data->id;
            $building->name = $data->name;
            $building->location = $data->location;
            $building->size = $data->size;
            $building->floors = $data->floors;
            $building->created = $data->created;
            $building->modified = $data->modified;
           
         
            // create the building
            if($building->create()){
         
                // set response code - 201 created
                http_response_code(201);
         
                // tell the user
                echo json_encode(array("message" => "Building was created.", "data"=>$building));
            }
         
            // if unable to create the building, tell the user
            else{
         
                // set response code - 503 service unavailable
                http_response_code(503);
         
                // tell the user
                echo json_encode(array("message" => "Unable to create building.","data"=>$building));
            }
        }
         
        // tell the user data is incomplete
        else{
         
            // set response code - 400 bad request
            http_response_code(400);
         
            // tell the user
            echo json_encode(array("message" => "Unable to create building. Data is incomplete."));
        }


?>