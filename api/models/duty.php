<?php


        class Duty{
         
            // database connection and table guard
            private $conn;
            private $table_name = "duties";
         
            // model properties
            public $id;
            public $guard;
            public $instructions;
            public $time_in;
            public $time_out;
         
         
            // constructor with $db as database connection
            public function __construct($db){
                $this->conn = $db;
            }

            //generic execution function
            public function executional($query){
           
                // prepare query
                $stmt = $this->conn->prepare($query);
                // sanitize
                $this->guard=htmlspecialchars(strip_tags($this->guard));
                $this->instructions=htmlspecialchars(strip_tags($this->instructions));
                $this->time_in=htmlspecialchars(strip_tags($this->time_in));
                $this->time_out=htmlspecialchars(strip_tags($this->time_out));
               
             
                // bind values
                $stmt->bindParam(":id", $this->id);
                $stmt->bindParam(":guard", $this->guard);
                $stmt->bindParam(":instructions", $this->instructions);
                $stmt->bindParam(":time_in", $this->time_in);
                $stmt->bindParam(":time_out", $this->time_out);
                

                // execute query
                if($stmt->execute()){
                    return true;
                }else{
                    return false;
                }

                    
            }

            // create duty
            function create(){
             
                // query to insert duty
                $query = "INSERT INTO
                            " . $this->table_name . "
                        SET
                            id=:id, 
                            time_in=:time_in,
                            time_out=:time_out, 
                            guard=:guard, 
                            instructions=:instructions";

             
                 // prepare query statement and execute
               $report = $this->executional($query);
               return $report;
                 
            }
            
         
            // read duties
            function read(){
         
                // select all query
                $query = "SELECT *
                        FROM  " . $this->table_name . "  ORDER BY time_out" ;
                        
             
                // query statement
                $stmt = $this->conn->prepare($query);
             
                // execute query
                $stmt->execute();
             
                return $stmt;
            }



            // read duties belonging to a given building
            function readGuardDuties(){
         
                // select all query
                $query = "SELECT *
                        FROM  " . $this->table_name . "  
                        WHERE
                            guard = ?
                        ORDER BY time_out
                            ";
                        
             
                // query statement
                $stmt = $this->conn->prepare($query);
               // bind id of duty to be read
                $stmt->bindParam(1, $this->guard);
                // execute query
                $stmt->execute();
             
                return $stmt;
            }

            //read single duty
            function readOne(){
             
                // query to read single duty
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
             
                // bind id of duty to be read
                $stmt->bindParam(1, $this->id);
             
                // execute query
                $stmt->execute();
                //$databaseErrors = $stmt->errorInfo();
              
                // get retrieved row
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
             
                // set values to model properties
                $this->guard = $row['guard'];
                $this->instructions = $row['instructions'];
                $this->time_in = $row['time_in'];
                $this->time_out = $row['time_out'];
                $this->id = $row['id'];
                
             
                
            }
        


            
            
            // update the duty
            function update(){
             
                // update query
                $query = "UPDATE
                            " . $this->table_name . "
                        SET
                        time_out=:time_out,
                        time_in=:time_in, 
                        guard=:guard, 
                        instructions=:instructions
                          
                        WHERE
                            id = :id";
                // prepare query statement and execute
               $report = $this->executional($query);
               return $report;
               

            }

            // delete the duty
            function delete(){
             
                // delete query
                $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
             
                // prepare query
                $stmt = $this->conn->prepare($query);
             
                // sanitize
                $this->id=htmlspecialchars(strip_tags($this->id));
             
                // bind id of duty to delete
                $stmt->bindParam(1, $this->id);
             
                // execute query
                if($stmt->execute()){
                    return true;
                }
             
                return false;
                 
            }

           

            

    }

    
?>