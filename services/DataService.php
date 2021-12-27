<?php

require_once "dao/DataDao.php"; 
require_once "exceptions/ApplicationException.php";
require_once "Service.php";

 
class DataService extends Service {

    private $dao;
 
    public function __construct() {
        parent::__construct();
        
        $this->dao = new DataDao($this->mysql->getConnection());
    }

    
    

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////      SOURCE LOCATIONS     ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////   
    
  public function getSourceLocations($params)
  { 
      if(!isset($params) || !isset($params["search"]))
      {
          $searchText = "";
      }
      else
      {
          $searchText = $params["search"];
      }
      
      
      $items = $this->dao->getSourceLocations($searchText);
      
      return $items;
  }
    
   
  public function sourceLocationsRawToJson()
  {
      $raw = json_decode(file_get_contents("data/source-locations-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["name"]["value"];
       }
      
      echo json_encode($data);
  }    
    
     
    
  public function sourceLocationsCreateFromJson()
  { 
      $data = json_decode(file_get_contents("data/source-locations.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {   
               $this->dao->createSourceLocation($data[$i]["name"]);
           }
          catch(Exception $e){ continue; }
            
      } 
  }      
    
    
    
    
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////      FAMILIES       ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////   
    
  public function getFamilies($params)
  { 
      if(!isset($params) || !isset($params["search"]))
      {
          $searchText = "";
      }
      else
      {
          $searchText = $params["search"];
      }
      
      
      $items = $this->dao->getFamilies($searchText);
      
      return $items;
  }
    
   
  public function familiesRawToJson()
  {
      $raw = json_decode(file_get_contents("data/families-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["name"]["value"];
       }
      
      echo json_encode($data);
  }    
    
     
    
  public function familiesCreateFromJson()
  { 
      $data = json_decode(file_get_contents("data/families.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {   
               $this->dao->createFamily($data[$i]["name"]);
           }
          catch(Exception $e){ continue; }
            
      } 
  }      
    
    
    
    
    

  /////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////      Plant Parts        ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////   
    
  public function getPlantParts($params)
  { 
      if(!isset($params) || !isset($params["search"]))
      {
          $searchText = "";
      }
      else
      {
          $searchText = $params["search"];
      }
      
      
      $items = $this->dao->getPlantParts($searchText);
      
      return $items;
  }
    
   
  public function plantPartsRawToJson()
  {
      $raw = json_decode(file_get_contents("data/plant-parts-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["part"]["value"];
       }
      
      echo json_encode($data);
  }    
    
     
    
  public function plantPartsCreateFromJson()
  { 
      $data = json_decode(file_get_contents("data/plant-parts.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {   
               $this->dao->createPlantPart($data[$i]["name"]);
           }
          catch(Exception $e){ continue; }
            
      } 
  }      
        
    
    
    
    
    
    
 /////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////      REFERENCE SOURCES        ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////   
    
  public function getSources($params)
  { 
      if(!isset($params) || !isset($params["search"]))
      {
          $searchText = "";
      }
      else
      {
          $searchText = $params["search"];
      }
      
      
      $items = $this->dao->getSources($searchText);
      
      return $items;
  }
    
   
  public function sourcesRawToJson()
  {
      $raw = json_decode(file_get_contents("data/sources-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["source"]["value"];
       }
      
      echo json_encode($data);
  }    
    
     
    
  public function sourcesCreateFromJson()
  { 
      $data = json_decode(file_get_contents("data/sources.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {   
               $this->dao->createSource($data[$i]["name"]);
           }
          catch(Exception $e){ continue; }
            
      } 
  }      
        
    
    
    
  
    
  /////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       DEPARTURES         ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////   
    
  public function getDepartures($params)
  { 
      if(!isset($params) || !isset($params["search"]))
      {
          $searchText = "";
      }
      else
      {
          $searchText = $params["search"];
      }
      
      
      $items = $this->dao->getDepartures($searchText);
      
      return $items;
  }
    
   
  public function departuresRawToJson()
  {
      $raw = json_decode(file_get_contents("data/departures-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["departure"]["value"];
       }
      
      echo json_encode($data);
  }    
    
     
    
  public function departuresCreateFromJson()
  { 
      $data = json_decode(file_get_contents("data/departures.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {   
               $this->dao->createDeparture($data[$i]["name"]);
           }
          catch(Exception $e){ continue; }
            
      } 
  }      
    
    
    
 /////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       Arrivals            ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////   
    
  public function getArrivals($params)
  { 
      if(!isset($params) || !isset($params["search"]))
      {
          $searchText = "";
      }
      else
      {
          $searchText = $params["search"];
      }
      
      
      $items = $this->dao->getArrivals($searchText);
      
      return $items;
  }
    
   
  public function arrivalsRawToJson()
  {
      $raw = json_decode(file_get_contents("data/arrivals-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["arrival"]["value"];
       }
      
      echo json_encode($data);
  }    
    
     
    
  public function arrivalsCreateFromJson()
  { 
      $data = json_decode(file_get_contents("data/arrivals.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {   
               $this->dao->createArrival($data[$i]["name"]);
           }
          catch(Exception $e){ continue; }
            
      } 
  }  
    
    
 /////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       SHIPS            ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////   
    
  public function getShips($params)
  {  
      if(!isset($params) || !isset($params["search"]))
      {
          $searchText = "";
      }
      else
      {
          $searchText = $params["search"];
      }
      
      
      $items = $this->dao->getShips($searchText);
      
      return $items;
  }
    
   
  public function shipsRawToJson()
  {
      $raw = json_decode(file_get_contents("data/ships-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["ship"]["value"];
       }
      
      echo json_encode($data);
  }    
    
     
    
  public function shipsCreateFromJson()
  { 
      $data = json_decode(file_get_contents("data/ships.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {   
               $this->dao->createShip($data[$i]["name"]);
           }
          catch(Exception $e){ continue; }
          
           
      } 
  }
    
}
