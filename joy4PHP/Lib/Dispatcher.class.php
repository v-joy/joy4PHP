<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
class Dispatcher{
	
	public function __construct(){
		
	}
	
	static function dispatch() {
		$pathInfo = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:"";
		$pathInfo = trim($pathInfo,"/");
		$requests = explode('/', $pathInfo);
		define("MODULE",array_shift($requests));
		define("ACTION",array_shift($requests));
		while (!empty($requests)) {
			$key = array_shift($requests);
			if (!isset($_GET[$key])) {
				$_GET[$key] = array_shift($requests);
			}
		}
	}
	
	static function getModule() {
		return MODULE?MODULE:"empty";
	}
	
	static function getAction() {
		return ACTION?ACTION:"empty";
	}
}