<?php


        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
         
        // get database connection
        include_once '../../config/database.php';
        include_once '../../models/guard.php';
        include_once '../../models/duty.php'; 
        
        $database = new Database();
        $db = $database->getConnection();
        $guard = new Guard($db);
        $duty = new Duty($db);

        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        $data->id = uniqid('', true);

        // make sure data is not empty 
        if( !empty($data->instructions) &&
            !empty($data->guard) &&
            !empty($data->time_in))
            
        {
                $guard->id = $data->guard;
                $guard->readOne();
                if($guard->name == null){
                       // set response code - 400 bad request
                    http_response_code(400);
                 
                    // tell the user
                    echo json_encode(array("message" => "Unable to create duty. guard does not exist."));

                }
                else{
                   
                    $data->time_out = date('Y-m-d H:i:s');

                   // set product property values
                    $duty->id = $data->id;
                    $duty->instructions = $data->instructions;
                    $duty->time_in = $data->time_in;
                    $duty->guard = $data->guard;
                    $duty->time_out =$data->time_out;

                   
                 
                    // create the duty
                    if($duty->create()){
                 
                        // set response code - 201 created
                        http_response_code(201);
                 
                        // tell the user
                        echo json_encode(array("message" => "duty was created.", "data"=>$duty));
                    }
                 
                    // if unable to create the duty, tell the user
                    else{
                 
                        // set response code - 503 service unavailable
                        http_response_code(503);
                 
                        // tell the user
                        echo json_encode(array("message" => "Unable to create duty.","truacy"=>$duty->create(), "duty"=>$duty));
                    }
                }
        }
        // tell the user data is incomplete
        else{
         
            // set response code - 400 bad request
            http_response_code(400);
         
            // tell the user
            echo json_encode(array("message" => "Unable to create duty. Data is incomplete."));
        }





?>