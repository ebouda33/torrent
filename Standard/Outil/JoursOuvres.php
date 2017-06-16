<?php
namespace Standard\Outil;


/**
 * 
 * @author xgld8274
 * @abstract permettant de connaitre les jours feries 
 * @see ou de savoir si notre jour en est un
 * ou de connaitre le prochain jour ouvre 
 * ne tient pas compte des heures
 */
abstract class  JoursOuvres {

	const LUNDI = 1;
	const MARDI = 1;
	const MERCREDI = 1;
	const JEUDI = 1;
	const VENDREDI = 1;
	const SAMEDI = 0;
	const DIMANCHE = 0;
	
	private static $semainier = [null,self::LUNDI,self::MARDI,self::MERCREDI,self::JEUDI,self::VENDREDI,self::SAMEDI,self::DIMANCHE];
	
	
	
	/**
	 * 
	 * @param \DateUtil $jour
	 * @return bool
	 * @see return si le jour est ouvre
	 */
	public static function estValide(DateUtil $jour){
		//permet de savoir si ou non il est ouvre
		
		//1 est ce le week-end samedi - dimanche
		$joursemaine = $jour->format("N");
                if(self::$semainier[$joursemaine]){
			//est il ferie ?
			if($jour->isFerie()){
				return !self::$semainier[$joursemaine];
			}else{
				return self::$semainier[$joursemaine];
			}
		}else{
			return self::$semainier[$joursemaine];
		}
		
		return false;
	}
	
	
	
	/**
	 * 
	 * @param int $year
	 * @return array
	 * @see definirJourFerie
	 */
	public static function listeFerie($year=null){
		
		return DateUtil::getJoursFeries($year);
	}
	
	/**
	 * 
	 * @param \DateUtil $jour
	 * @return  \DateUtil
	 * @see retourne le prochain jour ouvre ou lui meme si c est bon
	 */
	public static function obtenirJourOuvre(DateUtil $jour){
              while(!self::estValide($jour)){
                    $jour->ajouterXjours(1);
		}
		return $jour;
	}
}

?>