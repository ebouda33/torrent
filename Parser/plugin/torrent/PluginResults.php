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
    
    private $name;
    
    private $title;
    
    private $size;
    
    private $magnet;

    private $leecher;

    private $seeder;
    
    private $category;
    
    private $categoryLabel;
    
    
//    private $index = ['id','title','size','url','leecher','seeder','category'];
    public function __construct($input = []) {
        parent::__construct($input,0,"ArrayIterator");
//        var_dump($input);
//        foreach ($input as $name => $value) {
//            parent::offsetSet($name, $value);
//            $this->offsetSet($name, $value,true);
//        }
    }


    public function offsetSet($name, $value,$force = false) {
        
        if($this->offsetExists($name)){
            parent::offsetSet($name, $value);
        }else{
            exit(0);
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
