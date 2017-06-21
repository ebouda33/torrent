<?php
namespace STANDARD\Logger;

use STANDARD\Fichier\Fichier;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logger
 *
 * @author xgld8274
 */
class Logger {
    //put your code here
    private $file = null;
    private $instance = null;
    
    const EMERG  = 0;
    const ALERT  = 1;
    const CRIT   = 2;
    const ERR    = 3;
    const WARN   = 4;
    const NOTICE = 5;
    const INFO   = 6;
    const DEBUG  = 7;
    
    
    
    
    public function __construct($path = null,$filename = "") {
        if(empty($path)){
            $path = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."log";
        }
        if(empty($filename)){
            
            $filename = "log.log";
        }
        
        $this->file = new Fichier($path,$filename);
        $this->file->initialize('a+');
        $this->instance = rand(1000,9999);
    }
    
    public function info($message){
        
        $this->write($message, self::INFO);
        
    }
    public function warn($message){
        
        $this->write($message, self::WARN);
        
    }
    public function error($message){
        
        $this->write($message, self::ERR);
        
    }
    public function debug($message){
        
        $this->write($message, self::DEBUG);
        
    }
    
    
    
    private function write($message,$niveau){
        $date = new \DateTime();
        $contenu = $date->format("Y-m-dTH:i:s");
        
        $contenu .= " ";
        
        if($niveau === self::EMERG){
            $contenu .= "EMERG";
        }
        if($niveau === self::ALERT){
            $contenu .= "ALERT";
        }
        if($niveau === self::CRIT){
            $contenu .= "CRIT";
        }
        if($niveau === self::ERR){
            $contenu .= "ERR";
        }
        if($niveau === self::WARN){
            $contenu .= "WARN";
        }
        if($niveau === self::NOTICE){
            $contenu .= "NOTICE";
        }
        if($niveau === self::INFO){
            $contenu .= "INFO";
        }
        if($niveau === self::DEBUG){
            $contenu .= "DEBUG";
        }
        $contenu .= " ";
        $contenu .= "(".$niveau.")" ;
        $contenu .= " ";
        $contenu .= $this->instance;
        $contenu .= " ";
        $contenu .=  $message;
        $contenu .= " ";
  
        $this->file->ecrireLigneDansFichier($contenu);
    }
}
