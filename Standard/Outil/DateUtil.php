<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Standard\Outil;

use DateInterval;
use DateTime;
use Exception;

/**
 * Description of DateUtil
 *
 * @author xgld8274
 */

class DateUtilException extends Exception{
};

class DateUtil extends DateTime{
    
    private static $joursFeries ;
    
    /**
     * 
     * @param type $time
     * @param type $object
     * @param boolean $withHour si l on veut une date à 00:00:00
     */
    public function __construct($time = null, $object = null,$withHour = true) {
        
        parent::__construct($time, $object);
        
        if(!$withHour){
            $this->setTime(0, 0, 0);
        }
    }
    public static function createFromFormat($format, $time=null, $object=null) {
        if(is_null($object)){
            $date = parent::createFromFormat($format, $time);
        }else{
            $date = parent::createFromFormat($format, $time,$object);
        }
        
        
        return new DateUtil($date->format('Y-m-d H:i:s'),$object);
    }

    
    /**
     * Permet de comparer deux date entre elles sans les heures ou avec sur la meme journee
     * si la date est plus grande que la date en parametre return 1
     * si egale 0
     * sinon -1
     * @param DateTime $date
     * @return bit comparaison 1,0,-1
     * 
     */
    public function compareTo(DateTime $date ,$withHour = false){
        $comp = -1;
        if($date->format("d") == $this->format("d") && $date->format("m") == $this->format("m") && $date->format("Y") == $this->format("Y")){
            $comp = 0;
            if($withHour){
                if($this->getTimestamp() > $date->getTimestamp()){
                    $comp = 1;
                }elseif($this->getTimestamp() < $date->getTimestamp()){
                    $comp = -1;
                }
            }
	}else{
            if($this->getTimestamp() > $date->getTimestamp()){
                $comp = 1;
            }
        }
        
        return $comp;
        
    }

    
    public function formatToMysql($withHour = true){
        $format = "Y-m-d H:i:s";
        if(!$withHour){
            $format = "Y-m-d";
        }
        return $this->format($format);
    }
    
    
    public function ajouterXJours($jour){
        if(is_int($jour)){
            $intervalFormat = 'P'.str_replace("-", "",$jour).'D';
            $interval = new DateInterval($intervalFormat);
            if($jour> 0){
                $this->add($interval);
            }else{
                
                $this->sub($interval);
            }
        }else{
            throw new DateUtilException('nombre de jour doit etre un entier');
        }
        return $this;
    }
    
    /**
	 * 
	 * @param \DateUtil $jour
	 * @return bool
	 */
	public static function  __isFerie(DateUtil $jour){
            	$year = $jour->format("Y");
		//parcours le tableau des jours feries
		$tabferie = DateUtil::getJoursFeries($year);
		$trouve = false;
		$i = 0;
		while(!$trouve && $i < count($tabferie)){
			$j = $tabferie[$i];
                        $trouve = ($j->compareTo($jour) === 0);
			$i++;
		}
		return $trouve;
	}
        
        
        public function isFerie(){
            
//            var_dump($this);
            return self::__isFerie($this);
        }
        
    
    public static function getJoursFeries($year = null){
        return self::definirJourFerie($year);
    }
    
    /**
	 * 
	 * @param int $year
	 * @return array tableau des jours feries
	 */
	private static function definirJourFerie($year=null){
                if(is_null(self::$joursFeries)){
                    if(is_null($year)  || !is_numeric($year)){
                            $year = date('Y');
                    }
                    $tab = array();
                    $format = 'd-m-Y';
                    //date fixe
                    //nouvel an
                    array_push($tab, DateUtil::createFromFormat($format,"01-01-".$year));
                    //1 mai
                    array_push($tab, DateUtil::createFromFormat($format,"01-05-".$year));
                    //8 mai
                    array_push($tab, DateUtil::createFromFormat($format,"08-05-".$year));
                    //14juillet
                    array_push($tab, DateUtil::createFromFormat($format,"14-07-".$year));
                    //15 aout
                    array_push($tab, DateUtil::createFromFormat($format,"15-08-".$year));
                    //1 novembre
                    array_push($tab, DateUtil::createFromFormat($format,"01-11-".$year));
                    //11 novembre
                    array_push($tab, DateUtil::createFromFormat($format,"11-11-".$year));
                    //noel
                    array_push($tab, DateUtil::createFromFormat($format,"25-12-".$year));

                    //date fluctuante
                    //paques lundi
                    $paques = DateUtil::createFromFormat($format,date($format,easter_date($year)));
                    $paques->ajouterXJours(1);
                    array_push($tab, $paques);
                    //ascencion +38 de paques
                    $ascension = clone $paques;
                    $ascension->ajouterXJours(38);
                    array_push($tab, $ascension);
                    //pentecote
                    $pentecote = clone  $paques;
                    $pentecote->ajouterXJours(49);
                    array_push($tab, $pentecote);

                    self::$joursFeries = $tab;
                }
                
            return self::$joursFeries;   
	}
        
        
        public function __toString() {
            return $this->format("Y-m-d H:i:s");
        }
        
        public static function convert(DateTime $date){
            $format = "Y-m-d H:i:s";
            return DateUtil::createFromFormat($format,$date->format($format));
        }

    
}
