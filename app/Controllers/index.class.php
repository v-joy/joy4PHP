<?php
class indexController extends Controller{
	public function indexAction(){
		if(!$_SESSION["user_id"]){
			header("Location");
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
			
			$this->view->count = $count;
			$this->view->data = $table_model->select();
		}
		$this->view->dbname = Reg::get("db_name");
		$this->display();
	}
	
	public function login(){
		
	}
}