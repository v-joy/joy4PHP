<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
class Model{
	
	protected $_table ="";
	
	protected $_prefix ="";
	
	protected $_db;
	
	public function __construct($table=NULL,$prefix=NULL) {
		if (is_null($prefix)) {
			$this->_prefix = Reg::get('db_prefix');
		}else {
			$this->_prefix = $prefix;
		}
		if(!is_null($table)){
			$this->_table = $table;
		}
		$dbType = ucwords(strtolower(Reg::get('config_db_type')));
		$dbName = "DB".$dbType;
		$this->_db = new $dbName();
	}
	public function insert($data) {
		return $this->_db->insert($data,$this->_table);
	}
	
	public function delete($condition) {
		return $this->_db->delete($condition,$this->_table);
	}
	public function update($data,$condition) {
		return $this->_db->update($data,$condition,$this->_table);
	}
	public function select($condition) {
		return $this->_db->select($condition,$this->_table);
	}
	public function count($condition) {
		return $this->_db->count($condition,$this->_table);
	}
}
