<?php
namespace Standard\Fichier;
/***
  ABOUDARAM ERIC
  November 2013
  Function qui permet de parser un repertoire et de recuperer ce que l on veut
  
  3 @params
    @path string
    @extension string optionnal
    @mask filename optionnal
    
    @retour string (JSON, ObjectPHP,ARRAYPHP)
**/


class Explorer {
  private $erreur = true;
  private $message = "Pas parcouru....";
  private $path = "";
  private $extension = "";
  private $mask = "";
  private $niveau = 0;
  private $retour = self::JSON;
  private $liste = array();
  const JSON = 1;
  const ObjectPHP = 2;
  const ARRAYPHP = 3;
  const FOLDER = 'folder';
  const FILE = 'file';
  
  public function __construct($path,$extension="",$mask="",$niveau = "" ,$retour=self::JSON){
    $this->path = $path;
    $this->extension = $extension;
    $this->mask = $mask;
    
    if(!empty($niveau) )
      $this->niveau = $niveau;
    
    // echo "Recusrif sur :".$this->niveau ." niveaux <br>";
    
    if(!empty($retour) )
      $this->retour = $retour;
    // echo "$path<br>";
    if(empty($this->path)){
      $this->message .= "Aucun path préciser";
    }else{
      $this->run();
    }
    
    return $this;
  }
  
  private function run(array $params = array()){
    $this->message = "";
    // echo 'run<br>';
    
    $this->parcours($this->path,0);
  }
  
  private function parcours($path,$niveau){
    if(substr($path,0,1) != DIRECTORY_SEPARATOR && strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
      $path = DIRECTORY_SEPARATOR.$path;
//     echo $rootPath.$path."<br>";
	if(file_exists($path)){
	    $list = scandir($path);
	    if($list !== false){
	      array_splice($list,0,2);
	      // var_dump($list);
	      $this->erreur = false;
	      foreach($list as $value){
	        
	        if(is_dir($path.DIRECTORY_SEPARATOR.$value))
	          $type = "folder";
	        else if(is_file($path.DIRECTORY_SEPARATOR.$value))  
	          $type = "file";
	        else
	          $type = "";
	        
	        if($this->accepteFichier($value)){
	        	if(substr($path, -1) != DIRECTORY_SEPARATOR){
	        		$path = $path.DIRECTORY_SEPARATOR;
	        	}	
	          array_push($this->liste,array("type"=>$type,"path"=>$path.$value,"name"=>$value));
	        }	        
	        if($niveau < $this->niveau){
	          
	          if($type == "folder"){
	            //recursif
	            $this->parcours($path.$value,$niveau+1);
	          }
	        }
	      
	      }  
	      
	      
	    }else{
	      // echo $rootPath.$path;
	      $this->message .= "List impossible sur ce repertoire.".$path;
	    }
	}else{
		$this->message .= "List impossible sur ce repertoire inexistant.".$path;
	}
  }
  
  private function accepteFichier($fichier){
    $valide = true;
    //controle de l extension si demande
    if(!empty($this->extension)){
      $extension  = strrchr($fichier,".");
      $valide = $valide && (".".$this->extension == $extension);
    }
    if(!empty($this->mask)){
      // echo $fichier ." VS ".$this->mask;
      
      $pos = strpos($fichier,$this->mask);
      // echo " index:".$pos;
      if($pos !== false)
        $valide = $valide && true;
      else
        $valide = $valide && false;
    }
    
    return $valide;
  }
  
  public function toJson(){
  	$metaData = array('root'=> 'results', 'id'=> 'id','messageProperty'=>'message', 'totalProperty'=> 'total', 'successProperty'=> 'success');
  	$fields = array(array("name"=>"path","type"=>"string"),array("name"=>"name","type"=>"string"),array("name"=>"type","type"=>"string"));
  	$metaData['fields'] = $fields;
  	$list = array();
  	$list["metaData"] = $metaData;
  	$list['message'] = $this->getMessageError();
  	$list['success'] = !$this->getError();
  	$list['total'] = 0;
  	if($this->getError()){
  		$list['message'] = $this->getMessageError();
  	}else{
  		$list['results'] = $this->liste;
  		$list['total'] = count($list['results']);
  	
  	}
    return json_encode($this->liste);
  }
  public function toArray(){
    return $this->liste;
  }
  public function toObject(){
    return $this;
  }
  
  public function typeRetour(){
    
    return $this->retour;
  }
  
  public function getMessageError(){
    if($this->erreur){
      switch($this->retour){
        case self::JSON:
          return $this->message;
        case self::ObjectPHP:
          return $this->message;
        case self::ARRAYPHP:
          return $this->message;
      }
      return null;
      
    }
    return "";
  }

  public function getError(){
    return $this->erreur;
  }

  public function getRetour(){
    switch($this->retour){
      case self::JSON:
        return $this->toJson();
      case self::ObjectPHP:
        return $this->toObject();
      case self::ARRAYPHP:
        return $this->toArray();
    }
    return null;
  }
  
  public function __toString(){
    $texte = "Liste des fichiers:";
    foreach($this->liste as $value){
      $texte .= $value."," .PHP_EOL;
    }
    $texte = substr($texte,0,-2);
    
    return $texte;
  }
}



?>