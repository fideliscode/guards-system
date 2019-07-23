<?php
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
         
        // include database and model files
        include_once '../../config/core.php';
        include_once '../../config/database.php';
        include_once '../../models/guard.php';
         
        // instantiate database and guard model
        $database = new Database();
        $db = $database->getConnection();
         
        // initialize model
        $guard = new Guard($db);
         
        // get keywords
        $keywords=isset($_GET["s"]) ? $_GET["s"] : "";
         
        // query guards
        $stmt = $guard->search($keywords);
        $num = $stmt->rowCount();
         
        // check if more than 0 record found
        if($num>0){
         
            // guards array
            $guards_arr=array();
            $guards_arr["guards"]=array();
         

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
         
                $guard_item=array(
                    "id" => $id,
                    "name" => $name,
                    "address" => $address,
                    "age" => $age,
                    "building" => $building,
                    "created" => $created,
                    "modified" => $modified
                );
         
                array_push($guards_arr["guards"], $guard_item);
            }
         
            // set response code - 200 OK
            http_response_code(200);
         
            // show guards data
            echo json_encode($guards_arr);
        }
         
        else{
            // set response code - 404 Not found
            http_response_code(404);
         
            // tell the user no guards found
            echo json_encode(
                array("message" => "No guards found.")
            );
        }
?>