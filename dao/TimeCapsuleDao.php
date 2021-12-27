<?php

require_once "dao/Dao.php";
require_once "exceptions/ApplicationException.php";

class TimeCapsuleDao extends Dao {

    public static $TABLE_NAME = "timecapsules";
    public static $ALLOWED_FILTERS = array("id", "limit", "offset"
    );
    private $connection;

    public function __construct($conn) {
        parent::__construct();
        $this->connection = $conn;
    }

    /**
     * Creates a new time capsule
     * */
    public function createTimeCapsule($timecapsule) {

        $queryString = "
            INSERT INTO 
            timecapsule  (name,description,jsonQuery,jsonCompare,user_id)
            VALUES
            (:name, :description,:jsonQuery,:jsonCompare,:user_id)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $timecapsule["name"], PDO::PARAM_STR);
        $query->bindValue(":description", $timecapsule["description"], PDO::PARAM_STR);
        $query->bindValue(":jsonQuery", $timecapsule["jsonQuery"], PDO::PARAM_STR);
        $query->bindValue(":jsonCompare", $timecapsule["jsonCompare"], PDO::PARAM_STR);
        $query->bindValue(":user_id", $timecapsule["userId"], PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }

   
  
 
    
  
    public function getAll() {

         $queryString = "
            SELECT id, name, description, jsonQuery, jsonCompare, user_id
            FROM `timecapsule`
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }

     

}
