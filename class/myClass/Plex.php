<?php
/***
	Classe permettant de connaitre les films present dans un plex en vrai et dans sa bibliotheque
**/


namespace myClass;

use Standard\Fichier\Explorer;


class Plex {
	
	
	const PATH_PLEX = "plex_path_files";
	
	public function getFiles($path){
		$explorer = new Explorer($path);
		
		return $explorer->toArray();
	}
	
}
