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
    
    private $ini;
    /**
     * 
     * @param type $search
     */
    public function __construct(array $config=null) {
        
        $this->name = 'NexTorrent';
        $this->description = "https://www.nextorrent.net -> torrent en Fr en général, rapide et fiable.";
        $this->urlSearch =$this->url. '/torrents/recherche/';
        $this->result = array();
        if(!empty($config)){
            $this->config = $config;
            if(isset($this->config['proxy_url']) && !empty($this->config['proxy_url'])){
                $this->proxy = true;
            }
        }
        $this->ini = \Standard\Fichier\ReaderIni::read(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugin.ini');
        
        
    }
    
    
    public function search($search) {
        $searchPageUrl = $this->urlSearch.$search;
        $urlProxy = $this->proxy?$this->config['proxy_url']:null;
        $curl = new CurlUrl($searchPageUrl,$this->proxy,$urlProxy);
        $page =$curl->read();

        if($page !== false){
            $arbre = new DOMDocument();
            @$arbre->loadHTML($page);
            $elements = $arbre->getElementsByTagName('table');
            foreach ($elements as $elem) {
                if($elem->hasAttribute('class')){
                    if("table table-hover" === strtolower($elem->getAttribute('class'))){
                       
                        $domnodes = new DOMNodeRecursiveIterator($elem->childNodes);
                        if($domnodes->getChildren()->count()>0){
                            
                            $this->parcoursDomResult($domnodes->getChildren());
                        }
                    }
                }

            }
            $this->totalCount = $this->parcoursDomPagination($arbre);
            $this->success = true;
        }
    }

    
    
    function parcoursDomPagination($arbre){
        $elements = $arbre->getElementsByTagName('ul');
        $limite = 0;
        foreach ($elements as $elem) {
            if($elem->hasAttribute('class')){
                    if("pagination" === strtolower($elem->getAttribute('class')) && $elem->tagName === 'ul'){
                        $domnodes = new DOMNodeRecursiveIterator($elem->childNodes);
                        foreach ($domnodes as $node){
                            if($node instanceof DOMElement && $node->hasChildNodes() ) {
                                $as = new DOMNodeRecursiveIterator($node->childNodes);
                                foreach ($as as $a){
//                                    echo $a->nodeName . ' '.$a->textContent;
                                    $matches = null;
                                    preg_match('#-(.+)]#isU',$a->textContent,$matches);
//                                    var_dump($matches);
                                    if(count($matches)>1){
                                        $index = $matches[1];
                                        $limite = (intval($index)>$limite)?intval($index):$limite;
                                    }
                                }
                            }
                        }
                    }
            }

        }
        return $limite;
    }

    private function parcoursDomResult(DOMNodeRecursiveIterator $nodes){
        foreach ($nodes as $node){
            $urlTorrent = $this->getUrlTorrent(new DOMNodeRecursiveIterator($node->childNodes));
            
            if(!is_null($urlTorrent)){
                $this->generateResult($urlTorrent);
                
            }

        }

        
    }
    
    private function generateResult(array $urlTorrent){
        $urlMagnet = $this->getMagnet($this->url.$urlTorrent['url']);
        $index = count($this->result);
        if(!isset($this->result[$index])){
            $this->result[$index] = array();
        }
        $this->result[$index]['titre'] =  $urlTorrent['caption'];
        $this->result[$index]['magnet'] =  $urlMagnet['url'];
        $this->result[$index]['size'] =  $urlTorrent['size'];
        $this->result[$index]['seeder'] =  $urlTorrent['seeder'];
        $this->result[$index]['leecher'] =  $urlTorrent['leecher'];
        $this->result[$index]['category'] =  $urlTorrent['category'];
    }


    public function getResult(){
        $retour = new \Parser\plugin\torrent\PluginListeResults();
        foreach($this->result as $resultat){
            $results = new \Parser\plugin\torrent\PluginResults();
            $results['title'] = $resultat['titre'];
//            $results['category'] = $resultat['category'];
            $results['leecher'] = $resultat['leecher'];
            $results['seeder'] = $resultat['seeder'];
            $results['size'] = $resultat['size'];
            $results['magnet'] = $resultat['magnet'];
            $results['category'] = $resultat['category'];
            $retour->append($results);
        }
        return $retour;
    }
    
    public function getResultSuccess() {
        return $this->success;
    }

    public function getResultTotalCount() {
        //mieux le caculer avec la pagination prob nextorrent
        return $this->totalCount;
    }

    private function getUrlTorrent(DOMNodeRecursiveIterator $tr){
       
       $category = '';
       $url = "";
       $caption="Pas de résultat";
       $size = 0;
       $leecher = 0;
       $seeder = 0;
       
       if(count($tr)>6){
           $temp = new DOMNodeRecursiveIterator($tr[0]->childNodes);
           $td = new DOMNodeRecursiveIterator($temp[0]->childNodes);
           
            $category = $this->transformeCategory($td[0]->getAttribute('title'));
            $url = $td[1]->getAttribute('href');
            $caption = $td[1]->textContent;

            $size = $this->getTextContent($tr[2],0);

            $seeder=$this->getTextContent($tr[4]);

            $leecher=$this->getTextContent($tr[6]);
            
            return array('category'=>$category,"url"=>$url,"caption"=>$caption,"size"=>$size,"seeder"=>$seeder,"leecher"=>$leecher);
            
       }
       return null;


    }
    
    private function transformeCategory($value){
//        var_dump($value);
        $cat = strtolower(str_replace(array(' ','/'), '-', $value));
        foreach ($this->ini as $section){
            if(isset($section[$cat]) && isset($section['icone'])){
                $cat = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.$section['icone']);
            }
        }
        return $cat;
    }
    
    private function getTextContent($tr,$index=1){
        $td = new DOMNodeRecursiveIterator($tr->childNodes);
        return $td[$index]->textContent;
        
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
        $url = "";
        $caption="";
        if(count($a)>1){
            $url = $a[2]->getAttribute('href');
            $caption = $a[2]->textContent;
        }

        return array("url"=>$url,"caption"=>$caption);
    }
}
