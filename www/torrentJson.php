<?php
session_start();
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init_autoloader.php';

use myClass\ResponseJson;

set_time_limit(120);
/* 
 * page permettant de traiter les demandes du front
 */

//$file = new Fichier(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR,'config_appli.ini');
//$query = Request::getQueryString();

//connaitre si l on veut un json ou header dl
$action = filter_input(INPUT_GET, ResponseJson::$ACTION);
if($action === ResponseJson::$DOWNLOAD){
    $filename = ResponseJson::getDetails()->name;
    
    header('Content-Type: application/force-download');
    // Il sera nommé downloaded.pdf
    header('Content-Disposition: attachment; filename="'.$filename.'.torrent"');
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé
    
    echo ResponseJson::returnTorrent();
}else{
    echo ResponseJson::returnResponse();
}

