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
	
	public function insert($datd) {
		;
	}
	
	public function delete($condition) {
		;
	}
	public function update($date,$condition) {
		;
	}
	public function select($condition) {
		;
	}
	public function count($condition) {
		;
	}
	
	public function __destruct(){
		
	}
	
}