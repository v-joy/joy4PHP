<?php
function __autoload($class_name){
	$path = array(
		WEB_ROOT."Models/{$class_name}.class.php",
		WEB_ROOT."Controllers/{$class_name}.class.php",
		JOY4PHP."/Lib/{$class_name}.class.php"
	);
	foreach($path as $item){
		if(file_exists($item)) {
			include_once $item;
			return;
		}
	}
	throw new Exception("class $class_name not found!");
}

class joy4PHP{
	
	protected $_reg = null;
	
	public function __construct($configs=null){
		//handle configuration
		$configType = strtolower(gettype($configs));
		
		defined('JOY4PHP') || define('JOY4PHP', dirname(__FILE__));
		$defaultConfig = require_once(JOY4PHP."/Conf/Conf.php");
		$userConfig = array();
		switch($configType){
			case "array":
				$userConfig = $configs;
			case "string":
				$userConfig = $this->_getConfigFromFile($configs);
				break;
			case "null":
				break;
			default:
				throw new Exception("this type is not supported!");
		}
		
		$config = array_merge($defaultConfig,$userConfig);
		$this->_loadLib($config);
	}
	
	public function run(){
		//get module and action 
		Dispatcher::dispatch();
		
		//use reflaction to call requested method in class
		$module = Dispatcher::getModule();
		$action = Dispatcher::getAction();
		$controllerName = WEB_ROOT."/Controllers/".$module.".class.php";
		if (is_file($controllerName)) {
			require_once $controllerName;
		}else if (is_file(WEB_ROOT."/Controllers/__empty.class.php")) {
			require_once WEB_ROOT."/Controllers/__empty.class.php";
			$module = "empty";
		}else{
			throw new Exception("can not find the controller!");
		}
		$controllerClassName = $module."Controller";
		$controller = new $controllerClassName();
		
		$controllerReflection = new ReflectionClass($controllerClassName); 
		$action.="Action";
		if ($controllerReflection->hasMethod($action) ) {
			$method = $controllerReflection->getMethod($action);
			if ($method->isPublic()) {
				$method->invoke($controller);
			}else{
				throw new Exception("unable to visit this page");
			}
		}elseif ($controllerReflection->hasMethod("__empty")) {
			$controller->__empty();
		}else{
			throw new Exception("can not find the action!");
		}
		
	}
	
	private function _getConfigFromFile($file){
		if(!is_readable($file)){
			throw new Exception("configure file unreadable");
		}
		
		return require_once($file);
	}
	
	private function _loadLib($configs){
		$libPath = JOY4PHP."/Lib/";
		
		require_once($libPath."Common.php");
		require_once($libPath."Reg.class.php");
		require_once($libPath."Log.class.php");
		//$this->_reg = Reg::getInstance();
		foreach ($configs as $key => $config) {
			$configName = $key;
			Reg::set($configName, $config);
		}
		
		
		require_once($libPath."Dispatcher.class.php");
		require_once($libPath."Controller.class.php");
		require_once($libPath."DB.class.php");
		require_once($libPath."Model.class.php");
		require_once($libPath."View.class.php");
		
		//use empty will always return true, why?
		//if (empty($this->_reg->config_db_type)) {
		if (is_null(Reg::get('db_type'))) {
			Reg::set('db_type', 'mysql');
		}
		$dbType = ucwords(strtolower(Reg::get('db_type')));
		$dbDriverPath = $libPath.'DB'.$dbType.'.class.php';
		if(!is_file($dbDriverPath)){
			throw new Exception($dbType." database driver is not found!");
		}
		require_once $dbDriverPath;
		
	}
}