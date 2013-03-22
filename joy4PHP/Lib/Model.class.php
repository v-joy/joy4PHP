<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
class Model{
	
	protected $tableName;
	
	protected $prefix;
	
	protected $wholeName;
	
	public function __construct($table="__null__",$prefix=NULL) {
		if (is_null($prefix)) {
			$this->prefix = Reg::get('db_prefix');
		}else {
			$this->prefix = $prefix;
		}
		$this->tableName = $table;
	}
	
}
