<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myClass;

use config\ConfigReader;
use Parser\plugin\torrent\PluginGenerique;
use Transmission\TransmissionRPC;

/**
 * Description of ResponseJson
 *
 * @author xgld8274
 */
class ResponseJson {
    //put your code here
    private static $PLUGIN = 'plugin';
    private static $SEARCH = 'search';
    private static $TRANSMISSION = 'transmission';
    
    
    public static function returnResponse($file,$query){
        $proxy = false;
        $config = new ConfigReader($file);
        $config = $config->getConfig();
       
        if(!empty($config['proxy_url'])){
            $proxy = true;
        }
        $retour =array('success'=>false,'data'=>null,'message'=>'Erreur de traitement');
        if(strripos($query,self::$TRANSMISSION) !== false){
            
            $url = filter_input(INPUT_GET, self::$TRANSMISSION);
            if(!is_null($url) && $url !== false){
//                $url = json_decode($url);
                if(stripos('magnet',$url)>= 0){
                   $url = str_replace('@', '&tr', $url) ;
                   try{
                    $transmission = new TransmissionRPC($config['transmission_url'], $config['transmission_user'], $config['transmission_password'],$proxy);
                    $result =  $transmission->add($url,'/mnt/data/videos/adulte');
                    $id = $result->arguments->torrent_added->id;
                    $retour['success'] =true ;
                    $retour['message']= "tansmission ok=>".$id;  
                   }catch(\Transmission\TransmissionRPCException $exc){
                       $retour['message']= "tansmission en erreur ".$exc->getMessage();  
                   }
//                   $id = 0;
                }else{
                    $retour['message']= 'Erreur Ce n\'est pas un magnet.';  
                }
                    
            }else{
                $retour['message']= 'Erreur dans le lien '.$url;  
            }
            
            
            
            
            
            
        }else if(strripos($query,self::$SEARCH) !== false){
            //on execute la recherche sur tout les plugin demandé
            $jsonPlug = filter_input(INPUT_GET, 'plugins');
            $search = filter_input(INPUT_GET, self::$SEARCH);
            if(!is_null($jsonPlug) && $jsonPlug !== false && !is_null($search) && $search !== false){
                $plugins = json_decode($jsonPlug);
                foreach($plugins as $plugin){
                    $classname = PluginGenerique::getPluginClassName($plugin->id);
                }
                $torrent = new $classname(new ConfigReader($file));
                $torrent->search($search);
                $retour['success'] =true ;
                $retour['data']=$torrent->getResult();
                $retour['totalCount'] = count($torrent->getResult());
            }
            
        }else if(strripos($query,self::$PLUGIN) !== false){
            $plugins = PluginGenerique::getListe();
            $retour['success'] =true ;
            $retour['data']=$plugins;
        }
        return json_encode($retour);
    }
    
    
}
