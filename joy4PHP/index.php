<?php
//this is a test file
require_once "joy4PHP.php";
// temp func , for writting less letter
function D($var) {
	var_dump($var);
}
$joy4PHP = new joy4PHP("Conf/Conf.php");
$joy4PHP->run();