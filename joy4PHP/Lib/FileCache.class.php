<?php 
class FileCache{
	
	protected $_cache_path;
		
	public function __construct(){
		$this->_cache_path = Reg::get("cache_path");
		if(!is_dir($this->_cache_path)){
			throw new Exception("file cache path does exist.");
		}
	}
	
	public function __get($name){
		$cache_file = $this->_get_cache_path($name);
		if(is_file($cache_file)){
			return file_get_contents($cache_file);
		}else{
			return false;
		}
	}
	
	public function __set($name,$value){
		$cache_file = $this->_get_cache_path($name);
		file_put_contents($cache_file,$value);
	}
	
	public function has_key($name){
		$cache_file = $this->_get_cache_path($name);
		if(is_file($cache_file)){
			return true;
		}else{
			return false;
		}
	}
	
	public function clear(){
		$dir = opendir($this->_cache_path);
		while($file = readdir($dir)){
			if($file!="." && $file!=".."){
				unlink($this->_cache_path.$file);
			}
		}
		closedir($dir);
	}
	
	protected function _get_cache_path($name){
		return $this->_cache_path.md5($name);
	}
}