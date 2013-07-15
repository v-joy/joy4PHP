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