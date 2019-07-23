<?php
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
         
        // include database and model files
        include_once '../../config/core.php';
        include_once '../../config/database.php';
        include_once '../../models/building.php';
         
        // instantiate database and building model
        $database = new Database();
        $db = $database->getConnection();
         
        // initialize model
        $building = new Building($db);
         
        // get keywords
        $keywords=isset($_GET["s"]) ? $_GET["s"] : "";
         
        // query buildings
        $stmt = $building->search($keywords);
        $num = $stmt->rowCount();
         
        // check if more than 0 record found
        if($num>0){
         
            // buildings array
            $buildings_arr=array();
            $buildings_arr["buildings"]=array();
         

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
         
                $building_item=array(
                    "id" => $id,
                    "name" => $name,
                    "location" => $location,
                    "size" => $size,
                    "floors" => $floors,
                    "created" => $created,
                    "modified" => $modified
                );
         
                array_push($buildings_arr["buildings"], $building_item);
            }
         
            // set response code - 200 OK
            http_response_code(200);
         
            // show buildings data
            echo json_encode($buildings_arr);
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