<?php
session_start();
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'init_autoloader.php';


use myClass\Services;
use Parser\plugin\torrent\t411\T411;


$token = "";
$config = Services::loadSettings($token);
$torrent = new T411($config);

$categories = $torrent->getCategories();

foreach ($categories as $cat){
    if(isset($cat['name'])){
        echo "[".utf8_decode($cat['name'])."]<br>";
        echo "0=".$cat['id'];
        echo "<br>";
//        echo "scat : ".count($cat['cats']);
//        echo "<br>";
        foreach($cat['cats'] as $scat){
            echo $scat['id']."=".utf8_decode($scat['name']);
            echo "<br>";
        }
    }
}