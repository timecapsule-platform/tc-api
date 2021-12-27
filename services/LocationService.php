<?php

require_once "dao/LocationDao.php"; 
require_once "exceptions/ApplicationException.php";
require_once "Service.php";

 
class LocationService extends Service {

    private $dao;
 

    public function __construct() {
        parent::__construct();
        
        $this->dao = new LocationDao($this->mysql->getConnection());
    }

     
  public function getLocations($params)
  { 
      if(!isset($params) || !isset($params["search"]))
      {
          $searchText = "";
      }
      else
      {
          $searchText = $params["search"];
      }
      
      
      $items = $this->dao->getAll($searchText);
      
      return $items;
  }
    
   
    
   
  public function rawToJson()
  {
      
      $this->dao->createLocationsTransaction();
      /*
      $raw = json_decode(file_get_contents("data/drugs-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["name"]["value"];
            $data[$i]["drug"] = $raw[$i]["drug"]["value"];    
       }
      
      echo json_encode($data);
      */
  }    
    
       
    
  public function createFromJson()
  { 
      $data = json_decode(file_get_contents("data/location-data.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {
        
               $this->dao->createLocation($data[$i]["name"]);
          }
          catch(Exception $e){ continue; }
          
           
      } 
  }
    
}
