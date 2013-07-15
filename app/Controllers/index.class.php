<?php
class indexController extends Controller{
	public function indexAction(){
		
		if(!isset($_SESSION["username"])){
			redirect("http://".$_SERVER['HTTP_HOST'].$this->view->getIndexUrl()."/login");
		}
		if($this->getPost("action")){
			print_r($this->getPosts());exit;
		}
		$model = new Model();
		$tables = $model->query("show tables");
		$tables_value = array();
		if(is_array($tables)){
			foreach($tables as $table){
				foreach($table as $table_name){
					$tables_value[] = $table_name+",";
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
	
	public function changpwdAction(){
		
	}
}