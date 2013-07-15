<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
class DBMysql extends DB implements IDB{
	
	public function __construct() {
		$this->connect();
	}
	public function connect($configs=array()){
		$finalConfigs = array();
		$map = array("host","user","pwd","name","prefix","charset");
		foreach ($map as $item) {
			$finalConfigs[$item] = isset($configs[$item])?$configs[$item]:Reg::get("db_".$item);
		}
		$this->_dbLink = @mysql_connect($finalConfigs["host"],$finalConfigs["user"],$finalConfigs["pwd"]);
		if ($this->_dbLink != FALSE) {
			mysql_select_db($finalConfigs["name"],$this->_dbLink);
		}
		if (mysql_errno() !==0) {
			throw new Exception("connect to database failed:".mysql_error());
		}
		mysql_set_charset($finalConfigs["charset"],$this->_dbLink);
		
		return $this->_dbLink;
	}
	
	public function query($sql) {
		if (!$this->_dbLink) {
			throw new Exception("database is not connected!");
		}
		$this->_sqls[] = $sql;
		$this->freeResult();
		$this->_queryLink = mysql_query($sql,$this->_dbLink);
		$result = array();
		while($result[] = mysql_fetch_assoc($this->_queryLink)){};
		//pop the false item out of $result
		array_pop($result);
		return $result;
	}
	
	public function execute($sql) {
		if (!$this->_dbLink) {
			throw new Exception("database is not connected!");
		}
		$this->_sqls[] = $sql;
		$this->freeResult();
		return mysql_query($sql,$this->_dbLink)!=false;
	}
	
	public function freeResult(){
		if ($this->_queryLink != false) {
			mysql_free_result($this->_queryLink);
		}
		$this->_queryLink = false;
	}
	
	public function log($type="text"){
		
		switch($type){
			case "text":
				return "sqls:".implode(",",$this->_sqls).";error:".mysql_error($this->_dbLink);
				break;
			case "array":
				$log=array();
				$log["sqls"]= $this->_sqls;
				$log["error"]=mysql_error($this->_dbLink);
				return $log;
				break;
		}
	}
	
	public function close(){
		if(is_resource($this->_dbLink)){
		mysql_close($this->_dbLink);
		}
		$this->_dbLink = false;
	}
	
	public function getNewID(){
		return mysql_insert_id();
	}
}