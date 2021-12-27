<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

# Require the MsSQL class
 require_once "dao/MySQL.php";

/**
 * Description of Service
 *
 * @author apreventis
 */
abstract class Service {
    
    protected $mysql;
            
    public function __construct() {
        $this->mysql = new MySQL();
        $this->mysql->connect();
    }

}
