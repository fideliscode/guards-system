<?php


        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
         
        // get database connection
        include_once '../../config/database.php';
        include_once '../../models/building.php';
        include_once '../../models/guard.php'; 
        
        $database = new Database();
        $db = $database->getConnection();
        $building = new Building($db);
        $guard = new Guard($db);

        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        $data->id = uniqid('', true);

        // make sure data is not empty 
        if( !empty($data->name) &&
            !empty($data->building) &&
            !empty($data->address) &&
            !empty($data->age) )
        {
                $building->id = $data->building;
                $building->readOne();
                if($building->name == null){
                       // set response code - 400 bad request
                    http_response_code(400);
                 
                    // tell the user
                    echo json_encode(array("message" => "Unable to create guard. building does not exist."));

                }
                else{
                    $data->created = date('Y-m-d H:i:s');
                    $data->modified = date('Y-m-d H:i:s');

                   // set product property values
                    $guard->id = $data->id;
                    $guard->name = $data->name;
                    $guard->address = $data->address;
                    $guard->age = $data->age;
                    $guard->building = $data->building;
                    $guard->created = $data->created;
                    $guard->modified = $data->modified;
                   
                 
                    // create the guard
                    if($guard->create()){
                 
                        // set response code - 201 created
                        http_response_code(201);
                 
                        // tell the user
                        echo json_encode(array("message" => "guard was created.", "data"=>$guard));
                    }
                 
                    // if unable to create the guard, tell the user
                    else{
                 
                        // set response code - 503 service unavailable
                        http_response_code(503);
                 
                        // tell the user
                        echo json_encode(array("message" => "Unable to create guard."));
                    }
                }
        }
        // tell the user data is incomplete
        else{
         
            // set response code - 400 bad request
            http_response_code(400);
         
            // tell the user
            echo json_encode(array("message" => "Unable to create guard. Data is incomplete."));
        }





?>