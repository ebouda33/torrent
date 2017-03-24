<?php
namespace Standard\Fichier;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReaderIniException extends \Exception{}

class ReaderIni{
    
    /**
     * 
     * @param string $file string fullpath to ini file
     * @return object 
     */
    static public function read($file='config_file_cft.conf'){
//        return json_decode(json_encode(parse_ini_file($file,true)));
        $ini =  parse_ini_file($file,true);
        //surcharge conf
        if(isset($ini['PROD']['CFT'])){
            $ini['CFT'] = $ini['PROD']['CFT'];
        }


        if(isset($ini['PROD']['IN'])){
            $ini['IN'] = $ini['PROD']['IN'];
        }
        
        return $ini;
    }
}
