<?php
class indexController extends Controller{
	public function indexAction(){
		
		if(!isset($_SESSION["username"])){
			redirect("http://".$_SERVER['HTTP_HOST'].$this->view->getIndexUrl()."/login");
		}
		$action = $this->getPost("action");
		if($action){
			$action.="Action";
			return $this->$action();
		}
		$model = new Model();
		$tables = $model->query("show tables");
		$tables_value = array();
		if(is_array($tables)){
			foreach($tables as $table){
				foreach($table as $table_name){
					$tables_value[] = $table_name;
				}
			}
		}else{
			$this->error("查询数据库出现错误，请确认数据库的名称和密码是否正确！");
		}
		$this->view->tables = $tables_value;
		if($this->getGet("table")){
			$selected_table = $this->getGet("table");
			if(!in_array($selected_table,$tables_value)){
				$this->error("不存在该表！");
			}
			$columns = $model->query("desc {$selected_table}");
			//D($columns,true);
			if(is_array($columns)){
				$this->view->columns = $columns;
			}
			$table_model = new Model($selected_table);
			$page_size = Reg::get("page_size");
			$count = $table_model->count();
			if($this->getGet("page")){
				$page = $this->getGet("page");
			}else{
				$page = 1;
			}
			$begin_num = $page_size*($page-1);
			$this->view->page_total =  ceil($count/$page_size);
			$this->view->count = $count;
			$this->view->data = $table_model->query("select * from {$selected_table} limit {$begin_num},{$page_size}");
		}
		$this->view->dbname = Reg::get("db_name");
		$this->display();
	}
	
	public function deleteAction(){
		$key = $this->getPost("pri")?$this->getPost("pri"):"id";
		$table = new Model($this->getGet("table"));
		$ids = rtrim($this->getPost("ids"),",");
		$result = array(
			"success"=>false,
		);
		if($table->delete("$key in ($ids)")){
			$result["success"]=true;
		}
		echo json_encode($result);
		return;
	}
	
	public function addAction(){
		
	}
	
	public function updateAction(){
		
		$pri_key = $this->getPost("pri_key")?$this->getPost("pri_key"):"id";
		$pri_val = $this->getPost("pri_val");
		$key = $this->getPost("key");
		$value = $this->getPost("val");
		
		$table = new Model($this->getGet("table"));
		
		$data =array(
			$key=>$value
		);
		$result = array(
			"success"=>false,
		);
		
		if($table->update($data,"$pri_key=$pri_val")){
			$result["success"]=true;
		}
		echo json_encode($result);
		return;
	}
	
	public function changepwdAction(){
		$this->display();
	}
	
	public function dochangepwdAction(){
		$old_pwd = $this->getPost("old_pwd");
		$new_pwd = $this->getPost("new_pwd");
		$username = $_SESSION["username"];
		include_once(WEB_ROOT."Models/User.php");
		$user = new user();
		$result=$user->updateUser($username , $old_pwd,$new_pwd);
		
		echo json_encode($result);
	}
}