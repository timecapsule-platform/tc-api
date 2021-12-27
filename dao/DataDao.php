<?php

require_once "dao/Dao.php";
require_once "exceptions/ApplicationException.php";

class DataDao extends Dao {

    public static $TABLE_NAME = "data";
    public static $ALLOWED_FILTERS = array("id", "limit", "offset"
    );
    private $connection;

    public function __construct($conn) {
        parent::__construct();
        $this->connection = $conn;
    }

    
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////     SOURCE LOCATION     ////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    
   
    public function createSourceLocation($name) {

        $queryString = "
            INSERT INTO 
            slocation  (name)
            VALUES
            (:name)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }
 
    public function getSourceLocations($searchText) {

         $queryString = "
            SELECT name
            FROM `slocation`
            WHERE name LIKE '".$searchText."%'
            ORDER BY name ASC
            LIMIT 200
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }
    
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////     PLANT FAMILY     ////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    public function createFamily($name) {

        $queryString = "
            INSERT INTO 
            family  (name)
            VALUES
            (:name)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }
 
    public function getFamilies($searchText) {

         $queryString = "
            SELECT name
            FROM `family`
            WHERE name LIKE '".$searchText."%'
            ORDER BY name ASC
            LIMIT 200
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }
    
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////     PLANT PART       ////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * Creates a new plant part
     * */
    public function createPlantPart($name) {

        $queryString = "
            INSERT INTO 
            ppart  (name)
            VALUES
            (:name)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }
 
    public function getPlantParts($searchText) {

         $queryString = "
            SELECT name
            FROM `ppart`
            WHERE name LIKE '".$searchText."%'
            ORDER BY name ASC
            LIMIT 200
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////      REFERENCE SOURCES        ////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * Creates a new source
     * */
    public function createSource($name) {

        $queryString = "
            INSERT INTO 
            source  (name)
            VALUES
            (:name)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }
 
    public function getSources($searchText) {

         $queryString = "
            SELECT name
            FROM `source`
            WHERE name LIKE '".$searchText."%'
            ORDER BY name ASC
            LIMIT 200
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }
    
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////       DEPARTURES         ////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * Creates a new ship
     * */
    public function createDeparture($name) {

        $queryString = "
            INSERT INTO 
            departure  (name)
            VALUES
            (:name)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }
 
    public function getDepartures($searchText) {

         $queryString = "
            SELECT name
            FROM `departure`
            WHERE name LIKE '".$searchText."%'
            ORDER BY name ASC
            LIMIT 200
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////      ARRIVALS           ////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * Creates a new ship
     * */
    public function createArrival($name) {

        $queryString = "
            INSERT INTO 
            arrival  (name)
            VALUES
            (:name)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }
 
    public function getArrivals($searchText) {

         $queryString = "
            SELECT name
            FROM `arrival`
            WHERE name LIKE '".$searchText."%'
            ORDER BY name ASC
            LIMIT 200
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }
    
    
    
    
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////       SHIPS            ////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    
    /**
     * Creates a new ship
     * */
    public function createShip($name) {

        $queryString = "
            INSERT INTO 
            ship  (name)
            VALUES
            (:name)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }
 
    public function getShips($searchText) {

         $queryString = "
            SELECT name
            FROM `ship`
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
