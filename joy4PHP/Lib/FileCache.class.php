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
			$cache = file_get_contents($cache_file);
			$expire_time = (int)Reg::get("cache_time");
			if($expire_time!=0){
				$time = substr($cache,0,10);
				if((time()-$time)>$expire_time){
					unset($this->$name);
					return false;
				}
			}
			return substr($cache,10);
		}else{
			return false;
		}
	}
	
	public function __set($name,$value){
		$cache_file = $this->_get_cache_path($name);
		$value = time().$value;
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
	
	public function __unset($name){
		$cache_file = $this->_get_cache_path($name);
		if(is_file($cache_file)){
			@unlink($cache_file);
		}
		return true;
	}
	
	public function clear(){
		$dir = opendir($this->_cache_path);
		while($file = readdir($dir)){
			if($file!="." && $file!=".."){
				@unlink($this->_cache_path.$file);
			}
		}
		closedir($dir);
	}
	
	protected function _get_cache_path($name){
		return $this->_cache_path.md5($name);
	}
}