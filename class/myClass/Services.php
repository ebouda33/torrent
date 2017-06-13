<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myClass;

use Exception;
use model\UsersTable;
use Standard\crypt\OpenSSLClass;
use Standard\Fichier\Fichier;
use Standard\Fichier\ReaderIni;

/**
 * Description of Services
 *
 * @author eric
 */
class Services {
    //put your code here
    //determiner l environnement
    public static function getEnvironnement(){
        return 'prod';
        
    }
    public static function authentification($user,$pwd){
        $env = self::getEnvironnement();
        $table = new UsersTable(new MyBDD($env));
        $token = $table->authentification($user, $pwd);
        return $token;
    }
    
    public static function getEmail($token){
        $env = self::getEnvironnement();
        $table = new UsersTable(new MyBDD($env));
        $email = $table->getEmail($token);
        return $email;
    }
    
    
    public static function saveSetings($email,$liste){
        $path = self::getPath();
        $filename = $email.'.ini';
        $fileread = $path.$filename;
        $fileoutput = $fileread.'.enc';
        $keyfile = $path.$email.'.key';
        
        if(file_exists($keyfile)){
            Fichier::_effaceFichier($path.$filename);
            Fichier::_effaceFichier($fileoutput);
            Fichier::_effaceFichier($keyfile);
        }
        try{
            self::genereFichier($path, $filename,$liste);
        
            return self::cryptSettings($fileread,$keyfile,$fileoutput);
            
        }catch(Exception $exc){
            return false;
        }
    }
    
    public static function loadSettings($token){
        $email = self::getEmail($token);
        $path = self::getPath();
        $keyfile = $path.$email.'.key';
        $filename = $email.'.ini';
        $fileread = $path.$filename;
        $filecrypt = $fileread.'.enc';
        if(file_exists($keyfile) && file_exists($filecrypt)){
            $decrypt = self::decryptSettings($filecrypt, $keyfile, $fileread);
            if($decrypt){
                $data = ReaderIni::read($fileread);
//                \Standard\Fichier\Fichier::_effacerFichier($fileread);
                return $data;
            }
        }
        return array();
    }

    private static function  genereFichier($path,$filename,$liste){
        $fichier = new Fichier($path,$filename);
        $refus = array('_dc','token','action');
        foreach ($liste as $key=>$value) {
            if(!in_array($key, $refus) ){
                if(!empty($value)){
                    $fichier->ecrireLigneDansFichier($key.' = \''.$value.'\'', true);
                }
            
            }
        }
        
        $fichier->finalize();
    }
    
    private static function decryptSettings($filecrypt,$keyfile,$fileoutput){
        try{
            
            if(file_exists($fileoutput)){
                Fichier::_effaceFichier($fileoutput);
            }
            $error = OpenSSLClass::decryptFile($filecrypt, $keyfile, $fileoutput);
        }catch(Exception $exc){
            $error = true;
        }
        return $error !== false ?true:false;
    }
    
    private static function cryptSettings($fileread,$keyfile,$fileoutput){
        
        if(!file_exists($keyfile)){
            OpenSSLClass::generateKeyPrivateFile($keyfile);
        }
        $error = OpenSSLClass::cryptFile($fileread, $keyfile, $fileoutput);
        if($error !== false){
//            Fichier::_effaceFichier($fileread);
        }
        
        return $error !== false ?true:false;
    }
    
    
    protected static function getPath(){
        return dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR;
    }
}
