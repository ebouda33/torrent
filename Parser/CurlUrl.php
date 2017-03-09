<?php
namespace Parser;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CurlUrl
 *
 * @author xgld8274
 */
class CurlUrl {
    //put your code here
    
    private $curl ;
    private $close=true;
    
    
    public function __construct($url,$useProxy=false) {
        $this->curl = curl_init();
        
        curl_setopt($this->curl, CURLOPT_URL, $url);
        if($useProxy){
            $this->defineProxy();
        }
        curl_setopt($this->curl,CURLOPT_HEADER,0);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, 0);
    }
    
    
    private function defineProxy(){
        $ch = $this->curl;
        $proxy = "127.0.0.1:3128";
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false for https
    }
    
    public function read(){
       $stream =  curl_exec($this->curl);
        $this->close = false;
        return $stream;
    }
    
    public function close(){
        if(!$this->close){
           curl_close($this->curl);
            $this->close = true;
        }
    }
    
    public function __destruct() {
        $this->close();
    }
    
}
