<?php
class testController extends Controller{
	public function demoAction() {
		//echo "this is module:".MODULE."; and action:".ACTION;
		if ($this->isPost()) {
			echo "this is feedback,your post data is:<br>";
			D($this->getPosts());
			
		}else{
			$this->view->variable = "value";
			$this->display();
		}
	}
	
	public function __empty() {
		echo "we do not have the ".ACTION." action";
	}
}