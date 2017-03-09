
<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$page = file_get_contents("https://www.nextorrent.net/torrents/recherche/the%20flash");
$arbre = new DomDocument();
$arbre->loadHTML($page);
$elements = $arbre->getElementsByTagName('div');
foreach ($elements as $elem) {
    var_dump($elem);
    
}
