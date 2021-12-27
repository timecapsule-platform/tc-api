<?php

/*
 * SpeCtre
 * Alexandros Preventis
 * Feb 12, 2016
 */

/**
 * Description of Logger
 *
 * @author apreventis
 */
class Logger{
    
    private static $LOG_FILE = "mega_labor.log";
    
    public function __construct() {
        
    }

    
    public function __invoke($message) {
        $this->write($message);
    }
    
    public function write($message){
        $this->log = fopen(static::$LOG_FILE, "a");
        fwrite($this->log, date("Y-m-d H:i:s") . "\t" . $message . "\n");
        fclose($this->log);
    }
}
