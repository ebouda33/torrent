<?php
namespace Standard\crypt;

use Standard\Fichier\Fichier;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author eric
 */
class Crypter {
    //put your code here
    
    public static function encrypt_file(Fichier $fichier,$key){
        return self::crypt($fichier, $key, true);
    } 
    public static function decrypt_file(Fichier $fichier,$key){
        return self::crypt($fichier, $key, false);
    }
    
    private static function crypt(Fichier $fichier , $key,$crypt){
        $fichierFinal = new Fichier(dirname($fichier->genererChemin()), $fichier->getNomFichierWithoutExtension().".tmp");
        $fichier->initialize();
        $ligne = $fichier->lireLigneCourante();
        while($ligne !== false){
            if($crypt){
                $ligne = self::encrypt($ligne, $key);
            }else{
               $ligne = self::decrypt($ligne, $key); 
            }if($crypt){
                $fichierFinal->ecrireLigneDansFichier($ligne, true);
            }else{
                $fichierFinal->ecrireDansFichier($ligne, true);
            }
            $ligne = $fichier->lireLigneCourante();
        }
        $fichier->finalize();
        $fichierFinal->finalize();
        
        $fichier->effacerFichier();
        $fichierFinal->deplacerFichier(dirname($fichier->genererChemin()).DIRECTORY_SEPARATOR. $fichier->getNomFichier(), true);
        
        return $fichierFinal;
    }
    
    public static function encrypt($string,$crypted_key){
        $key = md5($crypted_key);
	$letter = -1;
	$new_str = '';
	$strlen = strlen($string);

	for ($i = 0; $i < $strlen; $i++) {
		$letter++;
		if ($letter > 31) {
			$letter = 0;
		}
		$neword = ord($string{$i}) + ord($key{$letter});
		if ($neword > 255) {
			$neword -= 256;
		}
		$new_str .= chr($neword);
	}
	return base64_encode($new_str);
    }
    
    public static function decrypt($stringSrc,$crypted_key){
        $private_key = md5($crypted_key);
	$letter = -1;
	$new_str = '';
	$string = base64_decode($stringSrc);
	$strlen = strlen($string);
	for ($i = 0; $i < $strlen; $i++) {
		$letter++;
		if ($letter > 31) {
			$letter = 0;
		}
		$neword = ord($string{$i}) - ord($private_key{$letter});
		if ($neword < 1) {
			$neword += 256;
		}
		$new_str .= chr($neword);
	}
	return $new_str;
    }
            
}
