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
                $classname = self::getClassName($explore['name'] );
                $torrent = new $classname();
                
                $fichier = new \Standard\Fichier\Fichier($explore['path'].DIRECTORY_SEPARATOR,'iconeBase64.php');
                $torrent->id = $fichier->getDatecreation().$explore['name'];
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
    
    public function getResultSuccess() {
        throw new \Exception('Not yet Implemented');
    }

    public function getResultTotalCount() {
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
    
    public static function getPluginClassName($id){
        $plugins = self::getListe();
        $classname = "";
        foreach ($plugins as $plugin){
            if($plugin['id'] === $id){
                $classname = self::getClassName($plugin['name']);
            }
        }
        return $classname;
    }

    private static function getClassName($namePlugin){
        $name = strtolower($namePlugin);
        $classname = 'Parser\\plugin\\torrent\\'.$name . '\\'.\ucfirst($name);
        return $classname;
    }
    
    public function search($motif,array $options=null){
        throw new Exception('Not Yet Implemented');
    }

}
