<?php
header('Content-Type: text/html; charset=utf-8');

require_once "dao/PlantDao.php"; 
require_once "exceptions/ApplicationException.php";
require_once "Service.php";
 

 
class PlantService extends Service {

    private $dao;
 

    public function __construct() {
        parent::__construct();
        
        $this->dao = new PlantDao($this->mysql->getConnection());
    }

     
  public function getPlants($params)
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
    
  
  public function getPlant($variant)
  { 
      
      
      $item  = $this->dao->getPlantOfVariant($variant);
      
      return $item;
  }    
    
    
    
  public function XMLtoJSON()
  {
       
      
      $dir = "data/rdf/";
      $files = scandir($dir, 0);
      $size = count($files);
      $count = 0;
      
      for($j=0; $j<$size; $j++)
      {
          if($files[$j] == "." || $files[$j] == "..")
          {
              continue;
          }
          
          echo $files[$j]."\n";
 
          
          $xml =  file_get_contents($dir.$files[$j]);
          //mb_convert_encoding($xml, 'HTML-ENTITIES', "UTF-8");
          echo mb_detect_encoding($xml);
          //echo $xml;
 
          $placeTag = "gn:name";
          preg_match_all("@<$placeTag(?:\s.*?!/)?>(.*?)</$placeTag\s*>@s", $xml, $place); 

          $latitudeTag = "wgs84_pos:lat";
          preg_match_all("@<$latitudeTag(?:\s.*?!/)?>(.*?)</$latitudeTag\s*>@s", $xml, $lat);

          $longtitudeTag = "wgs84_pos:long";
          preg_match_all("@<$longtitudeTag(?:\s.*?!/)?>(.*?)</$longtitudeTag\s*>@s", $xml, $long);

          for($i=0;$i<count($place[1]);$i++)
          {
              $data[$count]["location"] = utf8_encode($place[1][$i]);
              $data[$count]["latitude"] = number_format(floatval($lat[1][$i]), 6, '.', '');
              $data[$count]["longtitude"] = number_format(floatval($long[1][$i]), 6, '.', '');
 
               echo $data[$count]["location"]." ".$data[$count]["latitude"]." ".$data[$count]["longtitude"]; 
               echo "\n";
          }
          
          //echo json_encode($data);
 
          
      }
      
      
      
      
 
  }
    
    
   
  public function create()
  {
      
      
        //$this->dao->create();
       
       // $plants = new SpreadsheetReader("data/test.xlsx");
         
      
      
      /*
      $raw = json_decode(file_get_contents("data/plants-ecob-snippendaal-thesaurus-raw.json"),  true);
      
      $raw = $raw["results"]["bindings"];
      
      $size = count($raw);
       
       for($i=0; $i<$size; $i++)
       {
            $data[$i]["name"] = $raw[$i]["name"]["value"];
            $data[$i]["plant"] = $raw[$i]["plant"]["value"];    
       }
      
      echo json_encode($data);
      */
}    
    
       
    
  public function createPlantsFromJson()
  {
      // $data = json_decode(file_get_contents("data/thesaurus-plants.json"),  true);
      // $data = json_decode(file_get_contents("data/snipendal-plants.json"),  true);
     // $data = json_decode(file_get_contents("data/ecob-plants.json"),  true);
       $data = json_decode(file_get_contents("data/plants-ecob-snippendaal-thesaurus.json"),  true);
      
      $size = count($data);
     
     
      for($i=0; $i<$size; $i++)
      {
           try
           {
           
          // $this->dao->createPlant($data[$i]["name"],$data[$i]["scientificName"],"thesaurus");
          // $this->dao->createPlant($data[$i]["name"],$data[$i]["scientificName"],"snippendaal");
          // $this->dao->createPlant($data[$i]["name"],$data[$i]["scientificName"],"ecob");
               $this->dao->createPlant($data[$i]["name"],$data[$i]["plant"]);
          }
          catch(Exception $e){ continue; }
          
           
      } 
  }
    
}
