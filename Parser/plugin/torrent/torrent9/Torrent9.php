<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\plugin\torrent\torrent9;

use DOMDocument;
use DOMElement;
use Parser\CurlUrl;
use Parser\DOM\DOMNodeRecursiveIterator;
use Parser\plugin\torrent\PluginGenerique;

/**
 * Description of Torrent9
 *
 * @author xgld8274
 */
class Torrent9 extends PluginGenerique{
    private $url = '';
    private $urlSearch;
    private $proxy = false;
    private $config = false;
    private $result=array();
    
    private $success = false;
    private $totalCount = 0;

    private $message= '';
    
    private $ini;
    /**
     * 
     * @param type $search
     */
    public function __construct(array $config=null) {
        
        $this->ini = \Standard\Fichier\ReaderIni::read(dirname(__FILE__).DIRECTORY_SEPARATOR.'plugin.ini');
		$this->url =  $this->ini['settings']['url'];
                
        $this->name = 'Torrent9';
        $this->description = $this->url." -> torrent en Fr en général, rapide et fiable.";
        $this->urlSearch =$this->url. '/search_torrent/';
        $this->result = array();
        if(!empty($config)){
            $this->config = $config;
            if(isset($this->config['proxy_url']) && !empty($this->config['proxy_url'])){
                $this->proxy = true;
            }
        }
        
        
    }
    
    
    public function search($search,array $options=null) {
        $searchPageUrl = $this->urlSearch.$search.'.html';
        $urlProxy = $this->proxy?$this->config['proxy_url']:null;
        $searchPageUrl .= ($this->getStart() > 0)?'/page-'.intval($this->getStart()):'';
        $curl = new CurlUrl($searchPageUrl,$this->proxy,$urlProxy);

        $page =$curl->read();

	    if($page !== false){
            $arbre = new DOMDocument();
            @$arbre->loadHTML($page);
            $elements = $arbre->getElementsByTagName('table');

            foreach ($elements as $elem) {
                if($elem->hasAttribute('class')){
                    if("table table-striped table-bordered cust-table" === strtolower($elem->getAttribute('class'))){
                        $nodes = $elem->childNodes->item(1);
//                        var_dump($nodes);
//                        die;
                        if($nodes->hasChildNodes()){
//                            echo 'enfants';
//                            var_dump($nodes->childNodes);
//                            die;
                            $this->parcoursDomResult(new DOMNodeRecursiveIterator($nodes->childNodes));
                        }

                    }
                }

            }
            $this->totalCount = $this->parcoursDomPagination($arbre);
            $this->success = true;
        }else{
	        $this->message = 'impossible de recuperer les torrents sur le plugin, verifier l\'url';
        }
    }

    
    
    function parcoursDomPagination($arbre){
        $elements = $arbre->getElementsByTagName('ul');
        $limite = count($this->result);
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
//        echo "
//        <style scoped>
//            ol {
//              counter-reset: section;                /* On crée une nouvelle instance du
//                                                        compteur section avec chaque ol */
//              list-style-type: none;
//            }
//            li::before {
//              counter-increment: section;            /* On incrémente uniquement cette
//                                                        instance du compteur */
//              content: counters(section,\".\") \" \";    /* On ajoute la valeur de toutes les
//                                                        instances séparées par \".\". */
//                                                     /* Si on doit supporter < IE8 il faudra
//                                                        faire attention à ce qu'il n'y ait
//                                                        aucun blanc après ',' */
//            }
//        </style>
//        ";
//        echo "<ol >";
        foreach ($nodes as $node){
            if($node instanceof DOMElement){
                $urlTorrent = $this->getUrlTorrent(new DOMNodeRecursiveIterator($node->childNodes));
                if(!is_null($urlTorrent)){
                    $urlMagnet = $this->getMagnet($this->url.$urlTorrent['url']);
//                    $urlMagnet = "";

//                    echo "<li>".$urlMagnet ."</li>";
                    $this->generateResult($urlTorrent,$urlMagnet);

                }
            }

        }
//        echo "</ol>";


    }
    
    private function generateResult(array $urlTorrent,$urlMagnet){
        $index = count($this->result);
        if(!isset($this->result[$index])){
            $this->result[$index] = array();
        }
        $this->result[$index]['titre'] =  $urlTorrent['caption'];
        $this->result[$index]['magnet'] =  $urlMagnet;
        $this->result[$index]['size'] =  $urlTorrent['size'];
        $this->result[$index]['seeder'] =  $urlTorrent['seeder'];
        $this->result[$index]['leecher'] =  $urlTorrent['leecher'];
        $this->result[$index]['category'] =  $urlTorrent['category'];
        $this->result[$index]['categoryLabel'] =  $urlTorrent['categoryLabel'];
        
    }


    public function getResult(){
        $retour = new \Parser\plugin\torrent\PluginListeResults();
//        $retour = [];
        foreach($this->result as $resultat){
//            $results = new \Parser\plugin\torrent\PluginResults();
            $results = [];
            $results['title'] = $resultat['titre'];
//            $results['category'] = $resultat['category'];
            $results['leecher'] = $resultat['leecher'];
            $results['seeder'] = $resultat['seeder'];
            $results['size'] = $resultat['size'];
            $results['magnet'] = $resultat['magnet'];
            $results['category'] = $resultat['category'];
            $results['categoryLabel'] = $resultat['categoryLabel'];
            
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
        if(count($tr)>6){
//            var_dump($tr);
            //la categorie et le nom du torrent sont contenu dans le meme td
            $tdName = new DOMNodeRecursiveIterator($tr[1]->childNodes);

            $category = $this->transformeCategory($tdName[0]->getAttribute('class'));
            $url = $tdName[2]->getAttribute('href');
            $caption = $tdName[2]->textContent;


            $size = $tr[3]->textContent;

            $seeder = $tr[5]->textContent;

            $leecher = $tr[7]->textContent;

            return array('category'=>$category['image'],'categoryLabel'=>$category['label'],"url"=>$url,"caption"=>$caption,"size"=>$size,"seeder"=>$seeder,"leecher"=>$leecher);
        }
       return null;

       
    }
    
    private function transformeCategory($value){
//        var_dump($value);
        $cat = strtolower(str_replace(array(' ','/'), '-', $value));
        $image = null;
        foreach ($this->ini as $section){
            if(isset($section[$cat]) && isset($section['icone'])){
                $image = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.$section['icone']);
            }
        }
        return array('image'=>$image,'label'=>$cat);
    }
    

    private function getMagnet($url){
        $urlMagnet = array();
        $urlProxy = $this->proxy?$this->config['proxy_url']:null;
        $curl = new CurlUrl($url,$this->proxy,$urlProxy);

        $page =$curl->read();

        if($page !== false){
            $arbre = new DomDocument();
            @$arbre->loadHTML($page);
            $elements = @$arbre->getElementsByTagName('a');
            unset($arbre);
            foreach ($elements as $elem) {
                if($elem->hasAttribute('class')){
                    if("btn btn-danger download" === strtolower($elem->getAttribute('class')) && stripos($elem->getAttribute('href'),"magnet:?") !== false){
                        $urlMagnet = $elem->getAttribute('href');

                    }
                }
            }
        }
        $curl->close();
        unset($curl);
        return $urlMagnet;

    }


    public function getMessage(): String
    {
        return $this->message;
    }


}

