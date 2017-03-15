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
    
    public $handler ;
    private $close=true;
    
    
    public function __construct($url,$useProxy=false) {
        $this->handler = curl_init();
        
        curl_setopt($this->handler, CURLOPT_URL, $url);
        if($useProxy){
            $this->defineProxy();
        }
        curl_setopt($this->handler,CURLOPT_HEADER,false);
        curl_setopt($this->handler, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, false);
        
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, false); // false for https
    }
    
    
    private function defineProxy(){
        $ch = $this->handler;
        $proxy = "127.0.0.1:3128";
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        
    }
    
    public function defineHeader($entete,$value){
        curl_setopt($this->handler, $entete, $value);
    }
    
    public function definePost($post){
       $string = $post;
        if (is_array($post))
        {
            $string = '';
            foreach ($post as $key => $value){
                $string .= "$key=$value&";
            }
        }
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, $string);
        curl_setopt($this->handler, CURLOPT_POST, true);
    }
    
    public function read($url = null){
        if(!is_null($url)){
            curl_setopt($this->handler, CURLOPT_URL, $url);
        }
        $stream =  curl_exec($this->handler);
        
        print_r(curl_getinfo($this->handler));
        
        $this->close = false;
        
        //on reinitialise les params jusqu a la prochaine fois
        curl_setopt($this->handler, CURLOPT_POST, false);
        return $stream;
    }
    
    public function close(){
        if(!$this->close){
           curl_close($this->handler);
            $this->close = true;
        }
    }
    
    public function __destruct() {
        $this->close();
    }
    
}
