<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myClass;

/**
 * Description of ResponseJson
 *
 * @author xgld8274
 */
class ResponseJson {
    //put your code here
    private static $PLUGIN = 'plugin';
    
    
    
    public static function returnResponse($query){
        
        $retour =array();
        if(strripos($query,self::$PLUGIN) !== false){
            $plugins = \Parser\plugin\torrent\TorrentGenerique::getListe();
            array_push($retour , array('success'=>true,'data'=>$plugins));
            
        }
        
        return json_encode($retour);
    }
}
