<?php

require_once "exceptions/ApplicationException.php";

class MySQL {

    # Establish a new connection to the Database
    public function connect()
    {
	$host = $_ENV['DB_HOST'];
	$port = $_ENV['DB_PORT'];
	$db = $_ENV['DB_NAME'];
	$username = $_ENV['DB_USER'];
	$password = $_ENV['DB_PASSWORD'];

        try 
        {
            $this->connection = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $username, $password);
         
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
