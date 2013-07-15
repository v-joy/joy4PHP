<?php
class apiController extends Controller{
	
	protected $log;
	protected $dev;
	protected $nic;
	
	public function __construct(){
		$this->log = new Model("el_log");
		$this->dev = new Model("el_devlist");
		$this->nic = new Model("el_niclist");
	}
	
	public function indexAction(){
		$sn = $this->getGet("sn");
		$data=array(
			"querytime"=>time(),
			"sn"=>$sn,
			"querysuccess"=>0
		);
		
		if($sn){
			$item = $this->nic->select("DEV_SN='".$this->getGet("sn")."'");
			if(!is_array($item) || empty($item)){
				//当找不到记录时，应该怎么处理？
				$this->showError("not found");
			}else{
				if(count($item)!=1){
					//mark 当取到多个记录的时候应该怎么处理
					echo "mark 待处理:发现".count($item)."条符合条件的数据。"; 
					D($item);
				}else{
					//正常情况下。输出必要的信息
					$item = $item[0];
					if(!is_array($item) || empty($item)){
						$this->showError("not match");
					}else{
						$data["querysuccess"] = 1;
						echo "DEV_SN:".$item["DEV_SN"]."\n";
						echo "NIC_NAME:".$item["NIC_NAME"]."\n";
						echo "IPADDR:".$item["IPADDR"]."\n";
						echo "NETMASK:".$item["NETMASK"]."\n";
						echo "GATEWAY:".$item["GATEWAY"]."\n";
						echo "SPEED:".$item["SPEED"];
					}
				}
			}
		}else{
			$this->showError("miss parameter:sn");
		}
		
		if(!$this->log->add($data)){
			Log::write("插入log失败：".json_encode($data));
		}
	}
	
	public function postbackAction(){
		//操作是否成功的回调函数
		$id = "mark";//mark : 需要根据条件查找到之前插入的数据
		$success=trim($this->getGet("success"));
		$data=array(
			"postbacktime" => time(),
			"settingsuccess" => 0,
		);
		if($success=="1"){
			//mark 操作成功进行的操作
			$data["settingsuccess"]=1;
		}else{
			//mark 操作失败进行的操作
			$error_message = $this->getGet("error_message");
			$data["settingerror"]="pxe error:".$error_message;
		}
		$this->log->update($data,"id=$id");

	}
	
	protected function showError($msg){
		echo "success:0\n";
		echo "error_massage:$msg";
	}
}