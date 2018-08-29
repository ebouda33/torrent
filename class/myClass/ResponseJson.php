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
use Standard\Fichier\ReaderIni;
use Transmission\TransmissionRPC;
use Transmission\TransmissionRPCException;
use myClass\Plex;
use Standard\Web\ExtJSUtil;

/**
 * Description of ResponseJson
 *
 * @author xgld8274
 */
class ResponseJson {
    //put your code here
    public static $ACTION = 'action';
    private static $PLUGIN = 'plugin';
    private static $SEARCH = 'search';
    private static $TRANSMISSION = 'transmission';
    private static $LOGIN = 'login';
    private static $TOKEN = 'token';
    private static $SETTINGS = 'settings';
    private static $SEEDBOX = 'seedbox';
    private static $CATEGORIES = 'categories';
    public static $DOWNLOAD = 'download';
	public static $PLEX_FILES = 'PLEX_FILES';

	private static $LIMITPAGESIZE = 100;
    
    private static function loginValide($action,$token){
        // return !(!isset($_SESSION['token']) && $action !== self::$LOGIN) || (isset($_SESSION['token']) && empty($_SESSION['token']) && $token !== $_SESSION['token'] || (isset($_SESSION['authentification']) || (isset($_SESSION['authentification']) && !$_SESSION['authentification'])));
		$etatLogin = ($action === self::$LOGIN);
		if($etatLogin){
			 return true;
		}
		
		
		$etatToken = (isset($_SESSION['token']) && !empty($_SESSION['token']) && $token === $_SESSION['token']);
		$etatAuthent = ((isset($_SESSION['authentification'])) && (isset($_SESSION['authentification']) && $_SESSION['authentification']));
		$etatGlobal = $etatToken && $etatAuthent;
		
		// echo $token ." ".$_SESSION['token'];
		// echo "#L".$etatLogin; 
		// echo "<br>";
		// echo "#T".$etatToken;
		// echo "<br>";
		// echo "#A".$etatAuthent;
		// echo "<br>";
		// echo "<br>";
		// echo "#".$etatGlobal ."#";
		
		// unset($_SESSION['authentification']);
		
		
		return $etatGlobal;
    }
    
    public static function getDetails(){
        $token = filter_input(INPUT_GET, self::$TOKEN);
        $action = filter_input(INPUT_GET, self::$ACTION);
        $retour = 'Torrent incohérent / Incorrect Torrent';
        if(self::loginValide($action,$token)){
            $torrent = self::getPlugin($token);
            $id = filter_input(INPUT_GET, 'id');
            if(method_exists($torrent, 'details')){
                $retour = $torrent->details($id);
            }
        }
        return $retour;
    }
    
    private static function getPlugin($token){
        $plugin = filter_input(INPUT_GET, self::$PLUGIN);
        if($plugin !== false){
            $plugins = json_decode($plugin);
            $pluginClassName = PluginGenerique::getPluginClassName($plugins[0]->id);
            $torrent = self::getClassPlugin($pluginClassName,$token);
        } 
        return $torrent;
    }
    
    public static function returnTorrent(){
        $token = filter_input(INPUT_GET, self::$TOKEN);
        $action = filter_input(INPUT_GET, self::$ACTION);
        $retour = 'Torrent incohérent / Incorrect Torrent';
        if(self::loginValide($action,$token)){
//             $retour = self::traitementReponse($action,$token,$retour);
            $torrent = self::getPlugin($token);
            $retour = self::getTorrentForPlugin($torrent,$token);
             
        }
        
        return $retour;
    }
    
    
    public static function returnResponse(){
        $retour =array('success'=>false,'data'=>null,'message'=>'Erreur de traitement');
        //controle TOKEn
        $token = filter_input(INPUT_GET, self::$TOKEN);
        $action = filter_input(INPUT_GET, self::$ACTION);
        if(!self::loginValide($action, $token)){
             $retour['message']='Vous n\'êtes pas identifié correctement.';
        }else{
            $retour = self::traitementReponse($action,$token,$retour);
        }
        return json_encode($retour);
    }
    
    private static function getClassPlugin($className,$token){
        $config = Services::loadSettings($token);
        $torrent = new $className($config);
        return $torrent;
    }

        private static function traitementReponse($action,$token,$retour){
        try{
//            $configR = new ConfigReader($file);
            if(strtolower($action) === strtolower(self::$LOGIN)){
                $retour = self::toLogin($retour);
            }else if(strtolower($action) === strtolower(self::$TRANSMISSION)){
                $retour = self::toTransmission($token,$retour);
            }else if(strtolower($action) === strtolower(self::$SEARCH)){
                $retour = self::toSearch($token,$retour);
            }else if(strtolower($action) === strtolower(self::$PLUGIN)){
                $retour = self::toPlugin($retour);
            }else if(strtolower($action) === strtolower(self::$SETTINGS)){
                $retour = self::toSettings($token,$retour);
            }else if(strtolower($action) === strtolower(self::$SEEDBOX)){
                $retour = self::getSeedbox($token,$retour);
            }else if(strtolower($action) === strtolower(self::$CATEGORIES)){
                $retour = self::getCategories($token,$retour);
            }else if(strtolower($action) === strtolower(self::$PLEX_FILES)){
				$retour = self::getPlexInfo($token,$retour);
			}
        }catch(PluginException $exc){
            $retour['message'] = $exc->getMessage();
        }catch(TransmissionRPCException $exc){
            var_dump($exc);
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
			$_SESSION['authentification'] = $retour['success'];

        }

        return$retour;

    }
    
