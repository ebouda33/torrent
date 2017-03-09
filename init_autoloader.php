<?php


chdir(dirname(__FILE__));
if (file_exists('class/AutoLoader.php')) {
    $loader = require_once 'class/AutoLoader.php';
}


//definition des namespaces 
$config = array("ns"=>array(
                "Parser"=>dirname(__FILE__).DIRECTORY_SEPARATOR,
                "Transmission"=>dirname(__FILE__).DIRECTORY_SEPARATOR,    
));
Autoloader::register($config);

