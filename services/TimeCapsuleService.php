<?php

require_once "dao/TimeCapsuleDao.php"; 
require_once "exceptions/ApplicationException.php";
require_once "Service.php";

 
class TimeCapsuleService extends Service {

    private $connection;
    private $db;
    private $collection;
 

    public function __construct() 
    {
        parent::__construct();
        
        try
        {
            // connect to mongodb
             $this->connection = new MongoClient();
        }
        catch (Exception $e)
        {
            echo 'Mongo Connection Failed ',  $e->getMessage(), "\n";
        }
        
        // select a database
        $this->db = $this->connection->selectDB("timecapsule");
        //$this->db = $this->connection->timecapsule;

        // select a collection
        $this->collection = $this->db->timecapsules;
    }

     
  public function getTimeCapsules($userId)
  {   
     $data = array();
 
    //$query = array('userId' => '6');
     $query['userId'] = intval($userId);
      
    $cursor =  $this->collection->find($query);
    
	 
    foreach ($cursor as $document)
    {
        array_push($data,$document);
    } 
   
     return $data; 
  }
    
  public function createTimeCapsule($item)
  {  
        //$item["date_created"] = date("d-m-Y h:i:sa");
        $this->collection->insert($item);
    
        return $item;
  }
    
  public function deleteTimeCapsule($id)
  {  
        $this->collection->remove(array('_id' => new MongoId($id)));
    
        return;
  }
    
 
  
 
    
}