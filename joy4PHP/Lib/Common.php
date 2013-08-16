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
	$cache_content = $cache->$curent_url;
	if($cache_content!==false){
		echo "cached value:".$cache_content;
		if($exist_after_show){exit;}
	}else{
		$signal_var = "is_rendering_cache";
		$is_rendering_cache = isset($_POST[$signal_var]);
		$session_post_id = "PHPSESSID";
		$page_content = "cache file content faild";
		if(!$is_rendering_cache){
			$render_path =  "http://".$_SERVER['HTTP_HOST'].$curent_url;
			$session_id =  session_id();
			$post_data = array($session_post_id=>$session_id,$signal_var=>1);
			$curl = curl_init($render_path);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl,CURLOPT_POST,true);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$post_data);
			$page_content = curl_exec($curl);
			curl_close($curl);
			$cache->$curent_url = $page_content;
			echo $page_content;exit;
		}else{
			if(session_id()!=""){
				session_write_close();
			}
			session_id($_POST[$session_post_id]);
			// mark : there is a bug : when the server run the following line , it stuck
			session_start();
		}
	}
}