<?php
class joy4PHP{
	
	protected $_reg = null;
	
	public function __construct($configs=null){
		//handle configuration
		$configType = strtolower(gettype($configs));
		
		defined('JOY4PHP') || define('JOY4PHP', dirname(__FILE__).DIRECTORY_SEPARATOR);
		$defaultConfig = require_once(JOY4PHP."Conf/Conf.php");
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
		
		require_once($libPath."common.php");
		require_once($libPath."Reg.class.php");
		$this->_reg = Reg::getInstance();
		foreach ($configs as $key => $config) {
			$configName = "config_".$key;
			$this->_reg->$configName = $config;
		}
		
		
		require_once($libPath."Dispatcher.class.php");
		require_once($libPath."Controller.class.php");
		require_once($libPath."DB.class.php");
		require_once($libPath."Model.class.php");
		require_once($libPath."View.class.php");
		
		//use empty will always return true, why?
		//if (empty($this->_reg->config_db_type)) {
		if (is_null($this->_reg->config_db_type)) {
			$this->_reg->config_db_type="mysql";
		}
		$dbType = ucwords(strtolower($this->_reg->config_db_type));
		$dbDriverPath = $libPath.'DB'.$dbType.'.class.php';
		if(!is_file($dbDriverPath)){
			throw new Exception($dbType." database driver is not found!");
		}
		require_once $dbDriverPath;
		
	}
}