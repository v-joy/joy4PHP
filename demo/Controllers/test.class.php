<?php
class testController extends Controller{
	public function demoAction() {
		if ($this->isPost()) {
			$data = array();
			$data["name"] = $this->getPost("name");
			$data["pwd"]= $this->getPost("pwd");
			//require_once WEB_ROOT.DIRECTORY_SEPARATOR."Models".DIRECTORY_SEPARATOR."User.php";
			$user = new Model("user");
			if($user->insert($data)){
				$this->display("system:success");
			}else{
				$this->display("system:error");
			}
		}else{
			$this->view->title = "this is assigned in View";
			$this->display();
		}
	}
	
	//test count 
	public function countAction() {
		if ($this->isPost()) {
			$data = array();
			$data["name"] = $this->getPost("name");
			$user = new Model("user");
			$result = $user->count($data);
			echo "there are {$result} users matched.";
		}else{
			$this->view->title = "this is assigned in View";
			$this->display();
		}
	}
	
	public function __empty() {
		echo "we do not have the ".ACTION." action";
	}
}