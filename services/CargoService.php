<?php

require_once "dao/CargoDao.php"; 
require_once "exceptions/ApplicationException.php";
require_once "Service.php";

 
class CargoService extends Service {

    private $dao;
 
    public function __construct() {
        parent::__construct();
        
        $this->dao = new CargoDao($this->mysql->getConnection());
    }

     
  public function getCargo($params)
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
      $raw = json_decode(file_get_contents("data/cargo-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["cargo"]["value"];  
       }
      
      echo json_encode($data);
  }    
    
       
    
  public function createFromJson()
  { 
      $data = json_decode(file_get_contents("data/cargo.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {
        
               $this->dao->createCargo($data[$i]["name"]);
          }
          catch(Exception $e){ continue; }
          
           
      } 
  }
    
}
