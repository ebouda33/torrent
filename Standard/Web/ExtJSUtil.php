<?php


namespace Standard\Web;

use Standard\Fichier\Explorer;

class ExtJSUtil {
	
	
	static function transformExplorerToExtTree(Array $data ){
		$retour = array();
		foreach($data as $key => $value){
			$data = array();
			$data['id'] = $value['path'];
			$data['text'] = $value['name'];
			$data['name'] = $value['name'];
			$data['path'] = $value['path'];
			$data['leaf'] = $value['type']==Explorer::FILE?true:false;
				
			array_push($retour,$data);
		}
		
		
		return $retour;
		
	}
	
}
