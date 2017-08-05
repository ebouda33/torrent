<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\plugin\torrent\t411;

use Parser\CurlUrl;
use Parser\plugin\torrent\PluginException;
use Parser\plugin\torrent\PluginGenerique;

/**
 * Description of T411
 *
 * @author xgld8274
 */
class T411 extends PluginGenerique{
    //put your code here
    private $urlApi = "https://api.t411.al";
    private $urlSearch;
    private $proxy = false;
    public $curl ;
    private $result=array();
    private $config;
    private $config_general;
    private $ini;
    
    
    private $token;
    private $username;
    private $password;
    
    private $success = false;
    private $totalCount = 0;
    
    function __construct(Array $config=null) {
        
        $this->ini = \Standard\Fichier\ReaderIni::read(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugin.ini');
		
		$this->url =  $this->ini['url'];
        $this->name = 'T411';
        $this->description = $this->url." -> Le célébre T411.Il faut un compte pour fonctionner.";
        
        if(!empty($config)){
//            $this->config = $config->getConfig();
            $this->config = $config;
            $urlProxy = '';
            if(isset($this->config['proxy_url']) && !empty($this->config['proxy_url'])){
                $this->proxy = true;
                $urlProxy = $this->config['proxy_url'];
            }
            $this->urlSearch = $this->urlApi . '/torrents/search/';
            $this->curl = new CurlUrl($this->urlSearch,$this->proxy,$urlProxy);
            
            if(empty($this->token)){
                $this->login();
            }else{
                $this->patchAuthorization();
            }
        
        }
    }

    public function getResult() {
        return $this->result;
    }

    
    function login(){
        $answer = $this->auth();
        if(!is_null($answer) && $answer !== false){
            $this->uid   = $answer->uid;
            $this->token = $answer->token;

            $this->patchAuthorization();
        }else{
            throw  new PluginException('Failure Connection', PluginException::FLUX_ERROR);
        }
        
        
    }
    
    private function auth(){
        $this->username = $this->config['t411_username'];
        $this->password = $this->config['t411_password'];
        $this->curl->definePost([
                'username' => $this->username,
                'password' => $this->password,
            ]);
        $answer = $this->curl->read($this->urlApi.'/auth');
//        var_dump($this->config);
//        var_dump($this->urlApi);
        return json_decode($answer);
    }
    
    
    /**
     * Rajoute un header "Authorization" à l'objet acurl
     */
    private function patchAuthorization()
    {
        $this->curl->defineHeader(CURLOPT_HTTPHEADER, [
            'Authorization: '.$this->token
        ]);
         
    }
    
    public function search($search){
        $motif = explode('&', $search)[0];
        $searchEncode = str_replace($motif, \urlencode($motif), $search);
        $limit = '?offset='.$this->getStart().'&limit='.$this->getLimit();
        
        $categorie = $this->getCategorie();
        $gen = $this->ini;
        $cid = (isset($gen[$categorie]['default'])?$gen[$categorie]['default']:'');
        if(!empty($cid) ){
            $cid = '&cid='.$cid;
        }
        $motif = $this->urlSearch.$searchEncode.$limit.$cid;
        $answer = $this->curl->read($motif);
        $this->result = $this->genereResult($answer);
    }
    
    private function genereResult($answer){
        $flux = json_decode($answer,true);
        $retour = new \Parser\plugin\torrent\PluginListeResults();
        if(isset($flux['torrents'])){
            foreach($flux['torrents'] as $resultat){
                $results = new \Parser\plugin\torrent\PluginResults();
                $results['id'] = $resultat['id'];
                $results['title'] = $resultat['name'];
                $cat = $this->transformeCategory($resultat['category']);
                $results['category'] = $cat[0];
                $results['categoryLabel'] =$cat[1];
                $results['leecher'] = $resultat['leechers'];
                $results['seeder'] = $resultat['seeders'];
                $results['size'] = $resultat['size'];
                $retour->append($results);
            }
        }
        
        $this->success = true;
        $this->totalCount = isset($flux['total'])?$flux['total']:0;
        
        return $retour;
    }
    
    private function transformeCategory($cat){
//        var_dump($this->ini);
        $label = $cat;
        foreach ($this->ini as $title =>$section){
            if(isset($section[$cat])){
                $gen = $this->getConfigGeneral();
                $label = $section[$cat];
                $cat = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.$gen[$title]['icone']);
            }
        }
        return array($cat,$label);
    }
    
    public function getResultSuccess() {
        return $this->success;
    }

    public function getResultTotalCount() {
        return $this->totalCount;
    }

    
    public function getToken(){
        return $this->token;
    }
    
    public function getCategories(){
        $answer = $this->curl->read($this->urlApi.'/categories/tree/');
        
        return json_decode($answer,true);
        
    }
    
    public function download($id){
        $answer = $this->curl->read($this->urlApi.'/torrents/download/'.$id);
        
        return $answer;
    }
    
    public function details($id){
        $answer = $this->curl->read($this->urlApi.'/torrents/details/'.$id);
        $torrent = new \Parser\plugin\torrent\PluginResults(json_decode($answer));
        return $torrent;
    }
    
    public function getNameOfClass() {
        return 'T411';
    }


    
}
