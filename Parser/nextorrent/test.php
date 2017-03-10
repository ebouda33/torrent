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
    $transmission = new TransmissionRPC('http://xxxxx:9091/rpc', 'xxxxx', 'xxx');
    
    echo $transmission->GetSessionID();
    
    $torrent_location = $resultat[0]['url'];
    $result =  $transmission->add($torrent_location,'/mnt/data/download');
    $id = $result->arguments->torrent_added->id;
    print "ADD TORRENT TEST... [{$result->result}] (id=$id)\n";
//    
} catch (Exception $ex) {
    echo $ex;
}

