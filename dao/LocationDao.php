<?php

require_once "dao/Dao.php";
require_once "exceptions/ApplicationException.php";

class LocationDao extends Dao {

    public static $TABLE_NAME = "location";
    public static $ALLOWED_FILTERS = array("id", "limit", "offset"
    );
    private $connection;

    public function __construct($conn) {
        parent::__construct();
        $this->connection = $conn;
    }
    
    
    
    
    /**
     * Create Locations
     * */
    public function createLocationsTransaction() 
    { 
        $this->connection->beginTransaction();
         
        $raw = json_decode(file_get_contents("data/locations-raw.json"),  true);
  

        $raw = $raw["results"]["bindings"];

        $size = count($raw);
 
        for($i=0; $i<$size; $i++)
        {
            $name = $raw[$i]["name"]["value"];
            
            try
            {
                $this->createLocation($name);
            }
            catch(Exception $e){ continue; }
             
        }
 
        $result = $this->connection->commit();
        
        return $result;
        
    }
    
    
    

    /**
     * Creates a new drug
     * */
    public function createLocation($name) {

        $queryString = "
            INSERT INTO 
            location  (name)
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
            SELECT name
            FROM `location`
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
