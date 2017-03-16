<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Standard\Web;

/**
 * Description of Request
 *
 * @author xgld8274
 */
class Request {
    //put your code here
    
    public static function getQueryString(){
        return $_SERVER['QUERY_STRING'];
    }
}
