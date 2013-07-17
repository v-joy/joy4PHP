<?php
class loginController extends Controller{
	public function indexAction(){
		if($this->isPost()){
			include_once(WEB_ROOT."Models/User.php");
			$user = new user();
			$username = $this->getPost("username");
			$password = $this->getPost("password");
			
			if($user->verifyUser($username,$password)){
				$_SESSION["username"] = $username;
				redirect($this->view->getIndexUrl());
			}else{
				$this->error("账号或者密码错误！");
			}
		}else{
			$this->display();
		}
	}
	
	public function logoutAction(){
		unset($_SESSION["username"]);
		redirect($this->view->getIndexUrl());
	}

}