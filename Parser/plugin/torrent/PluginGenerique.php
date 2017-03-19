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
abstract class PluginGenerique implements PluginInterface{
    //put your code here
    protected $id;
    protected $icone;
    protected $description;
    protected $name;
    protected $options=array();
    
    public static function getListe(){
        $explorer = new Explorer(dirname(__FILE__));
        $result = array();
        foreach ($explorer->toArray() as $explore){
            if($explore['type'] === Explorer::FOLDER){
                $classname = 'Parser\\plugin\\torrent\\'.$explore['name'] . '\\'.ucfirst($explore['name']);
                $torrent = new $classname();
                
                $fichier = new \Standard\Fichier\Fichier($explore['path'].DIRECTORY_SEPARATOR,'iconeBase64.php');
                $torrent->id = $fichier->getDatecreation();
                $torrent->icone =  $fichier->lireFichierEntier();
                array_push($result, $torrent->getInfo());
                
                $torrent->__destruct();
            }
        }
        return $result;
    }
    public function getInfo() {
        
        return array('name'=> $this->name,'id'=>$this->id,'icone'=>$this->icone,'description'=>$this->description);     
    }
    public function getResult() {
        throw new \Exception('Not yet Implemented');
    }
    
    
    /**
     * permet de savoir si un torrent a des options comme les categories etc ...
     */
    public function getOptions(){
        return $this->options;
    }
    
    public function __destruct() {
        unset($this);
    }

    

}
