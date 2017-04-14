<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myClass;

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
    private static $ACTION = 'action';
    private static $PLUGIN = 'plugin';
    private static $SEARCH = 'search';
    private static $TRANSMISSION = 'transmission';
    private static $LOGIN = 'login';
    private static $TOKEN = 'token';
    private static $SETTINGS = 'settings';
    private static $SEEDBOX = 'seedbox';
    
    
    
    
    public static function returnResponse(){
        $retour =array('success'=>false,'data'=>null,'message'=>'Erreur de traitement');
        //controle TOKEn
        $token = filter_input(INPUT_GET, self::$TOKEN);
        $action = filter_input(INPUT_GET, self::$ACTION);
        if((!isset($_SESSION['token']) && $action !== self::$LOGIN) || (isset($_SESSION['token']) && empty($_SESSION['token']) && $token !== $_SESSION['token'])){
             $retour['message']='Vous n\'êtes pas identifié correctement.';
        }else{
            $retour = self::traitementReponse($action,$token,$retour);
        }
        return json_encode($retour);
    }
    
    private static function traitementReponse($action,$token,$retour){
        try{
//            $configR = new ConfigReader($file);
            if($action === strtolower(self::$LOGIN)){
                $retour = self::toLogin($retour);
            }else if($action === strtolower(self::$TRANSMISSION)){
                $retour = self::toTransmission($token,$retour);
            }else if($action === strtolower(self::$SEARCH)){
                $retour = self::toSearch($token,$retour);
            }else if($action === strtolower(self::$PLUGIN)){
                $retour = self::toPlugin($retour);
            }else if($action === strtolower(self::$SETTINGS)){
                $retour = self::toSettings($token,$retour);
                
            }else if($action === strtolower(self::$SEEDBOX)){
                $retour = self::getSeedbox($token,$retour);
                
            }
        }catch(PluginException $exc){
            $retour['message'] = $exc->getMessage();
        }catch(TransmissionRPCException $exc){
            $retour['message'] = "Probleme de connexion à la SeedBox <br>".$exc->getMessage();
        }
        
        return $retour;
    }
    
    
    private static function toLogin($retour){
        
        //Authentification systeme
        $user = filter_input(INPUT_GET, 'username');
        $pwd = filter_input(INPUT_GET, 'password');
        $res = Services::authentification($user, $pwd);
        if(!empty($res) && count($res)=== 2){
            $retour['success'] = true;
            $retour['data'] = $res['token'];
            $retour['name'] = $res['name'];
            $retour['message'] = '';

            $_SESSION['token'] =  $res['token'];

        }

        return$retour;

    }
    
    private static function toTransmission($token,$retour){
        $url = filter_input(INPUT_GET, self::$TRANSMISSION);
        if(!is_null($url) && $url !== false){
//                $url = json_decode($url);
            if(stripos('magnet',$url)>= 0){
                $urlM = str_replace('@', '&tr', $url) ;
                $retour = self::toMagnet($token, $urlM, $retour);
            }else{
                $retour['message']= 'Erreur Ce n\'est pas un magnet.'; 
            }
            

        }else{
            $retour['message']= 'Erreur dans le lien '.$url;  
        }

        return$retour;

    }
    
    private static function toMagnet($token,$url,$retour){
        $config = Services::loadSettings($token);
        $proxy = self::getProxy($config);
        
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

        return $retour;
    }
    
    private static function toSearch($token,$retour){
        //on execute la recherche sur tout les plugin demandé
        $jsonPlug = filter_input(INPUT_GET, 'plugins');
        $search = filter_input(INPUT_GET, self::$SEARCH);
        if(!is_null($jsonPlug) && $jsonPlug !== false && !is_null($search) && $search !== false){
            $plugins = json_decode($jsonPlug);
            //a voir pour gerer multi plugin
            foreach($plugins as $plugin){
                $classname = PluginGenerique::getPluginClassName($plugin->id);
            }
            $config = Services::loadSettings($token);
            $torrent = new $classname($config);
            
            $start = filter_input(INPUT_GET, 'start')!== false?filter_input(INPUT_GET, 'start'):0;
//            $limit = filter_input(INPUT_GET, 'limit')!== false?filter_input(INPUT_GET, 'limit'):25;
            $search .= '/'.(intval($start)+1);
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
        $retour['message'] = '';
        
        return $retour;
    }
    
    
    private static function getProxy($reader){
        
        $proxy = false;
        if(!empty($reader['proxy_url'])){
            $proxy = true;
        }
        
        return $proxy;
    }
    
    private static function toSettings($token,$retour){
        $retour['message'] = '';
        $load = filter_input(INPUT_GET, 'config') === 'load' ? true:false;
        if($load){
            $retour['success'] = true;
            $retour['data'] = Services::loadSettings($token);
        }else{
            $retour['success'] = Services::saveSetings(Services::getEmail($token), filter_input_array(INPUT_GET));
//            var_dump(filter_input_array(INPUT_GET));
//            $retour['success'] = true;
            $retour['message'] = $retour['success']?'Settings save':'Settings not save retry';
        }
        return $retour;
    }
    
    private static function getSeedbox($token,$retour){
        $retour['message'] = 'Problème sur les settings';
        $proxy = false;
        $settings = Services::loadSettings($token);
        if(!empty($settings['proxy_url'] )){
            $proxy = true;
        }
//        var_dump($settings);
        if(isset($settings['transmission_url']) && isset($settings['transmission_user']) && isset($settings['transmission_password']) ){
            $transmission = new TransmissionRPC($settings['transmission_url'],$settings['transmission_user'],$settings['transmission_password'],$proxy);

            $elements = $transmission->get();
            $stats = $transmission->sstats();
            $retour['success'] = $elements->result === 'success'?true:false;
            $retour['data'] = $elements->arguments->torrents;
            $retour['stats'] = $stats;
            $retour['message'] = '';
            $retour['totalCount'] = count($retour['data']);
        }
        return $retour;
    }
           
}
