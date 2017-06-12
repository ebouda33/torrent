<?php
namespace config;
ini_set("auto_detect_line_endings", true);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Exception;
use Standard\Fichier\Fichier;

/**
 * Description of ConfigReader
 *
 * @author xgld8274
 */
class ConfigReader {
   //put your code here
    private $config = array();
    private $file ;
   
    public function __construct(Fichier $file) {
        $this->file = $file;
        $this->config = array();
        
        
        $this->getConfig();
    }
    
    public function read(){
        $file = $this->file;
       if(!$file->presenceFichier()){
                throw new Exception('Aucun fichier de config présent...');
        }
        $file->initialize();
       while(($ligne = $file->lireLigneCouranteCsv(":")) !== Fichier::finFichier){
           for($i=2;$i < count($ligne);$i++){
                
                 $ligne[1] .= ':'.$ligne[$i];
            }
           if(strripos($ligne[0],'[]') !== false){
                $ligne[0] = str_replace('[]', '', $ligne[0]);
               if(!isset($this->config[$ligne[0]])){
                    $this->config[$ligne[0]] = array();
                }
            }
            if(count($ligne) >= 2){
                if(isset($this->config[$ligne[0]]) && is_array($this->config[$ligne[0]])){
                    $this->config[$ligne[0]][] = $ligne[1];
                }else{
                    $this->config[$ligne[0]] = $ligne[1];
                }
            }
        }
        if(count($this->config) === 0){
            throw new Exception('Configuration erroné controle votre fichier.');
        }

    }
    
    public function __get($name){
        if(count($this->config) === 0){
            return null;
       }
        if(isset($this->config[$name])){
            return $this->config[$name];
        }
        
        return null;
    }
    
    public function getConfig(){
        if(count($this->config) === 0){
            $this->read();
        }
        return $this->config;
    }
    
    public function configFileIsCrypted(){
        $file = $this->file;
        $crypt = false;
        try{
            $this->read($file);
            $this->config = array();
        } catch (Exception $ex) {
            echo $ex;
            $crypt = true;
        }
        
        return $crypt;
        
    }
    
    
}
