<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Parser\nextorrent;

/**
 * Description of Nextorrent
 *
 * @author xgld8274
 */
class Nextorrent {
    private $url = 'https://www.nextorrent.net';
    private $urlSearch;
    public function __construct($search) {
        $this->urlSearch =$this->url. '/torrents/recherche/';
    }
}
