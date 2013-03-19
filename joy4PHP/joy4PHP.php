<?php
class joy4PHP{
	
	protected $_reg = null;
	
	public function __construct($configs=null){
		//handle configuration
		$configType = strtolower(gettype($configs));
		$finalConfig = array();
		switch($configType){
			case "array":
				$finalConfig = $configs;
			case "string":
				$finalConfig = $this->_getConfigFromFile($configs);
				break;
			case "null":
				break;
			default:
				throw new Exception("this type is not supported!");
		}
		$this->_loadLib($finalConfig);
	}
	
	public function run(){
		//get module and action 
		Dispatcher::dispatch();
		
		//use reflaction to call requested method in class
		$module = Dispatcher::getModule();
		$action = Dispatcher::getAction();
		$controllerName = $this->_reg->config_application_path."/Lib/Controllers/".$module.".class.php";
		if (is_file($controllerName)) {
			require_once $controllerName;
		}else if (is_file($this->_reg->config_application_path."/Lib/Controllers/__empty.class.php")) {
			require_once $this->_reg->config_application_path."/Lib/Controllers/__empty.class.php";
			$module = "empty";
		}else{
			throw new Exception("can not find the controller!");
		}
		$controllerClassName = $module."Controller";
		$controller = new $controllerClassName();
		
		$controllerReflection = new ReflectionClass($controllerClassName); 
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
		$libPath = "./Lib/";
		
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
		
		
	}
}