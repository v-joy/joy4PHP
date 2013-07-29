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
show_list tinytext
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
		return $configs["show_list"];
	}
	
	public function set_show_columns($columns,$table_name){
		return $this->update(array("show_list"=>$columns),"table_name='".$table_name."'");
		
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
		$is_success = $this->execute("insert into `".$this->get_tablename()."` (`table_name`,`table_show_name`,`show_list`) values ('".$table_name."','".$table_name."','*')");
		if($is_success){
			return $is_success;
		}else{
			throw new Exception("创建默认配置失败！");
		}
	}
	
	public function get_table_list(){
		$tables = $this->query("show tables");
		$tables_value = array();
		$config_table = $this->get_tablename();
		//mark
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