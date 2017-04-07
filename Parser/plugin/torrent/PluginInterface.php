<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\plugin\torrent;

/**
 *
 * @author xgld8274
 */
interface PluginInterface {
    //put your code here
    
    public function getResult();
    
    public function getResultTotalCount();
    
    public function getResultSuccess();
    /**
     * @static recupere les infos inherent au torrent
     */
    public function getInfo($namePlugin);
    
    public function search($motif);
        
    
}
