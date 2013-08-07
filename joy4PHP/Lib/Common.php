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
	//echo "current url: ".$_SERVER['REQUEST_URI']."<br>";
	//$url = "/joy4php/app/index.php/index/index/table/el_log?is_search=123&id=1&sn=&querytime=&querysuccess=&devid=&queryerror=&postbacktime=&settingsuccess=&nicid=&settingerror=";
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