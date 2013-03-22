<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
class View{
	
	//store view variables
	protected $vars = array();
	
	
	
	//assign view variable
	public function __set($name,$value){
		$this->vars[$name] = $value;
	}
	
	//fetch view variable
	public function __get($name){
		return isset($this->vars[$name])?$this->vars[$name]:null;
	}
	
	public function display($path=""){
		$hasModule = strpos($path, ":");
		$filepath = "";
		$module = Dispatcher::getModule();
		$action = Dispatcher::getAction();
		if ($hasModule === false ) {
			if (!empty($path)) {
				$action = $path;
			}
		}else{
			$module = substr($path, 0,$hasModule);
			$action = substr($path, $hasModule+1);
		}
		$filepath = WEB_ROOT."Views".DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$action.".php";
		if (!is_readable($filepath)) {
			throw new Exception("can not find the view!");
		}
		
		//assign variables  
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}
		include $filepath;
	}
	
	public function render($content) {
		echo $content;
	}
}
