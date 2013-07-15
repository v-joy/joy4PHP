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
		$this->view = new View();
		if (method_exists($this, "__init")) {
			$this->__init();
		}
	}
	
	public function __destruct() {
		
	}
	
	public function getPost($name=null){
		if(is_null($name)){
			return $this->getPosts();
		}
		return isset($_POST[$name])?$_POST[$name]:null;
	}
	
	public function getGet($name=null){
		if(is_null($name)){
			return $this->getGets();
		}
		return isset($_GET[$name])?$_GET[$name]:null;
	}
	
	public function getparam($name){
		$result = isset($_POST[$name])?$_POST[$name]:null;
		if (!$result) {
			$result = isset($_GET[$name])?$_GET[$name]:null;
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
	
	public function display($path="") {
		$this->view->display($path);
	}
	
	public function error($msg){
		//mark:todo
		throw new Exception($msg);
	}
	
}
