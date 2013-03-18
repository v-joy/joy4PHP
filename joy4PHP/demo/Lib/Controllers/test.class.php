<?php
class testController extends Controller{
	public function demo() {
		echo "this is module:".MODULE."; and action:".ACTION;
	}
	
	public function __empty() {
		echo "we do not have the ".ACTION." action";
	}
}