<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\plugin\torrent\nextorrent;

use DOMElement;
use Parser\CurlUrl;
use Parser\DOM\DOMNodeRecursiveIterator;

/**
 * Description of Nextorrent
 *
 * @author xgld8274
 */
class Nextorrent {
    private $url = 'https://www.nextorrent.net';
    private $urlSearch;
    private $proxy = true;
    
    private $result=array();
    
    public function __construct($search) {
        $this->urlSearch =$this->url. '/torrents/recherche/';
        $this->result = array();
        
        $searchPageUrl = $this->urlSearch.$search;

        $curl = new CurlUrl($searchPageUrl,$this->proxy);
        $page =$curl->read();

        //echo(htmlentities($page));

        if($page !== false){
            $arbre = new DomDocument();
            @$arbre->loadHTML($page);
            $elements = $arbre->getElementsByTagName('table');
            foreach ($elements as $elem) {
                if($elem->hasAttribute('class')){
                    if("table table-hover" === strtolower($elem->getAttribute('class'))){
                        $domnodes = new DOMNodeRecursiveIterator($elem->childNodes);
                       $this->parcoursDomResult($domnodes->getChildren());
                    }
                }

            }
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
        $curl = new CurlUrl($url,$this->proxy);
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
