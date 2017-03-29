<?php

use Standard\crypt\OpenSSLClass;
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR .'init_autoloader.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$keyfile = dirname(__FILE__).DIRECTORY_SEPARATOR. 'keypass.bin';
OpenSSLClass::generateKeyPubFile($keyfile);


$fileread = dirname(__FILE__).DIRECTORY_SEPARATOR. 'app.js';
$fileoutput = dirname(__FILE__).DIRECTORY_SEPARATOR. 'eric.crypt';
$fileoutputdecrypt = dirname(__FILE__).DIRECTORY_SEPARATOR. 'eric.decrypt';
OpenSSLClass::cryptFile($fileread, $keyfile, $fileoutput);
OpenSSLClass::decryptFile($fileoutput, $keyfile, $fileoutputdecrypt);