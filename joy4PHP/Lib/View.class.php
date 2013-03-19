<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
abstract class View{
	
	//store view variables
	protected $vars = array();
	
	//assign view variable
	public function assign($name,$value){
		$this->vars[$name] = $value;
	}
	
	//fetch view variable
	public function fetch($name){
		return isset($this->vars[$name])?$this->vars[$name]:false;
	}
	
	public function display(){
		
	}
	
	public function render($content) {
		echo $content;
	}
}
