<?php
session_start();
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init_autoloader.php';

use myClass\ResponseJson;
use Standard\Fichier\Fichier;
use Standard\Web\Request;

set_time_limit(120);
/* 
 * page permettant de traiter les demandes du front
 */

//$file = new Fichier(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR,'config_appli.ini');
//$query = Request::getQueryString();


echo ResponseJson::returnResponse();


