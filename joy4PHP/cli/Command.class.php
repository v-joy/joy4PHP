<?php
/**
 * 
 * author  liu.jl
 * date 2013-12-31
 *
 */
class Command{
	
	protected $_in = null;
	
	public function __construct() {
		$this->_initIO();
	}
	
	protected function _initIO(){
		$this->_in = fopen("php://stdin","r");
	}
	
	public function getLine($notice=false){
		if($notice!=false){
			echo $notice;
		}
		return rtrim(fgets($this->_in));
	}
	
	public function getPermission($name=null){
		$permission = $this->getLine();
		$permission = strtolower($permission);
		return true?in_array($permission,array("y","yes")):false;
	}
	
	public function run($argv=array()){
		
		if(count($argv) < 2){
			$action =  $this->showHelp();
		}else{
			$action = $argv[1];
		}
		$this->runAction($action);	
	}
	public function runAction($action){
		//mark todo
		
		$action = strtolower($action);
		
		$actions = array(
			"cnw"=>"createnewweb",
			"cr"=>"checkrequirement",
			"sh"=>"showhelp",
			"q"=>"quit"
		);
		if(in_array($action,array_keys($actions))){
			$action = $actions[$action];
		}else if(in_array($action,array_values($actions))){
			//
		}else{
			$this->showError("can not find the action");
			exit;
		}
		
		$action .="Action";
		$this->$action();
		
/*		if(method_exists($this,$action) ){//&& is_callable($this->$action)
			$this->$action();
		}else{
			echo "can not find the action!";
		}*/
		return $action;
	}
	
	public function createnewwebAction(){
		//mark the following methods have not implemented yet
		$this->handleLocation($this->getLine("please input the location where you web will be installed"));
		$this->copyweb();
		$this->setpermission();
		$this->getConfig();
		$this->setConfig();
	}
	
	public function checkrequirementAction(){
		//mark todo
		echo "visit our website to get more infomation: https://github.com/v-joy/joy4PHP";
		exit;
	}

	public function showhelpAction(){
		$this->run();
	}

	public function quitAction(){
		echo "visit our website to get more infomation: https://github.com/v-joy/joy4PHP";
		exit;
	}	
	public function showHelp(){
		echo <<<EOD
=======================  joy4PHP help infomation ================
cnw/createnewweb		create new web
cr/checkrequirement		check requirement  
sh/showhelp				show help information
q/quit					quit this application
=================================================================
please input your choice:
EOD;
	return $this->getLine();
	}
	
	public function showError($error){
		//mark todo
		echo $error;
	}
	
	public function __destruct() {
		fclose($this->_in);
	}
}
