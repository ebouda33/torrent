<?php

/*
 * class gerant les resultats de torrent
 */

namespace Parser\plugin\torrent;

/**
 * Description of PluginResults
 *
 * @author xgld8274
 */
class PluginResults extends \ArrayObject{
    //put your code here
    private $id;
    
    private $title;
    
    private $size;
    
    private $magnet;

    private $leecher;

    private $seeder;
    
    private $category;
    
    
    
//    private $index = ['id','title','size','url','leecher','seeder','category'];
    
    public function offsetSet($name, $value) {
        if($this->offsetExists($name)){
            parent::offsetSet($name, $value);
        }else{
            throw new PluginException('Parametre inconnu ['.$name.'] dans '.__CLASS__);
        }
        
    }
    
    public function offsetExists($index) {
        return property_exists($this,$index);
    }
    
    public function offsetGet($name) {
        if($this->offsetExists($name)){
            return parent::offsetGet($name);
        }else{
            throw new PluginException('Parametre inconnu ['.$name.'] dans '.__CLASS__);
        }
    }
    
    public function __set($name, $value) {
        $this->offsetSet($name, $value);
    }
    
    public function __get($name) {
        return $this->offsetGet($name);
    }
}
