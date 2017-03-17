<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\plugin\torrent;

use Standard\Fichier\Explorer;

/**
 * Description of TorrentGenerique
 *
 * @author xgld8274
 */
abstract class TorrentGenerique implements TorrentInterface{
    //put your code here
    protected $id;
    protected $icone;
    protected $description;
    protected $name;
    
    public static function getListe(){
        $explorer = new Explorer(dirname(__FILE__));
        $result = array();
        foreach ($explorer->toArray() as $explore){
            if($explore['type'] === Explorer::FOLDER){
                array_push($result, array('name'=> strtoupper($explore['name'])));
            }
        }
        return $result;
    }
    public function getInfo() {
        return array('name'=>$this->name,'id'=>$this->id,'icone'=>$this->icone,'description'=>$this->description);     
    }
    public function getResult() {
        throw new \Exception('Not yet Implemented');
    }

    

}
