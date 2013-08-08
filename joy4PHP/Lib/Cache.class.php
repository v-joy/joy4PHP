<?php 
class Cache{
	
	protected $_engine;
	
	public function __construct($type="default"){
		$type = $type=="default" ? Reg::get("cache_type"):$type;
		$engineName = $type."Cache";
		$this->_engine = new $engineName();
	}
	
	public function __get($name){
		return $this->_engine->$name;
	}
	
	public function __set($name,$value){
		return ($this->_engine->$name=$value);
	}
	
	public function __isset($name){
		return $this->has_key($name);
	}
	
	public function has_key($name){
		return $this->_engine->has_key($name);
	}
}