<?php
session_start();
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'init_autoloader.php';

use Standard\Web\Request;

/* 
 * page permettant de traiter les demandes du front
 */



echo \myClass\ResponseJson::returnResponse(Request::getQueryString());


