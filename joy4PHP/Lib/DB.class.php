<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-19
 *
 */
abstract class DB{
	
	protected $_link = NULL;
	
	
	public function __construct() {
		;
	}
	
	public function query($sql) {
		;
	}
	
	public function execute($sql) {
		;
	}
	
	public function insert($date) {
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
	
}