<?php
class Log{
	
	static $logs = array();
	
	static function record($message=NULL,$level=NULL){
		$level= $level?$level:Reg::get("log_level");
		if(!self::checkLevel($level)){
			return false;
		}
		$time = time();
		self::$logs[] = "[ $time $level ] $message\r\n";
	}
	
	static function save($message_type=NULL,$destination=NULL,$extra_headers=NULL){
		$message_type = self::getType($message_type);
		if($message_type==3){
			if(empty($destination)){
				$destination = Reg::get("log_destination")."log_".date("Y-m-d").".log";
			}
			self::checkPath($destination);
		}
		error_log(implode("",self::$logs),$message_type,$destination,$extra_headers);
	}
	
	static function write($message=null , $level=null, $message_type = 3 , $destination=null , $extra_headers=null ){
		if(!$message)return;
		
		$level= $level?$level:Reg::get("log_level");
	if(!self::checkLevel($level)){
			return false;
		}
		$time = time();
		$message_type = self::getType($message_type);
		//3 means file
		if($message_type==3){
			if(empty($destination)){
				$destination = Reg::get("log_destination")."log_".date("Y-m-d").".log";
			}
			self::checkPath($destination);
		}
		/*  
		//testing mail log
		$message_type = 1;
		$destination="917038132@qq.com";
		$extra_headers = "Subject: Foo\n From: junling.liu@corp.elong.com\n";*/
		error_log("[ $time $level ] $message\r\n",$message_type,$destination,$extra_headers);
	}
	
	static function checkPath($destination){
			$path = dirname($destination);
			if(!is_dir($path)){
				@mkdir($path,0777,true);
			}
			if(!is_file($destination)){
				$fp = fopen($destination,"w+");
				fwrite($fp,"[   time    level ]  message\r\n");
				fclose($fp);
			}
	}
	
	static function getType($message_type){
		$message_type = $message_type?$message_type:Reg::get("log_type");
		$message_type = strtolower($message_type);
		$code = 3;
		switch($message_type){
			case "file":
				$code = 3;
				break;
			case "system":
				$code = 0;
				break;
			case "mail":
				$code = 1;
				break;
			default:
				//type is not supported , use file instead
				$code=3;
				break;
		}
		return $code;
	}
	
	static function checkLevel($level){
		$levels = array(
			"info"=>1,
			"notice"=>2,
			"warning"=>3,
			"error"=>4,
			"none"=>5
		); 
		if($level=="none" || !isset($levels[$level])){
			return false;
		}
		$configLeval = Reg::get("log_level");
		if( $levels[$level] >= $levels[$configLeval]){
			return true;
		}else{
			return false;
		}
	}
	
}