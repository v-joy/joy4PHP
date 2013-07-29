<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-19
 *
 */
require_once JOY4PHP."/Lib/"."DBInterface.php";
abstract class DB{
	
	protected $_dbLink = false;
	
	protected $_queryLink = false;
	
	protected $_sqls = array();
	

	public function __construct() {
		;
	}
	
	public function insert($data,$table) {
		$sql = "insert into ".$table." (".implode(",", array_keys($data)).") values ('".implode("','", array_values($data))."')";
		//echo $sql;return;
		return $this->execute($sql)?$this->getNewID():false;
	}
	
	public function delete($condition=null,$table) {
		$sql = "delete from ".$table;
		if($condition!=null) $sql.=" where (".$this->_parseCondition($condition).")";		
		return $this->execute($sql);
	}
	public function update($data,$condition=null,$table) {
		$setsql = "";
		if(is_array($data)){
			foreach($data as $key=>$value){
				$setsql.=$key."='".$value."',";
			}
			$setsql = rtrim($setsql,",");
		}else{
			$setsql = $data;
		}
		if(empty($setsql)){
			return true;
		}else{
			$sql = "update ".$table." set {$setsql}";
			if($condition!=null) $sql.=" where (".$this->_parseCondition($condition).")";
			return $this->execute($sql);
		}
	}
	public function select($condition=null,$table) {
		$sql = "select * from ".$table;
		if($condition!=null) $sql.=" where (".$this->_parseCondition($condition).")";	
		return $this->query($sql);
	}
	public function count($condition=null,$table) {
		$sql = "select count(*) as totlenum from ".$table;
		if($condition!=null) $sql.=" where (".$this->_parseCondition($condition).")";		
		$result = $this->query($sql);
		return $result[0]["totlenum"];
	}
	
	protected function _parseCondition($condition){
		$sql = "";
		if (is_object($condition)) {
			$condition = get_object_vars($condition);
		}
		if (is_array($condition)) {
			foreach ($condition as $key => $value) {
				$sql .= "and $key = \"$value\" ";
			}
			$sql = substr($sql, 4);
		}elseif (is_string($condition)){
			$sql = $condition;
		}else{
			throw new Exception("unsupported data type");
		}
		return $sql;
	}
	
	public function __destruct(){
		$this->freeResult();
		$this->close();
	}
	
}