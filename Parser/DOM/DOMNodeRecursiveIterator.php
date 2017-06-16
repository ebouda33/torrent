<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\DOM;

use ArrayIterator;
use DOMNodeList;
use RecursiveIterator;
use RecursiveIteratorIterator;

/**
 * Description of DOMNodeRecursiveIterator
 *
 * @author xgld8274
 */
class DOMNodeRecursiveIterator extends ArrayIterator implements RecursiveIterator {
 
  public function __construct (DOMNodeList $node_list) {
   
    $nodes = array();
    foreach($node_list as $node) {
      $nodes[] = $node;
    }
   
    parent::__construct($nodes);
   
  }
 
  public function getRecursiveIterator(){
    return new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);
  }
 
  public function hasChildren () {
    return $this->current()->hasChildNodes();
  }

 
  public function getChildren () {
    return new self($this->current()->childNodes);
  }
  
}
