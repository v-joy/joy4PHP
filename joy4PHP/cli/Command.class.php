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

	protected $_dbUser = null;

	protected $_dbPass = null;

	protected $_dbName = null;
	
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
		return $this->$action();
	}
	
	public function createnewwebAction(){
		//mark the following methods have not implemented yet
		//$this->_appName = $this->getLine("please input the name for your project folder:");
		$this->handleLocation();
		$this->copyweb(dirname(__FILE__).DIRECTORY_SEPARATOR."templateApp".DIRECTORY_SEPARATOR,$this->_location);
		$this->setpermission();
		$this->ConfigWeb();
		echo " web installed successfully\n";
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
		if(!is_dir($to)){
			@mkdir($to);
		}
		
		$list=opendir($from);
		while($file=readdir($list)){
			if($file!="." && $file!=".."){
				$sourceFile = $from.DIRECTORY_SEPARATOR.$file;
				$toFile = $to.DIRECTORY_SEPARATOR.$file;
				if(is_file($sourceFile)){
					@copy($sourceFile,$toFile);
				}elseif(is_dir($sourceFile)){
					$this->copyweb($sourceFile,$toFile);
				}
			}
		}
	}

	public function setpermission(){
		//mark need test
		@chmod($this->_location.DIRECTORY_SEPARATOR."data",0777);
        $index_path = $this->_location.DIRECTORY_SEPARATOR."index.php";
		$index_content = file_get_contents($index_path);
		$index_content = str_replace("{JOY4PHP}",dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR,$index_content);
		file_put_contents($index_path,$index_content);
	}

	public function ConfigWeb(){
		do{
			echo "can not connect to mysql , please try again\n";
			$_dbUser = $this->getLine("your database user:");
			$_dbPass = $this->getLine("your database password:");
			$_link = @mysql_connect("127.0.0.1",$_dbUser,$_dbPass);
		}while(!$_link);
		$this->_dbUser = $_dbUser;
		$this->_dbPass = $_dbPass;
		$this->_dbName = $this->getLine("your database name:");
		$dbs = mysql_query("show databases",$_link);
		$result = null;
		while($result = mysql_fetch_assoc($dbs)){
			if($result["Database"]==$this->_dbName){
				$result = true;
				break;
			}
		}
		if($result !== true){
			if($this->getPermission("can not find the database,would you like to create it now?")){
				if(@mysql_query("create database ".$this->_dbName,$_link)){
					echo "created successfully!\n";
				}else{
					echo "created faild!\n";
				}
			}
		}
		@mysql_close($_link);
		
		//set configuration
		$configure_path = $this->_location.DIRECTORY_SEPARATOR."Conf".DIRECTORY_SEPARATOR."Conf.php";
		$configure = file_get_contents($configure_path);
		$configure = str_replace("{dbUser}",$this->_dbUser,$configure);
		$configure = str_replace("{dbPass}",$this->_dbPass,$configure);
		$configure = str_replace("{dbName}",$this->_dbName,$configure);
		file_put_contents($configure_path,$configure);
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
