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
	
	public function add($data){
		return $this->insert($data);
	}
	
	public function page($start_index=null,$page_row=null){
		if(!$page_row){
			$page_row = (int)Reg::get("page_row");
			$page_row = $page_row?$page_row:20;
		}
		if(!$start_index){
			$page_var = Reg::get("page_var");
			$current_page = $_GET[$page_var]?$_GET[$page_var]:1;
			$start_index = $page_row*($current_page-1);
		}
		return $this->L($start_index,$page_row);
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
	public function insert($data) {
		return $this->_db->insert($data,$this->_table);
	}
	public function count($condition=null) {
		return $this->_db->count($condition,$this->_table);
	}
	
	public function __call($method_name,$arguments){
		if(count($arguments)==1){
			$arguments = $arguments[0];
		}else{
			//mark 待处理
		}
		$result = $this->_db->$method_name($arguments);
		$dbType = ucwords(strtolower(Reg::get('db_type')));
		$dbName = "DB".$dbType;
		if(is_object($result) && ($result instanceof $dbName)){
			return $this;
		}else{
			return $result;
		}
	}
}
