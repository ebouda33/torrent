<?php

use Parser\nextorrent\Nextorrent;
use Transmission\TransmissionRPC;


require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init_autoloader.php';


$nextorrent = new Nextorrent("terminator");
$resultat = $nextorrent->getResult();
//var_dump($resultat);

foreach ($resultat as $row){
    echo $row['titre'] .' <a href=\'\'>VOIR</a>';
    echo '<br>';
}
try{
    $transmission = new TransmissionRPC('http://new.nabous.fr:10001/rpc', 'ebouda', 'eaboud');
    
    echo $transmission->GetSessionID();
    
    $torrent_location = $resultat[0]['url'];
    $result =  $transmission->add($torrent_location,'/mnt/data/download');
    $id = $result->arguments->torrent_added->id;
    print "ADD TORRENT TEST... [{$result->result}] (id=$id)\n";
//    
} catch (Exception $ex) {
    echo $ex;
}

//$pageUrl = "http://localhost/torrent/Parser/nextorrent/";
//$siteUrl = "https://www.nextorrent.net";
//$searchPageUrl = $siteUrl."/torrents/recherche/terminator";
//$proxy = true;
//
//
//$curl = new CurlUrl($searchPageUrl,$proxy);
//$page =$curl->read();
//
////echo(htmlentities($page));
//
//if($page !== false){
//    $arbre = new DomDocument();
//    @$arbre->loadHTML($page);
//    $elements = $arbre->getElementsByTagName('table');
//    foreach ($elements as $elem) {
//        if($elem->hasAttribute('class')){
//            if("table table-hover" === strtolower($elem->getAttribute('class'))){
//                $domnodes = new DOMNodeRecursiveIterator($elem->childNodes);
//               parcoursDomResult($domnodes->getChildren());
//                echo "<br>";
//                
//            }
//        }
//
//    }
//}
//
//function parcoursDomPagination(){
//    
//}
//
//function parcoursDomResult(DOMNodeRecursiveIterator $nodes){
//    global $siteUrl;
//    foreach ($nodes as $node){
//        $urlTorrent = getUrlTorrent(new DOMNodeRecursiveIterator($node->childNodes));
//        echo $urlTorrent['caption'].'</a>';
//        $urlMagnet = getMagnet($siteUrl.$urlTorrent['url']);
//        if(count($urlMagnet)){
//            echo ' Magnet => <a href="'.$urlMagnet['url'].'">'.$urlMagnet['caption'].'</a>';
//        }
//        echo "<br>";
//    }
//    
//}
//
//function getUrlTorrent(DOMNodeRecursiveIterator $tr){
//    $td = new DOMNodeRecursiveIterator($tr[0]->childNodes);
//    $url = $td[2]->getAttribute('href');
//    $caption = $td[2]->textContent;
//    
//    
//    return array("url"=>$url,"caption"=>$caption);
//    
//    
//}
//
//function getMagnet($url){
//    global $proxy;
//    $urlMagnet = array();
//    $curl = new CurlUrl($url,$proxy);
//    $page =$curl->read();
//    if($page !== false){
//        $arbre = new DomDocument();
//        @$arbre->loadHTML($page);
//        $elements = $arbre->getElementsByTagName('div');
//        foreach ($elements as $elem) {
//            if($elem->hasAttribute('class')){
//                if("download" === strtolower($elem->getAttribute('class'))){
//                    $urlMagnet = getUrlMagnet($elem);
//                }
//            }
//        }
//   }
//   $curl->close();
//   return $urlMagnet;
//    
//}
//
//function getUrlMagnet(DOMElement $div){
//    
//    $a = new DOMNodeRecursiveIterator($div->childNodes);
//    $url = $a[2]->getAttribute('href');
//    $caption = $a[2]->textContent;
//    
//    
//    return array("url"=>$url,"caption"=>$caption);
//}
