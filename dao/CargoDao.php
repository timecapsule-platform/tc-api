<?php

require_once "dao/Dao.php";
require_once "exceptions/ApplicationException.php";

class CargoDao extends Dao {

    public static $TABLE_NAME = "location";
    public static $ALLOWED_FILTERS = array("id", "limit", "offset"
    );
    private $connection;

    public function __construct($conn) {
        parent::__construct();
        $this->connection = $conn;
    }

    /**
     * Creates a new drug
     * */
    public function createCargo($name) {

        $queryString = "
            INSERT INTO 
            cargo  (name)
            VALUES
            (:name)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }

   
  
 
     
      
  
    public function getAll($searchText) {

         $queryString = "
            SELECT name, type
            FROM `cargo`
            WHERE name LIKE '".$searchText."%'
            ORDER BY name ASC
            LIMIT 200
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }

     

}
