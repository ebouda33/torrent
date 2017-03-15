<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\plugin\torrent\t411;

use config\ConfigReader;
use Parser\CurlUrl;
use Parser\plugin\torrent\TorrentInterface;

/**
 * Description of T411
 *
 * @author xgld8274
 */
class T411 implements TorrentInterface{
    //put your code here
    private $urlApi = "http://api.t411.li";
    private $urlSearch;
    private $proxy = false;
    private $curl ;
    private $result=array();
    
    private $token;
    private $username;
    private $password;
    
    function __construct(ConfigReader $config,$search) {
        $this->urlSearch = $this->urlApi . '/search?'.$search;
        $this->curl = new CurlUrl($this->urlSearch,$this->proxy);
    }

    public function getResult() {
        return $this->result;
    }

    
    function login(){
        $answer = $this->auth();
        echo "login:".print_r($answer);
        var_dump($answer);
        
        $this->uid   = $answer->uid;
        $this->token = $answer->token;
        
        $this->patchAuthorization();
        
    }
    
    private function auth(){
        $this->curl->definePost([
                'username' => $this->username,
                'password' => $this->password,
            ]);
        $answer = $this->curl->read($this->urlApi.'/auth');
        var_dump($answer);
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
}
