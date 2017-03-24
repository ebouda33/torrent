<?php
namespace Standard\SQL;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Exception;
use PDO;
use Standard\SQL\Metadata\Source\MysqlMetadata;
use Standard\SQL\PlatformMySQL;
use Standard\Util;


/**
 * Description of BDD
 *
 * @author xgld8274
 */
class BDD extends PDO{
    const ADAPTER          = 'db';
	const SCHEMA           = 'schema';
	const NAME             = 'name';
	const PRIMARY          = 'primary';
	const PRIMARY_KEY      = 'primary key';
	const COLS             = 'cols';
	const METADATA         = 'metadata';
	const METADATA_CACHE   = 'metadataCache';
	const ROW_CLASS        = 'rowClass';
	const ROWSET_CLASS     = 'rowsetClass';
	const REFERENCE_MAP    = 'referenceMap';
	const DEPENDENT_TABLES = 'dependentTables';
	const SEQUENCE         = 'sequence';
	
	const COLUMNS          = 'columns';
	const REF_TABLE_CLASS  = 'refTableClass';
	const REF_COLUMNS      = 'refColumns';
	const ON_DELETE        = 'onDelete';
	const ON_UPDATE        = 'onUpdate';
	
	const CASCADE          = 'cascade';
	const RESTRICT         = 'restrict';
	const SET_NULL         = 'setNull';
	
	const AUTOINCREMENT 	= 'auto_increment';
	
	const CURSOR 			= 'CURSOR';
    //put your code here
    private $dsn;
    private $dbName;
    private $driver;
    private $platform;
    private $metadata;
    private $username;
    
    public function __construct($ini,$env,$dbname) {
        if(empty($env) || empty($dbname)){
            throw new Exception("Erreur dans les params d acces a la BDD");
        }
        $params = self::getParameters($ini,$env);
        
        $username   = $params['username'];
        $passwd     = $params['passwd'];
        $this->driver = $params['driver'];
        $host       = $params['host'];
        $port       = $params['port'];
//        $dbname     = "";
        $options    = null;
        
        $this->dsn = $this->driver . ':host='.$host.';port='.$port.';dbname='.$dbname;
        $this->dbName = $dbname;
        $this->username = $username;
        parent::__construct($this->dsn, $username, $passwd, $options);
        $this->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
        $this->platform = new PlatformMySQL($this);
    }
    
    function getDsn() {
        return $this->dsn;
    }

        
    private static function getParameters($ini,$name){
        $username   = isset($ini[$name]['user_root'])?$ini[$name]['user_root']:$ini['user_root'];
        $passwd     = isset($ini[$name]['pwd_root'])?$ini[$name]['pwd_root']:$ini['pwd_root'];
        $driver     = isset($ini[$name]['db_driver'])?$ini[$name]['db_driver']:$ini['db_driver'];
        $host       = isset($ini[$name]['host'])?$ini[$name]['host']:$ini['host'];
        $port       = isset($ini[$name]['db_port'])?$ini[$name]['db_port']:$ini['db_port'];
        
        return array("username"=>$username,"passwd"=>$passwd,"driver"=>$driver,"host"=>$host,"port"=>$port,);
    }
    
    public static function listeBDD($ini){
        $liste = $ini['db_liste'];
        $dbname = 'mysql';
        $sortie = array();
        foreach($liste as $env){
            $bdd = new BDD($ini, $env, $dbname);
            $select = 'SELECT
                        table_schema AS dbname, 
                        ROUND(SUM( data_length + index_length ) , 2) AS taille 
                      FROM information_schema.TABLES
                      GROUP BY TABLE_SCHEMA;';
            $lignes = $bdd->fetchAll($select);
            foreach($lignes as $row){
                if($row['dbname'] !== 'information_schema' && $row['dbname'] !== 'mysql' && $row['dbname'] !== 'performance_schema'){
                    $sortie[$env][$row['dbname']] = Util::afficherTailleFichier($row['taille']);
                }
            }
            
        }
        return $sortie;
    }
    
    function getDbName() {
        return $this->dbName;
    }

  

    function getInfos($table){
        if(empty($this->metadata)){
            $metadata = new MysqlMetadata($this);
            $table = $metadata->getTable($table);
            $constraints = $table->getConstraints();
            $schema = null;
            $primary = array();
            $metadata = array();
            $extra = array();

            $sql = "DESCRIBE ".$table->getName();
            $stmt = $this->query($sql);
            $row = $stmt->fetch();
           

    // 			Zend\Debug\Debug::dump($res->current());
    // 			die;

            if(!empty($row)){
                    while($row !== false) {
                            if(!empty($row["Extra"])){
                                    $extra[$row['Field']] = $row["Extra"];
                            }
                            $row = $stmt->fetch();
                    }
            }

            foreach($constraints as $constraint){
                    if(strtolower($constraint->getType()) == strtolower(self::PRIMARY_KEY)){
                            $primary = $constraint->getColumns();
                    }
            }

            foreach($table->getColumns() as $colonne){
                    $schema = $colonne->getSchemaName();
                    $nom = $colonne->getName();
                    $ligne = array();
                    $ligne['POSITION'] = $colonne->getOrdinalPosition();
                    $ligne['DATA_TYPE'] = $colonne->getDataType();
                    $ligne['PRECISION'] = $colonne->getNumericPrecision();
                    $ligne['UNSIGNED'] = $colonne->getNumericUnsigned();
                    $ligne['NULLABLE'] = $colonne->isNullable();
                    $ligne['LENGTH'] = $colonne->getCharacterMaximumLength();
                    $ligne['OCTET'] = $colonne->getCharacterOctetLength();
                    $ligne['SCALE'] = $colonne->getNumericScale();
                    $ligne['DEFAULT'] = $colonne->getColumnDefault();
                    $ligne['IDENTITY'] = false;
                    foreach($extra as $key=>$value){
                            $ligne['IDENTITY'] = ($nom == $key)?(($value == self::AUTOINCREMENT)?true:$ligne['IDENTITY']):$ligne['IDENTITY'];
                    }
                    foreach($primary as $key){		
                            $ligne['PRIMARY'] = ($nom == $key)?true:false;
                    }
                    $metadata[$nom] = $ligne;
            }


            $info = array(
                            self::SCHEMA           => $schema,
                            self::NAME             => $table->getName(),
                            self::COLS             => (array)  $table->getColumns(),
                            self::PRIMARY          => (array) $primary,
                            self::METADATA         => $metadata,
    // 				self::ROW_CLASS        => $this->_rowClass,
    // 				self::ROWSET_CLASS     => $this->_rowsetClass,
    // 				self::REFERENCE_MAP    => $this->_referenceMap,
    // 				self::DEPENDENT_TABLES => $this->_dependentTables,
    // 				self::SEQUENCE         => $this->_sequence
            );



            $this->metadata = $info;
        }
        return $this->metadata;
    }
        
    public function showTables(){
        $select = "show TABLES;";
        $tables = $this->fetchAll($select);
        $liste = array();
        foreach($tables as $table){
            $value = $table;
            if(is_array($table)){
                $value = array_shift($table);
            }
            array_push($liste, $value);
        }
        return $liste;
    }
    
    public function fetchAll($select,$attr=\PDO::FETCH_ASSOC){
        $res = $this->query($select);
        
        return $res->fetchAll($attr);
    }
    
    
    public function getCurrentSchema(){
        return $this->dbName;
    }
    
    public function getPlatform(){
        return $this->platform;
    }
    
    public function getUserName(){
        return $this->username;
    }
}
