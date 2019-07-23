<?php


	class Database{
			//database credentials
			private $host="localhost";
			private $db_name ="building-guards";
			private $password="password";
			private $username = "root";
			public $conn;
			//get database connection

			public function getConnection(){
				$this->conn = null;
				try{
					$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" .$this->db_name, $this->username,$this->password);
					$this->conn->exec("set names utf8");

				}catch(PDOException $exception){
					echo "connection error: " . $exception->getMessage();
				}
				return $this->conn;
			}

		}


?>