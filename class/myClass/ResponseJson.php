<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myClass;

use config\ConfigReader;
use Parser\plugin\torrent\PluginException;
use Parser\plugin\torrent\PluginGenerique;
use Parser\plugin\torrent\PluginListeResults;
use Transmission\TransmissionRPC;
use Transmission\TransmissionRPCException;

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
    private static $LOGIN = 'login';
    
    
    public static function returnResponse($file,$query){
        $retour =array('success'=>false,'data'=>null,'message'=>'Erreur de traitement');
        try{
            $configR = new ConfigReader($file);
            if(strripos($query,self::$LOGIN) !== false){
                $retour = self::toLogin($configR,$retour);
            }else if(strripos($query,self::$TRANSMISSION) !== false){
                $retour = self::toTransmission($configR,$retour);
            }else if(strripos($query,self::$SEARCH) !== false){
                $retour = self::toSearch($configR,$retour);
            }else if(strripos($query,self::$PLUGIN) !== false){
                $retour = self::toPlugin($retour);
            }
        }catch(PluginException $exc){
            $retour['message'] = $exc->getMessage();
        }
        return json_encode($retour);
    }
    
    
    
    private static function toLogin($config,$retour){
        
        $url = filter_input(INPUT_GET, self::$LOGIN);
        if(!is_null($url) && $url !== false){
            //Authentification systeme
            $user = filter_input(INPUT_GET, 'username');
            $pwd = filter_input(INPUT_GET, 'password');
            $res = Services::authentification($user, $pwd);
            if(!empty($res) && count($res)=== 2){
                $retour['success'] = true;
                $retour['data'] = $res['token'];
                $retour['name'] = $res['name'];
                $retour['message'] = '';
                
            }
        }else{
            $retour['message']= 'Erreur dans le lien '.$url;  
        }

        return$retour;

    }
    
    private static function toTransmission($config,$retour){
        $url = filter_input(INPUT_GET, self::$TRANSMISSION);
        if(!is_null($url) && $url !== false){
//                $url = json_decode($url);
            if(stripos('magnet',$url)>= 0){
                $urlM = str_replace('@', '&tr', $url) ;
                $retour = self::toMagnet($config, $urlM, $retour);
            }else{
                $retour['message']= 'Erreur Ce n\'est pas un magnet.'; 
            }
            

        }else{
            $retour['message']= 'Erreur dans le lien '.$url;  
        }

        return$retour;

    }
    
    private static function toMagnet(ConfigReader $reader,$url,$retour){
        $config = $reader->getConfig();
        $proxy = false;
        if(!empty($config['proxy_url'])){
            $proxy = true;
        }
        try{
            $transmission = new TransmissionRPC($config['transmission_url'], $config['transmission_user'], $config['transmission_password'],$proxy);
            $result =  $transmission->add($url,'/mnt/data/videos/adulte');
            $id = 0;
            if(isset($result->arguments->torrent_duplicate)){
                $id = $result->arguments->torrent_duplicate->id;
                $retour['duplicate'] = true;
            }else{
                $id = $result->arguments->torrent_added->id;
            }
            $retour['success'] =true ;
            $retour['message']= "tansmission ok=>".$id;  
        }catch(TransmissionRPCException $exc){
            $retour['message']= "tansmission en erreur ".$exc->getMessage();  
        }
//                   $id = 0;
        

        return $retour;
    }
    
    private static function toSearch(ConfigReader $config,$retour){
        //on execute la recherche sur tout les plugin demandé
        $jsonPlug = filter_input(INPUT_GET, 'plugins');
        $search = filter_input(INPUT_GET, self::$SEARCH);
        if(!is_null($jsonPlug) && $jsonPlug !== false && !is_null($search) && $search !== false){
            $plugins = json_decode($jsonPlug);
            //a voir pour gerer multi plugin
            foreach($plugins as $plugin){
                $classname = PluginGenerique::getPluginClassName($plugin->id);
            }
            $torrent = new $classname($config);
            $torrent->search($search);
            $retour['success'] =$torrent->getResultSuccess() ;
            $retour['data']= $torrent->getResult() instanceof PluginListeResults ?$torrent->getResult()->getArrayCopy():null;
            $retour['totalCount'] = $torrent->getResultTotalCount();
            $retour['message'] = '';
        }

        return $retour;
    }
    
    private static function toPlugin($retour){
        $plugins = PluginGenerique::getListe();
        $retour['success'] =true ;
        $retour['data']=$plugins;
        return $retour;
    }
}
