<?php


        class Guard{
         
            // database connection and table name
            private $conn;
            private $table_name = "guards";
         
            // model properties
            public $id;
            public $name;
            public $address;
            public $age;
            public $building;
            public $created;
            public $modified;
         
         
            // constructor with $db as database connection
            public function __construct($db){
                $this->conn = $db;
            }

            //generic execution function
            public function executional($query){
           
                // prepare query
                $stmt = $this->conn->prepare($query);
                // sanitize
                $this->name=htmlspecialchars(strip_tags($this->name));
                $this->address=htmlspecialchars(strip_tags($this->address));
                $this->age=htmlspecialchars(strip_tags($this->age));
                $this->building=htmlspecialchars(strip_tags($this->building));
                $this->created=htmlspecialchars(strip_tags($this->created));
                $this->modified=htmlspecialchars(strip_tags($this->modified));
               
             
                // bind values
                $stmt->bindParam(":id", $this->id);
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":address", $this->address);
                $stmt->bindParam(":age", $this->age);
                $stmt->bindParam(":building", $this->building);
                $stmt->bindParam(":created", $this->created);
                $stmt->bindParam(":modified", $this->modified);
                

                // execute query
                if($stmt->execute()){
                    return true;
                }else{
                    
                return false;
                }

                    
            }

            // create guard
            function create(){
             
                // query to insert record
                $query = "INSERT INTO
                            " . $this->table_name . "
                        SET
                            id=:id, 
                            created=:created,
                            modified=:modified, 
                            name=:name, 
                            address=:address, 
                            age=:age, 
                            building=:building";
             
                 // prepare query statement and execute
               $report = $this->executional($query);
               return $report;
                 
            }
            
            // read guards
            function read(){
         
                // select all query
                $query = "SELECT *
                        FROM  " . $this->table_name . "  ORDER BY name" ;
                        
             
                // query statement
                $stmt = $this->conn->prepare($query);
             
                // execute query
                $stmt->execute();
             
                return $stmt;
            }

            // read guards belonging to a given building
            function readBuildingGuards(){
         
                // select all query
                $query = "SELECT *
                        FROM  " . $this->table_name . " 
                        WHERE
                            building = ?";
                        
             
                // query statement
                $stmt = $this->conn->prepare($query);
               // bind id of guard to be read
                $stmt->bindParam(1, $this->building);
                // execute query
                $stmt->execute();
             
                return $stmt;
            }

            //read single guard
            function readOne(){
             
                // query to read single record
                $query = "SELECT
                            *
                        FROM
                            " . $this->table_name . " 
                        WHERE
                            id = ?
                        LIMIT
                            0,1";
             
                // prepare query statement
                $stmt = $this->conn->prepare( $query );
             
                // bind id of guard to be read
                $stmt->bindParam(1, $this->id);
             
                // execute query
                $stmt->execute();
                //$databaseErrors = $stmt->errorInfo();
              
                // get retrieved row
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
             
                // set values to model properties
                $this->name = $row['name'];
                $this->age = $row['age'];
                $this->address = $row['address'];
                $this->building = $row['building'];
                $this->created = $row['created'];
                $this->modified = $row['modified'];
                $this->id = $row['id'];
                
             
                
            }
        


            
            
            // update the guard
            function update(){
             
                // update query
                $query = "UPDATE
                            " . $this->table_name . "
                        SET
                        modified=:modified,
                        created=:created, 
                        name=:name, 
                        address=:address, 
                        age=:age, 
                        building=:building
                          
                        WHERE
                            id = :id";
                // prepare query statement and execute
               $report = $this->executional($query);
               return $report;
               

            }
            // delete the guard
            function delete(){
             
                // delete query
                $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
             
                // prepare query
                $stmt = $this->conn->prepare($query);
             
                // sanitize
                $this->id=htmlspecialchars(strip_tags($this->id));
             
                // bind id of record to delete
                $stmt->bindParam(1, $this->id);
             
                // execute query
                if($stmt->execute()){
                    return true;
                }
             
                return false;
                 
            }

            // search guards
            function search($keywords){
             
                // select all query
                $query = "SELECT
                            name,id,address,age, building,created,modified
                        FROM
                            " . $this->table_name . " 
                        WHERE
                            name LIKE ? OR address LIKE ? OR age LIKE ? 
                        ORDER BY
                            created DESC";
             
                // prepare query statement
                $stmt = $this->conn->prepare($query);
             
                // sanitize
                $keywords=htmlspecialchars(strip_tags($keywords));
                $keywords = "%{$keywords}%";
             
                // bind
                $stmt->bindParam(1, $keywords);
                $stmt->bindParam(2, $keywords);
                $stmt->bindParam(3, $keywords);
             
                // execute query
                $stmt->execute();
             
                return $stmt;
            }

            

    }

    
?>