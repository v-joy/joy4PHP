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
			$this->_table = $this->_prefix.$table;
		}
		$dbType = ucwords(strtolower(Reg::get('db_type')));
		$dbName = "DB".$dbType;
		$this->_db = new $dbName();
	}
	public function insert($data) {
		return $this->_db->insert($data,$this->_table);
	}
	
	public function add($data){
		return $this->insert($data);
	}
	
	public function execute($sql){
		return $this->_db->execute($sql);
	}
	
	public function query($sql){
		return $this->_db->query($sql);
	}
	
	public function logDb($type="text"){
		return $this->_db->log($type);
	}
	
	public function delete($condition=null) {
		return $this->_db->delete($condition,$this->_table);
	}
	public function update($data,$condition=null) {
		return $this->_db->update($data,$condition,$this->_table);
	}
	public function select($condition=null) {
		return $this->_db->select($condition,$this->_table);
	}
	public function count($condition=null) {
		return $this->_db->count($condition,$this->_table);
	}
}
