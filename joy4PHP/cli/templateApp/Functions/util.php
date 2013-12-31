<?php
function D($var,$halt=FALSE) {
	var_dump($var);
	if ($halt) {
		exit();
	}
}