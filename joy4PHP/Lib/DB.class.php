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
		$sql = "insert into ".$table." (".implode(",", array_keys($data)).") values (".implode(",", array_values($data)).")";
		return $this->execute($sql);
	}
	
	public function delete($condition,$table) {
		$sql = "delete from ".$table." where ";//(".implode(",", array_keys($data)).") values (".implode(",", array_values($data)).")";
		return $this->execute($sql);
	}
	public function update($data,$condition,$table) {
		;
	}
	public function select($condition,$table) {
		;
	}
	public function count($condition,$table) {
		;
	}
	
	//protected function _parse
	
	public function __destruct(){
		$this->freeResult();
		$this->close();
	}
	
}