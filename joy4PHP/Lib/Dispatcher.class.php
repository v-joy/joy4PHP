<?php
class Dispatcher{
	
	public function __construct(){
		
	}
	
	static function dispatch() {
		$requests = explode('/', trim($_SERVER['PATH_INFO'],"/"));
		define("MODULE",array_shift($requests));
		define("ACTION",array_shift($requests));
	}
	
	static function getModule() {
		return MODULE?MODULE:"empty";
	}
	
	static function getAction() {
		return ACTION?ACTION:"empty";
	}
}