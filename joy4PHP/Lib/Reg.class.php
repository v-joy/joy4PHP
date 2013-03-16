<?php 
class Reg{
	
	private static $_instance=NULL;
	
	protected static $vars = array();
	
	
	public static function getInstance(){
		if(self::$_instance == NULL){
			$classname = get_class();
			self::$_instance = new $classname();
		}
		return self::$_instance;
	}
	
	public function __set($key,$value){
		self::$vars[$key] = $value;
	}
	public function __get($key){
		return isset(self::$vars[$key])?self::$vars[$key]:NULL;
	}
	
}