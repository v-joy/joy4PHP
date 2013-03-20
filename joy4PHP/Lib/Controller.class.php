<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
abstract class Controller{
	
	protected $view = null;
	
	public function __construct() {
		if (method_exists($this, "__init")) {
			$this->__init();
		}
	}
	
	public function __destruct() {
		D($this->isPost());
	}
	
	public function getPost($name){
		return isset($_POST[$name])?$_POST[$name]:false;
	}
	
	public function getGet($name){
		return isset($_GET[$name])?$_GET[$name]:false;
	}
	
	public function getparam($name){
		$result = isset($_POST[$name])?$_POST[$name]:false;
		if (!$result) {
			$result = isset($_GET[$name])?$_GET[$name]:false;
		}
		return $result;
	}
	
	public function getPosts(){
		return $_POST;
	}
	
	public function getGets(){
		return $_GET;
	}
	
	public function isPost() {
		return "post"==strtolower($_SERVER['REQUEST_METHOD']);
	}
	
	
}
