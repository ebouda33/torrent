<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\plugin\torrent\t411;

use config\ConfigReader;
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
    private $urlApi = "http://api.t411.li";
    private $urlSearch;
    private $proxy = false;
    public $curl ;
    private $result=array();
    private $config;
    
    private $token;
    private $username;
    private $password;
    
    private $success = false;
    private $totalCount = 0;
    
    function __construct(ConfigReader $config=null) {
        
        $this->name = 'T411';
        $this->description = "http://api.t411.li -> Le célébre T411.Il faut un compte pour fonctionner.";
        
        if(!empty($config)){
            $this->config = $config->getConfig();
            if(!empty($this->config['proxy_url'])){
                $this->proxy = true;
            }
            $this->urlSearch = $this->urlApi . '/torrents/search/';
            $this->curl = new CurlUrl($this->urlSearch,$this->proxy,$this->config['proxy_url']);
            
            $this->login();
        
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
        $this->username = $this->config['t411_user'];
        $this->password = $this->config['t411_password'];
        $this->curl->definePost([
                'username' => $this->username,
                'password' => $this->password,
            ]);
        $answer = $this->curl->read($this->urlApi.'/auth');
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
        /**
        curl_setopt(??, CURLOPT_HTTPHEADER, [
            'Authorization: '.$this->token
        ]);
        **/
         
    }
    
    public function search($search){
        $motif = explode('&', $search)[0];
        $searchEncode = str_replace($motif, \urlencode($motif), $search);
        $answer = $this->curl->read($this->urlSearch.$searchEncode);
        
        $this->result = $this->genereResult($answer);
        
    }
    
    private function genereResult($answer){
        $flux = json_decode($answer,true);
        $retour = new \Parser\plugin\torrent\PluginListeResults();
        foreach($flux['torrents'] as $resultat){
            $results = new \Parser\plugin\torrent\PluginResults();
            $results['id'] = $resultat['id'];
            $results['title'] = $resultat['name'];
            $results['category'] = $resultat['category'];
            $results['leecher'] = $resultat['leechers'];
            $results['seeder'] = $resultat['seeders'];
            $results['size'] = $resultat['size'];
        }
        
        $this->success = true;
        $this->totalCount = $flux['total'];
        
        return $retour;
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
    
    
}
