<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
class DBMysql extends DB implements IDB{
	
	public function __construct() {
		parent::__construct();
	}
	public function connect(){
		$this->_dbLink = @mysql_connect($this->_config["host"],$this->_config["user"],$this->_config["pwd"]);
		if ($this->_dbLink != FALSE) {
			mysql_select_db($this->_config["name"],$this->_dbLink);
		}
		if (mysql_errno() !==0) {
			throw new Exception("connect to database failed:".mysql_error());
		}
		mysql_set_charset($this->_config["charset"],$this->_dbLink);
		return $this->_dbLink;
	}
	
	public function query($sql) {
		$this->_check__dbLink();
		//Log::write($sql);
		$this->_sqls[] = $sql;
		$this->freeResult();
		$this->_queryLink = mysql_query($sql,$this->_dbLink);
		if(!$this->_queryLink){
			//mark : what if query faild??
			return array();
		}
		$result = array();
		while($result[] = mysql_fetch_assoc($this->_queryLink)){};
		//pop the false item out of $result
		array_pop($result);
		return $result;
	}
	
	public function execute($sql) {
		//Log::write($sql);
		$this->_check__dbLink();
		$this->_sqls[] = $sql;
		$this->freeResult();
		return mysql_query($sql,$this->_dbLink)!=false;
	}
	
	protected  function _check__dbLink(){
		if (!is_resource($this->_dbLink)) {
			$connect_time = Reg::get("db_conn_time");
			$connect_time = $connect_time?$connect_time:0;
			$connect_time_max = Reg::get("db_conn_time_max");
			if($connect_time > $connect_time_max){
				Log::write("database  Reconnection time reached max!".date("Y-m-d h-i-s"));
				throw new Exception("database  Reconnection time reached max!");
			}
			Log::write("database  Reconnection time:".$connect_time);
			Reg::set("db_conn_time",$connect_time+1);
			$this->connect();
		}
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