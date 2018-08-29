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
        $select = "select token,name from users where email = :email and password = md5(:pwd)";

        $res = $this->execute($select, array(':email'=>$user,':pwd'=>$pwd));
        
        if(count($res)>0){
            return $res[0];
        }
        return null;
    }
    
    public function getEmail($token){
        $select = "select email from users where token=:token";
        
        $res = $this->execute($select, array(':token'=>$token));
        
        if(count($res)>0){
            return $res[0]['email'];
        }
        return null;
    }
}
