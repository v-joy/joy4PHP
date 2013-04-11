<?php
//this is a test file
header("Content-type: text/html; charset=utf-8"); 
define('JOY4PHP', dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."joy4PHP".DIRECTORY_SEPARATOR);
require_once JOY4PHP."joy4PHP.php";
// temp func , for writting less letter
function D($var,$halt=FALSE) {
	var_dump($var);
	if ($halt) {
		exit();
	}
}
define('WEB_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
$joy4PHP = new joy4PHP(WEB_ROOT."Conf/Conf.php");
$joy4PHP->run();