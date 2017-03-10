<?php
namespace config;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


use Standard\Fichier\Fichier;

/**
 * Description of ConfigReader
 *
 * @author xgld8274
 */
class ConfigReader {
   //put your code here
    private $config = array();
    
    public function __construct(Fichier $file) {
        $this->config = array();
        if(!$file->presenceFichier() && $file->getExtension() === '.ini'){
            $file->finalize();
            $file = new \Standard\Fichier\Fichier(dirname($file->genererChemin()), $file->getNomFichierWithoutExtension().'.crypt');
        }

        $this->read($file);
    }
    
    private function read(Fichier $filesrc){
        $file = $this->decryptFile($filesrc);
        //le fichier doit etre lisible pour passer
       while(($ligne = $file->lireLigneCouranteCsv(":")) !== Fichier::finFichier){
            for($i=2;$i < count($ligne);$i++){
                 $ligne[1] .= ':'.$ligne[$i];
            }
            if(count($ligne) >= 2){
                $this->config[$ligne[0]] = $ligne[1];
            }
        }
        if(count($this->config) === 0){
            throw new Exception('Configuration erroné controle votre fichier.');
        }
        
        $this->cryptFile($file);
    }
    
    public function __get($name){
        if(count($this->config) === 0){
            return null;
       }
        if(isset($this->config[$name])){
            return $this->config[$name];
        }
        
        return null;
    }
    
    public function getConfig(){
        return $this->config;
    }
    
    private function cryptFile(Fichier $file){
        if($file->getExtension() === '.ini'){
            $this->encode_decode($file,true,'.crypt');
        }
    }
    private function decryptFile(Fichier $file){
     if($file->getExtension() === '.crypt'){
            return $this->encode_decode($file,false,'.ini');
        }
       return $file;
    }
    
    
    private function encode_decode($file,$encode,$ext){
       $contenu = $file->lireFichierDansTableau();
        $contenuE = array();
        
        foreach ($contenu as $ligne){
            if($encode){
                array_push($contenuE, base64_encode($ligne));
            }else{
                array_push($contenuE, base64_decode($ligne));
            }
        }
        $file->effaceContenuFichier();
        foreach ($contenuE as $ligne){
            if($encode){
                $file->ecrireLigneDansFichier($ligne,true);
            }else{
                $file->ecrireDansFichier($ligne,true);
                
            }
        }
        $file->finalize();
        
        $file->deplacerFichier(Fichier::_validerPath(dirname($file->genererChemin())).$file->getNomFichierWithoutExtension().$ext);
        return new Fichier(Fichier::_validerPath(dirname($file->genererChemin())),$file->getNomFichierWithoutExtension().$ext);
        
    }
}
