<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace model;
/**
 * Description of UsersTable
 *
 * @author eric
 */
class UsersTable extends \Standard\SQL\Model{
    //put your code here
    
    
    /**
     * 
     * @param type $user
     * @param type $pwd
     * @return string TOKEN
     */
    public function authentification($user,$pwd){
        $select = "select token from users where email = :email and password = md5(:pwd)";
        
        $res = $this->execute($select, array(':email'=>$user,':pwd'=>$pwd));
        
        if(count($res)>0){
            return $res[0]['token'];
        }
        return null;
    }
}
