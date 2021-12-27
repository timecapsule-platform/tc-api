<?php

/*
 * SpeCtre
 * Alexandros Preventis
 * Feb 5, 2016
 */

/**
 * Description of ApplicationException
 *
 * @author apreventis
 */
class ApplicationException extends Exception{
    
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

}
