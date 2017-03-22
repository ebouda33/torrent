<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\plugin\torrent\nextorrent;

use config\ConfigReader;
use DOMDocument;
use DOMElement;
use Parser\CurlUrl;
use Parser\DOM\DOMNodeRecursiveIterator;
use Parser\plugin\torrent\PluginGenerique;

/**
 * Description of Nextorrent
 *
 * @author xgld8274
 */
class Nextorrent extends PluginGenerique{
    private $url = 'https://www.nextorrent.net';
    private $urlSearch;
    private $proxy = false;
    private $config = false;
    private $result=array();
    
    private $success = false;
    private $totalCount = 0;
    
    /**
     * 
     * @param type $search
     */
    public function __construct(ConfigReader $config=null) {
        
        $this->name = 'NexTorrent';
        $this->description = "https://www.nextorrent.net -> torrent en Fr en général, rapide et fiable.";
        $this->urlSearch =$this->url. '/torrents/recherche/';
        $this->result = array();
        if(!empty($config)){
            $this->config = $config->getConfig();
            if(!empty($this->config['proxy_url'])){
                $this->proxy = true;
            }
        }
        
        
    }
    
    
    public function search($search) {
        $searchPageUrl = $this->urlSearch.$search;

        $curl = new CurlUrl($searchPageUrl,$this->proxy,$this->config['proxy_url']);
        $page =$curl->read();

        //echo(htmlentities($page));

        if($page !== false){
            $arbre = new DOMDocument();
            @$arbre->loadHTML($page);
            $elements = $arbre->getElementsByTagName('table');
            foreach ($elements as $elem) {
                if($elem->hasAttribute('class')){
                    if("table table-hover" === strtolower($elem->getAttribute('class'))){
                        $domnodes = new DOMNodeRecursiveIterator($elem->childNodes);
                        if($domnodes->getChildren()->count()>2){
                            $this->parcoursDomResult($domnodes->getChildren());
                        }
                    }
                }

            }
            $this->success = true;
        }
    }

    
    
    function parcoursDomPagination(){

    }

    private function parcoursDomResult(DOMNodeRecursiveIterator $nodes){
        foreach ($nodes as $node){
            $urlTorrent = $this->getUrlTorrent(new DOMNodeRecursiveIterator($node->childNodes));
            $urlMagnet = $this->getMagnet($this->url.$urlTorrent['url']);
            $index = count($this->result);
            if(!isset($this->result[$index])){
                $this->result[$index] = array();
            }
            $this->result[$index]['titre'] =  $urlTorrent['caption'];
            $this->result[$index]['url'] =  $urlMagnet['url'];
            $this->result[$index]['size'] =  $urlTorrent['size'];
            $this->result[$index]['seeder'] =  $urlTorrent['seeder'];
            $this->result[$index]['leecher'] =  $urlTorrent['leecher'];

        }

        
    }
    
    public function getResult(){
        return $this->result;
    }
    
    public function getResultSuccess() {
        return $this->success;
    }

    public function getResultTotalCount() {
        //mieux le caculer avec la pagination prob nextorrent
        return count($this->result);
    }

        private function getUrlTorrent(DOMNodeRecursiveIterator $tr){
       $td = new DOMNodeRecursiveIterator($tr[0]->childNodes);
       $url = $td[2]->getAttribute('href');
        $caption = $td[2]->textContent;

       $td = new DOMNodeRecursiveIterator($tr[2]->childNodes);
       $size = $td[0]->textContent;
       
        $td = new DOMNodeRecursiveIterator($tr[4]->childNodes);
        $seeder=$td[1]->textContent;
                
        $td = new DOMNodeRecursiveIterator($tr[6]->childNodes);
        $leecher=$td[1]->textContent;
        
        return array("url"=>$url,"caption"=>$caption,"size"=>$size,"seeder"=>$seeder,"leecher"=>$leecher);


    }

    private function getMagnet($url){
       $urlMagnet = array();
        $curl = new CurlUrl($url,$this->proxy,$this->config['proxy_url']);
        $page =$curl->read();
        if($page !== false){
            $arbre = new DomDocument();
            @$arbre->loadHTML($page);
            $elements = $arbre->getElementsByTagName('div');
            foreach ($elements as $elem) {
                if($elem->hasAttribute('class')){
                    if("download" === strtolower($elem->getAttribute('class'))){
                        $urlMagnet = $this->getUrlMagnet($elem);
                    }
                }
            }
       }
       $curl->close();
       return $urlMagnet;

    }

    private function getUrlMagnet(DOMElement $div){

        $a = new DOMNodeRecursiveIterator($div->childNodes);
        $url = $a[2]->getAttribute('href');
        $caption = $a[2]->textContent;


        return array("url"=>$url,"caption"=>$caption);
    }
}
