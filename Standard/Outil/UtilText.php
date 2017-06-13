<?php
namespace Standard\Outil;

/**
 * 
 * @author xgld8274
 * class util pour tout ce qui est formatage de texte
 *
 */
class UtilText {
	static private $listeUnite = array('Octets','Ko','Mo','Go','To'); 
	
	static function afficherTailleFichier($taille){
		$texte  = "";
		$taille = floatval($taille);
		$index = 0;
		
		while($taille > 1024 && $index+1 < count(self::$listeUnite)){
			$taille = round( $taille / 1024,2);
			$index++;
		}
		
		return $taille . " ".self::$listeUnite[$index];
		
	}
	
}

?>