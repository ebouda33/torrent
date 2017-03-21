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
    private static $SEARCH = 'search';
    
    
    public static function returnResponse($file,$query){
        
        $retour =array('success'=>false,'data'=>null);
        if(strripos($query,self::$SEARCH) !== false){
            //on execute la recherche sur tout les plugin demandé
            $jsonPlug = filter_input(INPUT_GET, 'plugins');
            $search = filter_input(INPUT_GET, 'search');
            if(!is_null($jsonPlug) && $jsonPlug !== false && !is_null($search) && $search !== false){
                $plugins = json_decode($jsonPlug);
                foreach($plugins as $plugin){
                    $classname = \Parser\plugin\torrent\PluginGenerique::getPluginClassName($plugin->id);
                }
                $torrent = new $classname(new \config\ConfigReader($file));
                $torrent->search($search);
                $retour['success'] =true ;
                $retour['data']=$torrent->getResult();
                $retour['totalCount'] = count($torrent->getResult());
            }
            
        }else if(strripos($query,self::$PLUGIN) !== false){
            $plugins = \Parser\plugin\torrent\PluginGenerique::getListe();
            $retour['success'] =true ;
            $retour['data']=$plugins;
        }
        return json_encode($retour);
    }
    
    
}
