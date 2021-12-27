<?php

require_once "dao/Dao.php";
require_once "exceptions/ApplicationException.php";

class DrugDao extends Dao {

    public static $TABLE_NAME = "drug";
    public static $ALLOWED_FILTERS = array("id", "limit", "offset"
    );
    private $connection;

    public function __construct($conn) {
        parent::__construct();
        $this->connection = $conn;
    }

    /**
     * Creates a new drug
     * */
    public function createDrug($name,$drug) {

        $queryString = "
            INSERT INTO 
            drug  (name, drug)
            VALUES
            (:name, :drug)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":name", $name, PDO::PARAM_STR);
        $query->bindValue(":drug", $drug, PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }

   
  
 
     
         
     public function getDrugOfVariant($variant) 
     {

         $queryString = "
            SELECT drug drugName
            FROM `drug`
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
            SELECT name, drug drugName
            FROM `drug`
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
