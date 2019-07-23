<?php


		class Building{
		 
		    // database connection and table name
		    private $conn;
		    private $table_name = "buildings";
		 
		    // object properties
		    public $id;
		    public $name;
		    public $location;
		    public $size;
		    public $floors;
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
			    $this->location=htmlspecialchars(strip_tags($this->location));
			    $this->size=htmlspecialchars(strip_tags($this->size));
			    $this->floors=htmlspecialchars(strip_tags($this->floors));
			    $this->created=htmlspecialchars(strip_tags($this->created));
			    $this->modified=htmlspecialchars(strip_tags($this->modified));
			   
			 
			    // bind values
			    $stmt->bindParam(":id", $this->id);
			    $stmt->bindParam(":name", $this->name);
			    $stmt->bindParam(":location", $this->location);
			    $stmt->bindParam(":size", $this->size);
			    $stmt->bindParam(":floors", $this->floors);
			    $stmt->bindParam(":created", $this->created);
			    $stmt->bindParam(":modified", $this->modified);
				

				// execute query
				if($stmt->execute()){
					return true;
				}else{
					return false;
				}

				    
		    }
		    




		    // read buildings
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


		    
		    // create building
			function create(){
			 
			    // query to insert record
			    $query = "INSERT INTO
			                " . $this->table_name . "
			            SET
			                id=:id, 
			                created=:created,
			                modified=:modified, 
			                name=:name, 
			                location=:location, 
			                size=:size, 
			                floors=:floors";
			 
			     // prepare query statement and execute
			   $report = $this->executional($query);
			   return $report;
			     
			}
			


			//read single building
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
			 
			    // bind id of building to be updated
			    $stmt->bindParam(1, $this->id);
			 
			    // execute query
			    $stmt->execute();
			    //$databaseErrors = $stmt->errorInfo();
			  
			    // get retrieved row
			    $row = $stmt->fetch(PDO::FETCH_ASSOC);
			 
			    // set values to model properties
			    $this->name = $row['name'];
			    $this->size = $row['size'];
			    $this->location = $row['location'];
			    $this->floors = $row['floors'];
			    $this->created = $row['created'];
			    $this->modified = $row['modified'];
			    $this->id = $row['id'];
			    
			 
			    
			}
			
			// update the product
			function update(){
			 
			    // update query
			    $query = "UPDATE
			                " . $this->table_name . "
			            SET
			            modified=:modified,
			            created=:created, 
			            name=:name, 
			            location=:location, 
			            size=:size, 
			            floors=:floors
			              
			            WHERE
			                id = :id";
			    // prepare query statement and execute
			   $report = $this->executional($query);
			   return $report;
			   

			}
			// delete the product
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

			// search products
			function search($keywords){
			 
			    // select all query
			    $query = "SELECT
			                name,id,location,size, floors,created,modified
			            FROM
			                " . $this->table_name . " 
			            WHERE
			                name LIKE ? OR location LIKE ? OR size LIKE ? 
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