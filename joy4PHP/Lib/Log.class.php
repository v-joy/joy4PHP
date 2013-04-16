<?php
class Log{
	
	static function write($message=null , $level=null, $message_type = 0 , $destination=null , $extra_headers=null ){
		if(empty($message))return;
		$message_type = self::getType($message_type);
		$level= $level?$level:Reg::get("config_log_level");
		$time = time();
		//3 means file
		if($message_type==3){
			if(empty($destination)){
				$destination = Reg::get("config_log_destination")."log_".date("Y-m-d").".log";
			}
			if(!is_file($destination)){
				$fp = fopen($destination,"w+");
				//mark: there is a bug : when the path do not exists , it will cause an error
				fwrite($fp,"[   time    level ]  message\r\n");
				fclose($fp);
			}
		}
		error_log("[ $time $level ] $message\r\n",$message_type,$destination,$extra_headers);
	}
	
	static function getType($message_type){
		$message_type = $message_type?$message_type:Reg::get("config_log_type");
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
	
}