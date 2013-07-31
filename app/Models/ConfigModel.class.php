<?php 
class ConfigModel extends Model {
	
	public function __construct(){
		
		parent::__construct(Reg::get("db_config_table"));
	}
	
	
	
	public function create_config_table(){
		$sql = "CREATE TABLE `".$this->_prefix.$this->_table."` (
id int(6) not null primary key auto_increment,
table_name char(63) not null,
table_show_name char(63),
columns_info text,
show_list text
) engine=InnoDB DEFAULT CHARACTER SET=UTF8";
		return $this->execute($sql);
//echo $this->logDb();
//EXIT;
//return ;
	}
	
	public function get_tablename(){
		return $this->_prefix.$this->_table;
	}
	
	public function get_show_columns($table_name){
		$configs = $this->get_config($table_name);
		$show_list = explode(",",$configs["show_list"]);
		$columns_info = json_decode($configs["columns_info"]);
		$show_info = array();
		
		if(is_array($show_list) && !empty($show_list)){
			foreach($columns_info as $info_item){
				$info_item = (array)$info_item;
				if(in_array($info_item["Field"],$show_list)){
					$show_info[] = $info_item;
				}
			}
			return $show_info;
		}else{
			throw new Exception("查询显示列的信息失败！");
		}
	}
	
	public function set_show_columns($columns,$table_name){
		return $this->update(array("show_list"=>$columns),"table_name='".$table_name."'");
	}
	
	public function get_columns($table_name){
			$show_list = $this->get_show_columns($table_name);
			$list = $this->query("desc {$table_name}");
			foreach($list as $key=>$item){
				foreach($show_list as $show_list_item){
					if($item['Field']==$show_list_item['Field']){
						$list[$key]["is_show"] = true;
						$list[$key]["show_name"] = $show_list_item["show_name"];
						break;
					}
				}
				if(!isset($list[$key]["is_show"])){
					$list[$key]["is_show"] = false;
					$list[$key]["show_name"] = $list[$key]["Field"];
				}
			}
			return $list;
	}
	
	public function update_column_description($columns_desc,$table_name){
		$list = $this->query("desc {$table_name}");
		$save_value = array();
		foreach($list as $key=>$item){
			$is_find = false;
			foreach($columns_desc as $c_key=>$c_value){
				if($item['Field']==$c_key){
					$is_find = true;
					$save_value[] = array("Field"=>$item['Field'],"show_name"=>$c_value);
					break;
				}
			}
			if(!$is_find){
				$save_value[] = array("Field"=>$item['Field'],"show_name"=>$item['Field']);
			}
		}
		$save_value = json_encode($save_value);
		//handle chinese character
		$save_value = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $save_value);
		return $this->update(array("columns_info"=>$save_value),"table_name='".$table_name."'");
	}
	
	public function update_table_show_name($data){
		$return_value = true;
		foreach($data as $key=>$value){
			$result = $this->update(array("table_show_name"=>$value),"table_name='".$key."'");
			if(!$result){$return_value = false;}
		}
		return $return_value;
	}
	
	public function get_config($table_name){
		$table_config = $this->select("table_name='".$table_name."'");
		if(empty($table_config)){
			if($this->create_default_config($table_name)){
				return $this->get_config($table_name);
			}
		}else{
			return $table_config[0];
		}
		
	}
	
	public function get_pri($table){
		$columns = $this->query("desc $table");
		foreach($columns as $column){
			if($column["Key"]=="PRI"){
				return $column['Field'];
			}
		}
		//如果没有找到 则默认为id
		return "id";
	}
	
	protected function create_default_config($table_name){
		$columns = $this->query("desc $table_name");
		$show_list = "";
		$columns_info = array();
		foreach($columns as $column){
			$columns_info[] = array("Field"=>$column['Field'],"show_name"=>$column['Field']);
			$show_list .= $column['Field'].",";
		}
		$show_list = rtrim($show_list,",");
		$columns_info = json_encode($columns_info);
		$is_success = $this->execute("insert into `".$this->get_tablename()."` (`table_name`,`table_show_name`,`columns_info`,`show_list`) values ('".$table_name."','".$table_name."','".$columns_info."','".$show_list."')");
		if($is_success){
			return $is_success;
		}else{
			throw new Exception("创建默认配置失败！".$this->logDb());
		}
	}
	
	public function get_table_list(){
		$tables = $this->query("show tables");
		$tables_value = array();
		$config_table = $this->get_tablename();
		if(is_array($tables)){
			foreach($tables as $table){
				foreach($table as $table_name){
					//exclude config table
					if($table_name != $config_table) {
						$tables_value[] = array("name"=>$table_name,"show_name"=>$this->get_table_show_name($table_name));
					}
				}
			}
		}else{
			throw new Exception("查询数据库出现错误，请确认数据库的名称和密码是否正确！");
		}
		return $tables_value;
	}
	
	public function get_table_show_name($table_name){
		$config = $this->get_config($table_name);
		return $config["table_show_name"];
	}
}