<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myClass;

/**
 * Description of Services
 *
 * @author eric
 */
class Services {
    //put your code here
    //determiner l environnement
    public static function getEnvironnement(){
        return 'prod';
        
    }
    public static function authentification($user,$pwd){
        $env = self::getEnvironnement();
        $table = new \model\UsersTable(new MyBDD($env));
        $token = $table->authentification($user, $pwd);
        return $token;
    }
}
