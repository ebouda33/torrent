<?php
namespace Standard\Fichier;
/**
 * Class de gestion de fichier
 * 
 * @author Eric ABOUDARAM
 * @since 2015-01-07
 * 
 * 
 */

use Standard\Fichier\Explorer;

class ReaderException extends \Exception {
}
class FileException extends \Exception {
}



class Fichier {
	
	private $path ;
	private $filename ;
	
	private $file;
	
	private $error;
	private $msgError;
	/**
	 * handle du fichier si tulise
	 * @var handle
	 */
	private $handle;
	
	/**
	 * la ligne courante de la lecture du fichier
	 * @var mixed|String
	 */
	private $ligneCourante;
	private $ligneCouranteCsv;
	
	const separator_csv_standard = ";";
        
        const finFichier = false;
	/**
	 * 
	 * @param String $path chemin
	 * @param String $filename nom du fichier
	 
	 */
	public function __construct($path,$filename){
		$this->creerDossier($path);
		$this->path = $path;
		$this->filename = $filename;
		$this->ligneCourante = null;
		$this->handle = null;
		$this->error = false;
		$this->msgError = "";
		$this->file = $this->genererChemin();
	}
	
	
	public function lireFichierDansTableau(){
		$this->revenirAuDebut();
		$lignes = array();
		while($ligne = $this->lireLigneCourante()){
			array_push($lignes, $ligne);
		}
		$this->finalize();
		
		return $lignes;
	}
	
	public function getHandle(){
		return $this->handle;
	}
	
	
	public function nombreLignes(){
		$this->revenirAuDebut();
		$compteur = 0;
		while($this->lireLigneCourante()){
			$compteur++;
		}
		return $compteur;
		$this->finalize();
	}
	/**
	 * 
	 * @param String $dir
	 * @param String $type
	 * @param String $extension
	 * @return array des fichiers
	 */
	public static function listeFichiers($dir,$type,$extension="",$niveau=0){
		
		$mask = "";
		$explorer = new Explorer($dir,$extension,$mask,$niveau,$type);
		
		return $explorer;
	}
	
	
	/**
	 *  ouvre le fichier en lecture seule
	 */
	public function initialize($mode='r'){
		//ouverture du fichier
		self::creerDossier($this->path);
		$file = $this->file;
                if(!$this->presenceFichier()){
                    $handler = fopen($file,$mode);
                     $this->handle = $handler;
                }else{
                    if(is_readable($file)){
                            $handler = fopen($file,$mode);
                            $this->handle = $handler;
                    }else{
                            $this->generatedError("Fichier non lisible ".$file);
                    }
                }
	}
	
	
	/**
	 * @return boolean
	 */
	public function getERROR(){
		return $this->error;
	}
	
	/**
	 * @return String
	 */
	public function getMessageERROR(){
		return $this->msgError;
	}
	
	private function generatedError($message){
		$this->error = true;
		$this->msgError .= $message;
		throw new FileException ("Une erreur est survenue:".$this->msgError);
	}
	
	protected function revenirAuDebut(){
		$this->finalize();
		$this->initialize();
	}
	
	/**
	 * creation de fichier
	 * @param String mode d ouverture 'a+' par defaut (placement pointeur a la fin et cree le fichier si necessaire)
	 * 
	 */
	function creerFichier($mode='a+'){
		fopen($this->file,$mode);
		$this->finalize();
		$this->initialize($mode);
	}
	
	/**
	 * Static creation de dossier recursif
	 * @param String $nom
	 * @return boolean
	 */
	static function creerDossier($nom) {
		if (!empty($nom) && !is_dir($nom)) {
	
			if(is_dir($nom)){
				return true;
			}else{
				$mode = 0775;
				$mk = mkdir($nom,$mode,true) ;
				chmod($nom,$mode);
				return $mk;
			}
		}else{
			if(file_exists($nom))
				return true;
		}
		return false;
	}
	
	
	/**
	 * 
	 * @return string le chemin complet path+filename
	 */
	function genererChemin(){
		$path = self::_validerPath($this->path);
		
		return $path.$this->filename;
	}
	
