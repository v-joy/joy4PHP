<?php
class indexController extends Controller{
	public function indexAction(){
		
		if(!isset($_SESSION["username"])){
			redirect("http://".$_SERVER['HTTP_HOST'].$this->view->getIndexUrl()."/login");
		}
		//mark: 目前好像没有用到
		$action = $this->getPost("action");
		if($action){
			$action.="Action";
			return $this->$action();
		}
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
			if($show_list!="*")	$show_list = explode(",",$show_list);
			$show_list_array=array();
			foreach($columns as $key=>$item){
				if($show_list=="*" || in_array($item['Field'],$show_list) ){//|| $item['Key']=="PRI"
					$show_list_array[] = $item;
				}
			}
			
			if(is_array($show_list_array)){
				$this->view->columns = $show_list_array;
			}
			$columns_show = $config_model->get_show_columns($selected_table);
			$table_model = new Model($selected_table,"");
			$page_size = Reg::get("page_size");
			if($this->getGet("page")){
				$page = $this->getGet("page");
			}else{
				$page = 1;
			}
			$condition = "1=1";
			if($this->isPost()){
				$posts = $this->getPost();
				if(is_array($posts)){
					foreach($posts as $key=>$post){
						foreach($this->view->columns as $c_key=>$column){
							if($key==$column["Field"] && $post!=""){
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
										$condition.=" and $key=$post ";
										break;
									case "cha":
										//char
									case "var":
										//varchar
									case "tex":
										//text
										$condition.=" and $key like '%".$post."%' ";
										break;
								}
							}
						}
					}
				}
			}
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
			$this->view->data = $table_model->query("select {$columns_show} from `{$selected_table}` $condition limit {$begin_num},{$page_size}");
		}
		$this->view->dbname = Reg::get("db_name");
		$this->display();
	}
	
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
			$keys = $this->getPost("keys");
			$val = $this->getPost("val");
			$keys = explode(",",rtrim($keys,","));
			$val = explode(",",rtrim($val,","));
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
			$list = $config_model->query("desc {$table}");
			$this->view->columns = $list;
			$this->view->table = $table;
			$this->display();
		}
	}
	
	public function column_manageAction(){
		$config_model = new ConfigModel();
		$table = $this->getGet("table");
		if($this->isPost()){
			$fields = $this->getPost("fields");
			$fields = rtrim($fields,",");
			if($config_model->set_show_columns($fields,$table)){
				$result["success"]=true;
			}else{
				$result["success"]=false;
				$result["message"]="更改失败，详细日志：".$config_model->logDb();
			}
			echo json_encode($result);
			
		}else{
			$show_list = $config_model->get_show_columns($table);
			if($show_list!="*")	$show_list = explode(",",$show_list);
			$list = $config_model->query("desc {$table}");
			foreach($list as $key=>$item){
				if($show_list=="*" || in_array($item['Field'],$show_list)){
					$list[$key]["is_show"] = true;
				}else{
					$list[$key]["is_show"] = false;
				}
			}
			$this->view->columns = $list;
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
		$list = $model->query("desc {$table}");
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