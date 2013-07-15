<?php
class loginController extends Controller{
	public function indexAction(){
		if($this->isPost()){
			$users = include(WEB_ROOT."data/users/users.php");
			if(!is_array($users)){
				$this->error("用户系统出现异常！！");
			}
			$username = $this->getPost("username");
			$password = $this->getPost("password");
			$is_legal = false;
			foreach($users as $user){
				if($username == $user["username"] && $password == $user["password"]){
					$is_legal = true;
				}
			}
			if($is_legal){
				$_SESSION["username"] = "admin";
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