<?php
//Database drivers must impliment this interface
interface IDB{
	public function connect();
	
	public function query($sql);
	
	public function execute($sql);
	
	public function freeResult();
	
	public function log();
	
	public function close();
	
}