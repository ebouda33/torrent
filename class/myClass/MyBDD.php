<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myClass;

use Standard\Fichier\ReaderIni;
use Standard\SQL\BDD;

/**
 * Description of MyBDD
 *
 * @author xgld8274
 */
class MyBDD extends BDD{
    //put your code here
    const BDD = 'mytorrent';
    
    public function __construct($env='prod') {
        //getconfig BDD
        $env = (stripos(\Standard\Web\Request::getURI(),'mytorrent.nabous.fr') !== false)?'mytorrent':$env;
        $file = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR. 'config'.DIRECTORY_SEPARATOR.'config_general.ini';
        
        $ini = ReaderIni::read($file);
        parent::__construct($ini, $env, self::BDD);
    }
}
