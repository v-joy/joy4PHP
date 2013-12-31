<?php
/**
 * 
 * author  liu.jl
 * date 2013-12-31
 *
 */
class Command{
	
	protected $_in = null;

	protected $_action = null;
	
	protected $_location = null;
	
	protected $_appName = null;
	
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
		//$this->_appName = $this->getLine("please input the name for your project folder:");
		$this->handleLocation();
		$this->copyweb(dirname(__FILE__).DIRECTORY_SEPARATOR."templateApp".DIRECTORY_SEPARATOR,$this->_location);
		$this->setpermission();
		$this->getConfig();
		$this->setConfig();
		
		echo "finished";
	}
	
	public function handleLocation(){
		//mark :有问题：应该判断该目录的上一级是否存在 而不是该目录是否存在
		$location = $this->getLine("please input the location where you web will be installed:");
		while(!is_dir($location)){
			$location = $this->getLine("location not exist,please re-input the location where you web will be installed:");
			$location= realpath($location);
		}
		$this->_location = $location;
	}
	
	public function copyweb($from,$to){
		$list=opendir($from);
		while($file=readdir($list)){
			if($file!="." && $file!=".."){
				$targetFile = $from.DIRECTORY_SEPARATOR.$file;
				$toFile = $to.DIRECTORY_SEPARATOR.$file;
				if(is_file($toFile)){
					copy($targetFile,$toFile);
				}elseif(is_dir($toFile)){
					@mkdir($toFile,0777);
					echo $toFile."\n";
					//$this->copyweb($targetFile,$toFile);
					
				}
			}
		}
		
	}

	public function setpermission(){
		//mark todo
	}

	public function getConfig(){
		//mark todo
	}
	
	public function setConfig(){
		//mark todo
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
