<?php
session_start();
// for debuging :

//echo session_id()."|";
//session_write_close();
//session_id("38bls1n5qpk79s8qrno0c2l02");
//session_start();
//echo session_id();
//exit;
header("Content-type: text/html; charset=utf-8"); 
define('JOY4PHP', realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."joy4PHP").DIRECTORY_SEPARATOR);
require JOY4PHP."joy4PHP.php";
define("WEB_ROOT",dirname(__FILE__).DIRECTORY_SEPARATOR);
$joy4PHP = new joy4PHP(WEB_ROOT."Conf/Conf.php");
try{
$joy4PHP->run();
}catch(Exception $e){
	echo "出现了异常：".$e->getMessage();
}