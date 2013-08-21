<?php
class loginController extends Controller{
	public function indexAction(){
		if($this->isPost()){
			$model = new Model();
			$tables = $model->query("show tables");
			$has_config_table = false;
			$config_table = Reg::get("db_prefix").Reg::get("db_config_table");
			if(is_array($tables)){
				foreach($tables as $table){
					foreach($table as $table_name){
						if($table_name==$config_table) $has_config_table = true;
					}
				}
			}else{
				$this->error("查询数据库出现错误，请确认数据库的名称和密码是否正确！");
			}
			if(! $has_config_table){
				$config_model = new ConfigModel();
				$success = $config_model->create_config_table();
				if(! $success){
					$this->error("创建数据出错！");
				}
			}
			$user = new User();
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