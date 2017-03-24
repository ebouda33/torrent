<?php
namespace Standard\SQL;

use Exception;
/***
 * Outil pour manipuler du sql
 * 
 */


class SQL {

	
	
	
	public static function executeQueryCount(BDD $adapter, $query,array $having=null){
		$modele = new Model( $adapter);
                $queryC = SQL::generateQueryCount($query,$having);
                
		try{
			$res = $modele->execute($queryC,BDD::FETCH_OBJ);
			
			/** attention dans le cas des group by on n a pas forcement de lignes retournes ou plusieurs **/
			$queryTrunk = self::trunkQuery($query);
                        
//                        echo "#".$queryTrunk;
                        
			$groupby = self::coherenceMotSQL($queryTrunk, Model::GROUP);
				
			if($groupby){
				return count($res);
			}
			if(empty($res)){
				return 0;
			}elseif(count($res) > 1 ){
				return count($res);
			}
			return $res[0]->total;
		}catch(InvalidQueryException $e){
			throw $e;
		}catch(Exception $e){
			throw $e;
		}
	
		return 0;
	}
	
	public static function coherenceMotSQL($query,$word){
		$query = preg_replace('/\(.*\)/U', '', $query);
		return strripos($query, $word);
	}
	
	private static function removeText($texte,$pos1,$pos2){
            $sortie = "";
            
            $sortie = substr($texte,0,$pos1);
            $sortie .= substr($texte,$pos2+1);
           
            return $sortie;
	}
	
	public static function trunkQuery($query){
            $requete = $query;
            $indice = 0;
            $fin = strlen($query);
            $pos1 = 0;
            $pos2 = 0;
            $passage = 0;
//            echo "origine : ".$query;
//            echo "<br>";
            while($indice < $fin){
                $bracket = strripos($requete, "(",$indice);
                if($bracket !== false){
                    $pos1 = $bracket;
                    $bracket = stripos($requete, ")",$pos1);
                    $pos2 = $bracket;
                   $requete = self::removeText($requete, $pos1, $pos2);
                   
                }else{
                    $indice = $fin;
                }
            }
            
            return $requete;
/**          return preg_replace('#\((?>[^()]|(?R) +)*\)#U','', $query); **/
	}
	
	public static function generateQueryCount($query,array $having = null){
		$txtr = "";
		if(!empty($having)){
			foreach ($having as $val){
				if(!empty($val)){
					$txtr .= ",".$val;
				}
			}
		}
		return self::modifierQuery($query,"count(*) as total ".$txtr);
	}

	
	/**
	 * Permet de mettre les colonnes desir� dans la requete template
	 * @param string $liste
	 * @throws Exception
	 * @return string requete ou jette une exception si incorrecte
	 */
	protected static function modifierQuery($query,$liste){
		$select = trim($query);
		$wSelect = Model::SELECT;
//		$wAll = Model::SQL_STAR;
		$wFROM = "FROM";
		$wFIN = ";";
//		$wWhere = Model::WHERE;
//		$wGROUP = Model::GROUP;
//		$wHaving = Model::HAVING;
//		$wOrder = Model::ORDER;
		
		
		//elimination du carac ; si il existe
		if(substr($select, -1) == $wFIN){
			$select = substr($select, 0,-1);
		}
		
		$posFrom = stripos($select, $wFROM);
//		$posWhere = (stripos($select, $wWhere) !== false)?stripos($select, $wWhere):strlen($select);
//		$posOrder = stripos($select, $wOrder);
		
		
//		$posWhere = ($posWhere !== false)?$posWhere:strlen($select);
//		$posOrder = ($posOrder !== false)?$posOrder:strlen($select);
		
		
		if($posFrom !== false){
			//position entre select et from
		
			$depart = strlen($wSelect);
			$longueur = $posFrom - $depart;
			$colonnes = trim(substr($select,$depart ,$longueur));
		
			
			if(empty($colonnes) || $colonnes === "*"){
				$select = str_replace($colonnes,$liste , $select);
			}elseif(stripos( $liste,"count") !== false){
				$select = str_replace($colonnes,$liste , $select);
			}elseif(stripos( $colonnes,"count") !== false){
				$select = str_replace($colonnes,$liste, $select);
			}

		}
		else{
			throw new Exception("Erreur sur requete select incorrect ".$select);
		}
		
		return $select;
	}
}

?>