	static function _validerPath($path){
		if(substr($path, -1) != DIRECTORY_SEPARATOR){
			$path .= DIRECTORY_SEPARATOR;
		}
		
		return $path;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	function presenceFichier(){
	
		return file_exists($this->file);
	}
	
	/**
	 * Lis un fichier en globalite attention a la taille 
	 * @return string si fin de fichier retourne false donc test avec ===
	 */
	function lireFichierEntier(){
		return file_get_contents($this->file);
	}
	
	
	
	function lireLigneCouranteCsv($separateur=";"){
		if(is_resource($this->handle)){
			if (!feof($this->handle))
			{
				$this->ligneCouranteCsv = fgetcsv ($this->handle,0,$separateur);
			}else{
				return false;
			}
				
		}else{
			$this->initialize();
			$this->lireLigneCouranteCsv($separateur);
		}
		return $this->ligneCouranteCsv;
	}
	
	//remove fin de ligne a la lecture
	function lireLigneCourante(){
		if(is_resource($this->handle)){
			if (!feof($this->handle))
			{
				$this->ligneCourante = fgets($this->handle);
			}else{
				return false;
			}
			
		}else{
			$this->initialize();
			$this->lireLigneCourante();
		}
		return $this->ligneCourante;
	}
	
	/**
	 * ecris dans le fichier
	 * @param String $contenu
	 * @param boolean $ouvert conserver ouverture
	 * @return number
	 */
	function ecrireDansFichier($contenu,$ouvert=false){
		if(!is_resource($this->handle)){
			$this->creerFichier();
		}
		$handler = $this->handle;
		$retour = fwrite($handler,$contenu);
		if(!$ouvert)
			$this->finalize();
	
		return $retour;
	}
	
	/**
	 * ecris une ligne dans le fichier
	 * @param String $contenu
	 * @param boolean $ouvert conserver ouverture
	 * @return number
	 */
	function ecrireLigneDansFichier($contenu,$ouvert=false,$convertUTF8=false){
		if($convertUTF8){
			$contenu = utf8_encode($contenu);
		}
		if(!is_resource($this->handle)){
			$this->creerFichier();
		}
		$handler = $this->handle;
		$retour = fwrite($handler,$contenu.PHP_EOL);
		if(!$ouvert)
			$this->finalize();
	
		return $retour;
	}

	function ecrireLigneCSVDansFichier(array $contenu,$delimiter=";",$ouvert=false,$convertUTF8=true){
		
		$ligne = implode($delimiter,$contenu);
		
		return $this->ecrireLigneDansFichier($ligne,$ouvert,$convertUTF8);
	}
	
	/**
	 * copie le fichier vers un endroit le chemin et le nom si omission du nom reprise origine
	 * @param String $dest
	 * @param boolean $ecrase
	 * @return boolean
	 */
	public function copierFichier($dest,$ecrase=false){
		$source = $this->file;
		if(is_dir($dest)){
			$pathDest = $dest;
			$pos = strrpos($source,DIRECTORY_SEPARATOR);
			$dest .= substr($source,$pos,strlen($source));
		}else{
			$pos = strrpos($dest,DIRECTORY_SEPARATOR);
			$pathDest = substr($dest,0,$pos);
		}
	
		// echo "creation dossier:".$pathDest;
		$this->creerDossier($pathDest) ;
		if($ecrase){
			if(file_exists($source)){
				return copy($source,$dest);
			}
			return false;
		}else{
			//verification presence
			if(!file_exists ($dest))
				return copy($source,$dest);
	
	
		}
		
		return false;
	}
	
	static function _copierFichier($source,$dest,$ecrase=false){
		if(is_dir($dest)){
			$pathDest = $dest;
			$pos = strrpos($source,DIRECTORY_SEPARATOR);
			$dest .= substr($source,$pos,strlen($source));
		}else{
			$pos = strrpos($dest,DIRECTORY_SEPARATOR);
			$pathDest = substr($dest,0,$pos);
		}
	
		self::creerDossier($pathDest) ;
		if($ecrase){
			if(file_exists($source) && !is_dir($source)){
				return copy($source,$dest);
			}
			return false;
		}else{
			//verification presence
			if(!file_exists ($dest))
				return copy($source,$dest);
	
	
		}
	
		return false;
	}
	
	/**
	 * Deplace et/ou renomme un fichier
	 * Cette fonction est la meme chose qu'un mv sur linux.
	 * Il ecrasera si le fichier existe deja dans le dossier de destination
	 * @param String $dest
	 * @return boolean 
	 */
	function _moveFichier($dest) {
		$oldname = $this->file;
		return rename($oldname,$dest);
		
	}
	
	
	/**
	 * Deplace un fichier et capable de le renommer pour ne pas ecraser
	 * @param String $dest
	 * @param boolean $ecrase
	 * @return string
	 */
	function deplacerFichier($dest,$ecrase=false){
		$source = $this->file;
		$file = $dest;
		$compteur = 1;
		$reussite = false;
		$this->finalize();
                if(is_dir($dest)){
                    $dest = self::_validerPath($dest).$this->filename;
                }
                    
                
		if(!$ecrase){
			while(is_file($file) == TRUE){
				$file = $dest."_".$compteur;
				$compteur++;
			}
		}
		
	
		$reussite = $this->copierFichier($file,$ecrase);
		if($reussite){
			$reussite = $reussite && $this->effacerFichier($source);
		}
		
	
		return $reussite;
	}
	public static function _deplacerFichier($source,$dest,$ecrase=false){
		$fichier = new Fichier(dirname($source),  basename($source));
                $fichier->finalize();
		$file = $dest;
		$compteur = 1;
		$reussite = false;
	
		if(!$ecrase){
			while(is_file($file) == TRUE){
				$file = $dest."_".$compteur;
				$compteur++;
			}
		}
	
		
		
		$reussite = self::_copierFichier($source,$file,$ecrase);
		if($reussite){
			$reussite = $reussite && self::_effacerFichier($source);
		}
	
	
		return $reussite;
	}
	
	
	/**
	 * Static function recurssif ou pas
	 * @param String $rep
	 * @param boolean $recursif default false
	 * @return boolean
	 */
	public static function _effacerRepertoire($rep,$recursif = false){
		if(is_dir($rep)){
			$liste = self::listeFichiers($rep, "")->toArray();
			if(count($liste) == 0){
				return rmdir($rep);
			}elseif($recursif){
				foreach($liste as $file){
					$path = $file['path'];
					if($file['type'] === Explorer::FILE){
						self::_effacerFichier($path);
					}elseif($file['type'] === Explorer::FOLDER){
						self::_effacerRepertoire($path,true);
					}
				}
				
			}
			
			self::_effacerRepertoire($rep,false);
		}else{
			return false;
		}
	}
	
	
	
	
	/**
	 * Determine si c est un fichier ou un rep a effacer
	 * @param String $cheminComplet
	 * @param Boolean $recursif
	 * @return boolean
	 */
	public static function _efface($cheminComplet,$recursif = false){
		if(is_file($cheminComplet)){
			return self::_effacerFichier($cheminComplet);
		}elseif(is_dir($cheminComplet)){
			return self::_effacerRepertoire($cheminComplet,$recursif);
		}
		
		return false;
	} 
	
	/**
	 * 
	 * @return boolean
	 */
	function effacerFichier(){
		$file = $this->file;
		$compteur = 0;
		while(is_file($file) == TRUE && $compteur < 1)
		{
			
			unlink($file);
			$compteur++;
		}
		return !is_file($file);
	}
	
	
	
	static function _effacerFichier($file){
		$compteur = 0;
		while(is_file($file) == TRUE && $compteur < 1)
		{
				
			unlink($file);
			$compteur++;
		}
		return !is_file($file);
	}
	
	
	static function _effaceFichier($file){
		
		return self::_effacerFichier($file);
	}
        
        function effaceContenuFichier(){
            \file_put_contents($this->genererChemin(), '');
        }
	
	
	/**
	 * @return timestamp
	 */
	public function getDatecreation(){
		$file = $this->file;
		return filectime($file);
	}
	
	public function getNomFichier(){
		return self::obtenirNomFichier($this->file);
	}
	
	public function getNomFichierWithoutExtension(){
		$file = $this->getNomFichier();
		$pos = strripos($file,".");
		if(!$pos){
			$pos = strlen($file);
		}
		return substr($file,0, $pos);
	}
	
	public function getExtension(){
		$name = $this->getNomFichier();
		
		return self::_getExtension($name);
	}
	
	public static function _getExtension($nameFile){
		$pos = strripos( $nameFile,".");
		return substr($nameFile, $pos,strlen($nameFile)); 
	} 
	/**
	 * Static function
	 * @param String $file
	 * @return string
	 */
	static function obtenirNomFichier($file){
		if(!is_null($file) and !empty($file)){
			$file = basename($file);
			return $file;
		}
		return "";
	
	}
	
	/**
	 * Static function
	 * @param String $file
	 * @return String
	 */
	static function obtenirChemin($file){
		if(!is_null($file) and !empty($file)){
	
			return dirname( $file );
		}
		return "";
	}
	
	
	
	public function finalize(){
		if(is_resource($this->handle))
			fclose($this->handle);
		
	}
	
	public function __destruct(){
		$this->finalize();
	}
	
        /**
         * 
         * @param type $pathFichier chemin du fichier
         */
        public static function _getDateFichier($pathFichier){
            if(is_file($pathFichier)){
                return filemtime($pathFichier);
            }
            return null;
        }
	
}


?>