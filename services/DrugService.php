<?php

require_once "dao/DrugDao.php"; 
require_once "exceptions/ApplicationException.php";
require_once "Service.php";

 
class DrugService extends Service {

    private $dao;
 

    public function __construct() {
        parent::__construct();
        
        $this->dao = new DrugDao($this->mysql->getConnection());
    }

     
  public function getDrugs($params)
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
    
  
  public function getDrug($variant)
  { 
      
      
      $item  = $this->dao->getDrugOfVariant($variant);
      
      return $item;
  }    
    
    
    
    
   
  public function rawToJson()
  {
      $raw = json_decode(file_get_contents("data/drugs-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["name"]["value"];
            $data[$i]["drug"] = $raw[$i]["drug"]["value"];    
       }
      
      echo json_encode($data);
  }    
    
       
    
  public function createDrugsFromJson()
  { 
       $data = json_decode(file_get_contents("data/drugs.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {
        
               $this->dao->createDrug($data[$i]["name"],$data[$i]["drug"]);
          }
          catch(Exception $e){ continue; }
          
           
      } 
  }
    
}
