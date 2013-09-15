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
	
	protected $curent_sql = array();
	
	public function __construct() {
		$this->_init_sql();
	}
	
	public function L($start=0,$row=20){
		if(is_array($start)){
			$row = $start[1];
			$start = $start[0];
		}
		$this->curent_sql["limit"] = " limit $start,$row ";
		return $this;
	}
	
	public function W($condition=null){
		if($condition!=null) {		
			$sql = "";
			if (is_object($condition)) {
				$condition = get_object_vars($condition);
			}
			if (is_array($condition)) {
				foreach ($condition as $key => $value) {
					if(is_int($value)){
						$sql .= "and `$key` = $value ";
					}else{
						$sql .= "and `$key` = \"$value\" ";
					}
				}
				$sql = substr($sql, 4);
			}elseif (is_string($condition)){
				$sql = $condition;
			}else{
				throw new Exception("unsupported data type");
			}
			if(!empty($sql)){
				$sql = "where ( ".$sql.")";
			}
			$this->curent_sql["where"] = $sql;
		}
		
		return $this;
	}
	
	public function O($order="id desc"){
			$this->curent_sql["order"] = " order by ".$order." ";
			return $this;
	}
	
	public function F($field="*"){
		if(!is_array($field)){
			$this->curent_sql["field"] = $field;
		}else{
			$this->curent_sql["field"] = " `".implode("`,`",$field)."` ";
		}
		return $this;
	}
	
	public function T($table){
		$this->curent_sql["table"] = " `".$table.'` ';
		return $this;
	}
	
	public function D($data){
		$this->curent_sql["data"] = $data;
		return $this;
	}
	
	public function insert($data,$table) {
		$sql = "insert into `".$table."` (`".implode("`,`", array_keys($data))."`) values ('".implode("','", array_values($data))."')";
		return $this->execute($sql)?$this->getNewID():false;
	}
	
	public function delete($condition=null,$table) {
		$this->W($condition)->T($table);
		$sql = $this->_parseSql("delete");
		return $this->execute($sql);
	}
	public function update($data,$condition=null,$table) {
		$this->W($condition)->T($table)->D($data);
		$sql = $this->_parseSql("update");
		return $this->execute($sql);
	}
	public function select($condition=null,$table) {
		$this->W($condition)->T($table);
		$sql = $this->_parseSql("select");
		return $this->query($sql);
	}
	public function count($condition=null,$table) {
		$this->W($condition)->T($table);
		$sql = $this->_parseSql("count");
		$result = $this->query($sql);
		return $result[0]["totlenum"];
	}
	
	protected function _parseSql($type="select"){
		$sql = "";
		if(!isset($this->curent_sql["field"]) || empty($this->curent_sql["field"]) ){
			$this->curent_sql["field"] = "*";
		}
		switch($type){
			case "select":
				$sql .= "select ".$this->curent_sql["field"]." from ".$this->curent_sql["table"]." ".$this->curent_sql["where"]." ".$this->curent_sql["order"]." ".$this->curent_sql["limit"];
				break;
			case "count":
				$sql .= "select count(*) as totlenum from ".$this->curent_sql["table"]." ".$this->curent_sql["where"];
				break;	
			case "update":
				$setsql = " set ";
				if(is_array($this->curent_sql["data"])){
					foreach($this->curent_sql["data"] as $key=>$value){
						if(is_string($value)){
							$setsql .= "`".$key."`='".$value."',";
						}else{
							$setsql .= "`".$key."`=".$value.",";
						}
					}
					$setsql = rtrim($setsql,",");
				}else{
					$setsql .= $this->curent_sql["data"];
				}
				$setsql .= " ";
				$sql .= "update".$this->curent_sql["table"].$setsql.$this->curent_sql["where"];
				//Log::write($sql);
				break;
			case "delete":
				$sql = "delete from ".$this->curent_sql["table"].$this->curent_sql["where"].$this->curent_sql["limit"];
				break;
			default:
				Log::write("DB.class _parseSql default error");
				throw new Exception("DB.class _parseSql default error");
		}
		_init_sql();
		return $sql;
	}
	
	protected function _init_sql(){
		$this->curent_sql = array(
			'limit' => '',
			'where' => '',
			'order' => '',
			'field' => '*',
			'table' => '',
			'data' => ''
		);
	}
	
	public function __destruct(){
		$this->freeResult();
		$this->close();
	}
	
}