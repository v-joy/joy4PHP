<?php
//do I really need a db interface?
interface IDB{
	public function connect();
	
	public function query($sql);
	
	public function execute($sql);
	
	public function freeResult();
	
	public function close();
	
}