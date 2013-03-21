<?php
/**
 * 
 * author  liu.jl
 * date 2013-3-18
 *
 */
class View{
	
	//store view variables
	protected $vars = array();
	
	
	
	//assign view variable
	public function __set($name,$value){
		$this->vars[$name] = $value;
	}
	
	//fetch view variable
	public function __get($name){
		return isset($this->vars[$name])?$this->vars[$name]:null;
	}
	
	public function display($path=""){
		include(WEB_ROOT."Views".DIRECTORY_SEPARATOR."test/demo".$path.".php");
		/*
		echo "displaying the view:".$path."<br>";
		echo <<<EOT
		<form method="post">
		<input type="submit" name="submit" value="test">
		</form>
EOT;
*/
	}
	
	public function render($content) {
		echo $content;
	}
}