    private static function toTransmission($token,$retour){
        $url = filter_input(INPUT_GET, self::$TRANSMISSION);
        $plugin = filter_input(INPUT_GET, self::$PLUGIN);
        if(!is_null($url) && $url !== false){
//                $url = json_decode($url);
          
            if(stripos($url,'magnet') !== false && stripos($url,'magnet') === 0){
                $urlM = str_replace('@', '&tr', $url) ;
                $retour = self::toMagnet($token, $urlM, $retour);
            }elseif($plugin !== false){
                $retour = self::toTorrent($token,$url, $retour);
            }else{
                $retour['message']= 'Erreur Ce n\'est pas un magnet/torrent.'; 
            }
            

        }else{
            $retour['message']= 'Erreur dans le torrent '.$url;  
        }

        return $retour;

    }
    
    private static function toTorrent($token,$url,$retour){
        $plugTorrent = self::getPlugin($token);
        $torrentLines = self::getTorrentContent($url,$plugTorrent);
        return self::toMagnet($token, $torrentLines, $retour,true);
    }
    
    private static function toMagnet($token,$url,$retour,$meta_info = false){
        $config = Services::loadSettings($token);
        $proxy = self::isProxy($config);
        $location = filter_input(INPUT_GET, "location");
      
        try{
            $transmission = new TransmissionRPC($config['transmission_url'], $config['transmission_user'], $config['transmission_password'],$proxy);
           // $transmission->setDebug(true);
            if($meta_info){
                $result =  $transmission->add_metainfo($url,$location);
            }else{
                $result =  $transmission->add($url,$location);
            }
            $id = 0;
            if(isset($result->arguments->torrent_duplicate)){
                $id = $result->arguments->torrent_duplicate->id;
                $retour['duplicate'] = true;
            }else{
                $id = (isset($result->arguments->torrent_added))?$result->arguments->torrent_added->id:-1;
            }
            $retour['success'] =$result->result === 'success'?true:false ;
            $retour['message']= $result->result === 'success'?"tansmission =>".$id:$result->result;  
        }catch(TransmissionRPCException $exc){
            $retour['message']= "tansmission en erreur ".$exc->getMessage();  
        }
      
      ///echo "<br/>RETOUR";
      //var_dump($retour);
       //  die;
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
            $limit = filter_input(INPUT_GET, 'limit')!== false?filter_input(INPUT_GET, 'limit'):100;
//            $search .= '/'.(intval($start)+1);
            $torrent->setStart($start);
            $torrent->setLimit($limit);
            $torrent->setCategorie(filter_input(INPUT_GET, 'categorie')!== false?filter_input(INPUT_GET, 'categorie'):null);
            $torrent->search($search);
            $retour['success'] =$torrent->getResultSuccess() ;
            $retour['data']= $torrent->getResult() instanceof PluginListeResults ?$torrent->getResult()->getArrayCopy():null;
            $retour['totalCount'] = $torrent->getResultTotalCount();
            $retour['message'] = $torrent->getMessage();
            $retour['pageSize'] = self::$LIMITPAGESIZE;
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
    
    
    private static function isProxy($reader){
        
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
        $settings = Services::loadSettings($token);
        $proxy = self::isProxy($settings);
//        var_dump($settings);
        $proxyurl = null;
        if($proxy){
            $proxyurl = $settings['proxy_url'];
        }
        if(isset($settings['transmission_url']) && isset($settings['transmission_user']) && isset($settings['transmission_password']) ){
            $transmission = new TransmissionRPC($settings['transmission_url'],$settings['transmission_user'],$settings['transmission_password'],$proxy,$proxyurl);

            $elements = $transmission->get();
            $stats = $transmission->sstats();
            $retour['success'] = $elements->result === 'success'?true:false;
            $retour['data'] = $elements->arguments->torrents;
            $retour['stats'] = $stats;
            $retour['message'] = '';
            $retour['totalCount'] = count($retour['data']);
            $retour['pageSize'] = self::$LIMITPAGESIZE;
        }
        return $retour;
    }
    
    private static function getCategories($token,$retour){
//        $config = Services::loadSettings($token);
//        $proxy = self::getProxy($config);
        //recup la config_generale
        $ini = self::getIniGeneral();
        $categories = array();
        foreach ($ini['categories'] as $cat){
            array_push($categories,array('value'=>$cat,'text'=>$ini[$cat]['label']));
        }
        $retour['data'] = $categories;
        $retour['success'] = true;
        $retour['message']='Categories generales';
        return  $retour;
    }
           
    
    private static function getIniGeneral(){
        $file = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR. 'config'.DIRECTORY_SEPARATOR.'config_general.ini';
        
        $ini = ReaderIni::read($file);
        
        return $ini;
    }
    
    private static function getTorrentForPlugin(PluginGenerique $plugin){
        $id = filter_input(INPUT_GET, 'id');
        return self::getTorrentContent($id,$plugin);
    }
    
    private static function getTorrentContent($id,$plugin){
        if(method_exists($plugin, 'download')){
            return $plugin->download($id);
        }
        return '';
    }
	
	private static function getPlexInfo($token,$retour){
		
		$node = filter_input(INPUT_GET, 'node');
		$ini = self::getIniGeneral();
		$plex = new Plex();
		if($node === 'root'){
		    if(key_exists(Plex::PATH_PLEX,$ini)){
			    $node = $ini[Plex::PATH_PLEX];
            }
		}
		$data = $plex->getFiles($node);
		$retour['data'] = ExtJSUtil::transformExplorerToExtTree($data);
		$retour['totalCount'] = count($retour['data']);
		$retour['message'] = '';
		$retour['success'] = true;
		return $retour;
	}
}
