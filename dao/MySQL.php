<?php

require_once "exceptions/ApplicationException.php";

class MySQL {

    private $connection = null;
    private $host = 'localhost';
    private $db = 'timecapsule';
    private $username = 'root';
    private $password = '';

    # Establish a new connection to the Database
    public function connect()
    {
        try 
        {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8", $this->username, $this->password);
         
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // make mysql driver not stringuly numeric values (does not affect decimals)
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->connection->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        } 
        catch (PDOException $e)
        {
            throw new ApplicationException("Connection with MySQL failed. " . $e->getMessage(), 500);
        }
    }

    # Close the connection
    public function close()
    {
        $this->connection = null;
    }

    # Return the current connection. 
    public function getConnection()
    {
        return $this->connection;
    }

}
