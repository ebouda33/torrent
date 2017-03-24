<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Standard\SQL;

use Standard\SQL\BDD;

/**
 * Description of Model
 *
 * @author xgld8274
 */
class Model {
    //put your code here
    const INSERT = 'INSERT';
    const DELETE = 'DELETE';
    const UPDATE = 'UPDATE';
    const SELECT = 'SELECT';
    
    const SQL_STAR = "*"; 
    const WHERE = "where";
    const GROUP = "group";
    const HAVING = "having";
    const ORDER = "order";
    
    private $bdd ;
    
    function __construct(BDD $bdd) {
        $this->bdd = $bdd;
    }
    
    
    function execute($sql,$attr = BDD::FETCH_ASSOC){
        if(stripos(self::INSERT, $sql) === 0 || stripos(self::DELETE, $sql) === 0 || stripos(self::UPDATE, $sql) === 0 ){
            return $this->bdd->exec($sql);
        }
        
        return $this->bdd->fetchAll($sql,$attr);
        
        
    }

}
