<?php
class joy4PHP{
	
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
				throw new Exception("不支持该类型！");
		}
		
		
		
		
		$this->_loadLib();
		$reg = Reg::getInstance();
		echo $reg->name;
	}
	
	public function run(){
		
	}
	
	private function _getConfigFromFile($file){
		if(!is_readable($file)){
			throw new Exception("配置文件不可读！");
		}
		return require_once($file);
	}
	
	private function _loadLib(){
		$libPath = "./Lib/";
		
		require_once($libPath."common.php");
		require_once($libPath."Reg.class.php");
		require_once($libPath."Router.class.php");
		require_once($libPath."Model.class.php");
		require_once($libPath."View.class.php");
		require_once($libPath."Controller.class.php");
		
		$reg = Reg::getInstance();
		$reg->name = "test";
	}
}