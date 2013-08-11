<?php
//this file is used to place common functions
function D($var,$halt=FALSE) {
	var_dump($var);
	if ($halt) {
		exit();
	}
}

function redirect($url){
	return header('Location: '.$url);
}
function url_set_var($name,$value,$url=null){
	if(is_null($url)){
		$url = $_SERVER['REQUEST_URI'];
	}
	if(isset($_GET[$name])){
		if($value != $_GET[$name]){
			$url = preg_replace("&/$name/([a-zA-Z0-9_-])*&i","/$name/$value",$url);
			$url = preg_replace("/\?$name=([a-zA-Z0-9_-])*/","?$name=$value",$url);
			$url = preg_replace("/&$name=([a-zA-Z0-9_-])*/","&$name=$value",$url);
		}
		return $url;
	}else{
		if(stripos($url,"?")){
			return $url."&{$name}={$value}";
		}else{
			return $url."?{$name}={$value}";
		}
	}
}
function cache_curent_page($exist_after_show=true){
	$cache = new Cache("File");
	$curent_url = $_SERVER["REQUEST_URI"];
	$signal_var = "is_rendering_cache";
	if(isset($cache->$curent_url)){
		echo "cached value:".$cache->$curent_url;
		if($exist_after_show){exit;}
	}else{
		$is_rendering_cache = isset($_GET[$signal_var]);
		$page_content = "cache file content faild";
		if(!$is_rendering_cache){
			$render_path = url_set_var($signal_var,1);
			//mark : curently only use http protocol
			$render_path =  "http://".$_SERVER['HTTP_HOST'].$render_path;
			$page_content = file_get_contents($render_path);
			$cache->$curent_url = $page_content;
		}
	}
}