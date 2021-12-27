<?php

require_once "dao/Dao.php";
require_once "exceptions/ApplicationException.php";

 
 

class PlantDao extends Dao {

    public static $TABLE_NAME = "plant";
    public static $ALLOWED_FILTERS = array("id", "limit", "offset"
    );
    private $connection;

    public function __construct($conn) {
        parent::__construct();
        $this->connection = $conn;
    }

    
    
    /**
     * Creates a new plant
     * */
    public function createExcel() 
    {

        
       $this->connection->beginTransaction();
        
        
        $plants = new SpreadsheetReader("data/plants.xlsx");
         
        $counter = 0;
        
        foreach ($plants as $plant)
        {
            if($counter > 5)
                break;
            print_r($plant);
        }
         
        for($i=0; $i<$size; $i++)
        {
            $name = $raw[$i]["name"]["value"];
            $plant = $raw[$i]["plant"]["value"];    
            
            try
            {
                $this->createPlant($name,$plant);
            }
            catch(Exception $e){ continue; }
             
        }
         
       
        $result = $this->connection->commit();
        
        
        return $result;
        
    }
    
    
    
    /**
     * Creates a new plant
     * */
    public function create() 
    {

        $plants = new SpreadsheetReader("data/plantsZ.xlsx");
        
        return (count($plants);
        
          
        
        /*
       $this->connection->beginTransaction();
        
        
         //done $raw = json_decode(file_get_contents("data/plants-raw-0-50000.json"),  true);
          $raw = json_decode(file_get_contents("data/plants-50-75.json"),  true);
        //$raw = json_decode(file_get_contents("data/plants-raw-100000-150000.json"),  true);
        // $raw = json_decode(file_get_contents("data/plants-raw-150000-200000.json"),  true);
        //done  $raw = json_decode(file_get_contents("data/plants-raw-200000-250000.json"),  true);
         
        
        $raw = $raw["results"]["bindings"];

        $size = count($raw);
         
        for($i=0; $i<$size; $i++)
        {
            $name = $raw[$i]["name"]["value"];
            $plant = $raw[$i]["plant"]["value"];    
            
            try
            {
                $this->createPlant($name,$plant);
            }
            catch(Exception $e){ continue; }
             
        }
         
       
        $result = $this->connection->commit();
        
        
        return $result;
        
        */
    }
    
    
    /**
     * Creates a new plant
     * */
    public function createPlant($name,$plant) {

        $queryString = "
            INSERT INTO 
            plant  (name, plant)
            VALUES
            (:name, :plant)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->bindValue(":plant", $plant, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }

   
  
 
     
         
     public function getPlantOfVariant($variant) 
     {

         $queryString = "
            SELECT plant plantName
            FROM `plant`
            WHERE name = :variant
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":variant", $variant, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
  
    public function getAll($searchText) {

         $queryString = "
            SELECT name, plant plantName
            FROM `plant`
            WHERE name LIKE '".$searchText."%'
            LIMIT 200
        ";

        $query = $this->connection->prepare($queryString);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
 
        return $result;
    }

     

}
