<?php
class indexController extends Controller{
	
	public function __init(){
		//for cache bebuging
		//$cache = new Cache("File");
		//$cache->clear();
		//cache_curent_page();
		
		if(!isset($_SESSION["username"])){
			redirect("http://".$_SERVER['HTTP_HOST'].$this->view->getIndexUrl()."/login");
		}
	}
	
	public function indexAction(){
		$config_model = new ConfigModel();
		$tables = $config_model->get_table_list();
		$this->view->tables = $tables;
		$selected_table = $this->getGet("table");
		if($selected_table){
			$has_selected_table = false;
			foreach($tables as $item){
				if($item["name"]==$selected_table)$has_selected_table = true;
			}
			if(!$has_selected_table){throw new Exception("不存在该表！");}
			$columns = $config_model->query("desc {$selected_table}");
			$show_list = $config_model->get_show_columns($selected_table);
			
			if(is_array($show_list)){
				$this->view->columns = $show_list;
				foreach($show_list as $item){
					$columns_show .= $item['Field'].",";
				}
				$columns_show = rtrim($columns_show,",");
			}
			
			$table_model = new Model($selected_table,"");
			$page_size = Reg::get("page_size");
			if($this->getGet("page")){
				$page = $this->getGet("page");
			}else{
				$page = 1;
			}
			$condition = "1=1";
			if($this->getGet("is_search")){
				$all_columns = $config_model->get_columns($selected_table);
				$gets = $this->getGet();
				if(is_array($gets)){
					foreach($gets as $key=>$get){
						foreach($all_columns as $c_key=>$column){
							if($key==$column["Field"] && $get!=""){
								$c_type = substr($column["Type"],0,3);
								switch($c_type){
									case "int":
										//int
									case "flo":
										//float
									case "boo":
										//boolean
									case "dou":
										//double
										$condition.=" and $key=$get ";
										break;
									case "cha":
										//char
									case "var":
										//varchar
									case "tex":
										//text
										$condition.=" and $key like '%".$get."%' ";
										break;
									default :
										throw new Exception("unsupported type:".$c_type);
								}
							}
						}
					}
				}
			}
			//mark: $condition needs to filter sql injection
			$count_condition = "1=1";
			if($condition=="1=1"){
				$condition = "";
			}else{
				$count_condition = substr($condition,7);
				$condition = "where (".substr($condition,7)." )";
			}
			$begin_num = $page_size*($page-1);
			$count = $table_model->count($count_condition);
			$this->view->page_total =  ceil($count/$page_size);
			$this->view->count = $count;
			$this->view->primary_key = $config_model->get_pri($selected_table);
			$this->view->data = $table_model->query("select {$columns_show} from `{$selected_table}` $condition limit {$begin_num},{$page_size}");
		}
		$this->view->dbname = Reg::get("db_name");
		$this->display();
	}
	
/*	protected function get_search_condition($val,$Field,$table){
		
	}*/
	
	public function deleteAction(){
		$key = $this->getPost("pri")?$this->getPost("pri"):"id";
		$table = new Model($this->getGet("table"),"");
		$ids = rtrim($this->getPost("ids"),",");
		$result = array(
			"success"=>false,
		);
		if($table->delete("$key in ($ids)")){
			$result["success"]=true;
		}else{
			$result["message"]="删除失败：".$table->logDb();
		}
		
		echo json_encode($result);
		return;
	}
	
	public function addAction(){
		$config_model = new ConfigModel();
		$table = $this->getGet("table");
		if($this->isPost()){
			$keys = $this->getPost("data_key");
			$val = $this->getPost("data_value");
			$data = array_combine($keys,$val);
			$model = new Model($table,"");
			$success = $model->add($data);
			$result = array();
			if($success){
				$result["success"]=true;
			}else{
				$result["success"]=false;
				$result["message"]="更改失败，详细日志：".$model->logDb();
			}
			echo json_encode($result);
		}else{
			$list = $config_model->get_columns($table);
			$this->view->columns = $list;
			$this->view->table = $table;
			$this->display();
		}
	}
	
	public function column_manageAction(){
		$config_model = new ConfigModel();
		$table = $this->getGet("table");
		if($this->isPost()){
			$fields = $this->getPost("data_value");
			$fields = implode(",",$fields);
			if($config_model->set_show_columns($fields,$table)){
				$result["success"]=true;
			}else{
				$result["success"]=false;
				$result["message"]="更改失败，详细日志：".$config_model->logDb();
			}
			echo json_encode($result);
			
		}else{
			$this->view->columns = $config_model->get_columns($table);
			$this->view->table = $table;
			$this->display();
		}
	}
	
	public function table_manageAction(){
		$config_model = new ConfigModel();
		$table = $this->getGet("table");
		if($this->isPost()){
			$data_value = $this->getPost("data_value");
			$data_key = $this->getPost("data_key");
			$data = array_combine($data_key,$data_value);
			$success = $config_model->update_table_show_name($data);
			if($success){
				$result["success"]=true;
			}else{
				$result["success"]=false;
				$result["message"]="更改失败，详细日志：".$config_model->logDb();
			}
			echo json_encode($result);
		}else{
			$this->view->columns = $config_model->get_table_list();
			$this->view->table = $table;
			$this->display();
		}
	}
	
	public function description_manageAction(){
		$config_model = new ConfigModel();
		$table = $this->getGet("table");
		if($this->isPost()){
			$keys = $this->getPost("data_key");
			$values = $this->getPost("data_value");
			$descriptions = array_combine($keys,$values);
			$success = $config_model->update_column_description($descriptions,$table);
			if($success){
				$result["success"]=true;
			}else{
				$result["success"]=false;
				$result["message"]="更改失败，详细日志：".$config_model->logDb();
			}
			echo json_encode($result);
			
		}else{
			$this->view->columns = $config_model->get_columns($table);
			$this->view->table = $table;
			$this->display();
		}
	}
	
	public function viewAction(){
		$table = $this->getGet("table");
		$pri = $this->getGet("pri");
		$model = new Model($table);
		$configModel = new ConfigModel();
		$pri_key= $configModel->get_pri($table); 
		$list = $configModel->get_columns($table);
		$this->view->columns = $list;
		$this->view->values = $model->select($pri_key."=".$pri);
		$this->view->values = $this->view->values[0];
		$this->display();
	}
	
	public function updateAction(){
		
		$pri_key = $this->getPost("pri_key")?$this->getPost("pri_key"):"id";
		$pri_val = $this->getPost("pri_val");
		$key = $this->getPost("key");
		$value = $this->getPost("val");
		
		$table = new Model($this->getGet("table"),"");
		
		$data =array(
			$key=>$value
		);
		$result = array(
			"success"=>false,
		);
		
		if($table->update($data,"$pri_key=$pri_val")){
			$result["success"]=true;
		}
		$result["mess"]=$table->logDb();
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
		$user = new User();
		$result=$user->updateUser($username , $old_pwd,$new_pwd);
		
		echo json_encode($result);
	}
}