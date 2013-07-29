<?php 
class user{
	
	private $users ;
	
	private $data_path;
	
	public function __construct($data_path=null){
		if(is_null($data_path)){
			$this->data_path = WEB_ROOT."data/users/users.php";
		}else{
			$this->data_path = $data_path;
		}
		$users = include($this->data_path);
		if(!is_array($users)){
			throw new Exception("用户系统出现异常！！");
		}else{
			$this->users = $users;
		}
	}
	
	public function getAllUsers(){
		return $this->users;
	}
	
	public function verifyUser($username,$password){
			foreach($this->users as $user){
				if($username == $user["username"] && $password == $user["password"]){
					return true;
				}
			}
			return false;
	}
	
	public function updateUser($name , $pwd_old,$pwd_new){
		$result = array(
			"success"=>false,
		);
		foreach($this->users as $key=>$user){
			if($name == $user["username"] && $pwd_old == $user["password"]){
				$this->users[$key]["password"]=$pwd_new;
				$result["success"] = true;
			}
		}
		if(!$result["success"]){
			$result["message"]="密码不正确！";
		}
		$data ="<?php return array(";
		foreach($this->users as $key=>$user){
			$data.= "array('username'=>'".$user["username"]."','password'=>'".$user["password"]."'),";
		}
		$data.=");";
		file_put_contents($this->data_path,$data);
		return $result;
	}
}