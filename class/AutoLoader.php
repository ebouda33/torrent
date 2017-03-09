<?php

/* 
 * xgld8274
 */

class Autoloader{
	private static $namespace ;

	public static function register(array $config){
		self::$namespace = $config['ns']; 
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}
	
	
	public static function autoload($class){
		$namespace = explode("\\", $class);
		$path = "";
                
                if(!class_exists($class)){
                    foreach(self::$namespace as $key => $value){
                            if($key == $namespace[0]){
                                    $path = $value;
                            }
                    }

    //                echo $path ."=>".$class."<br>";

                    if(!empty($path)){
                        $class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
                        $file = $path.$class.".php";
                        if(file_exists($file))   {
                            require_once $file;
                            return true;
                        }
                    }
                }
                return false;
	}
}