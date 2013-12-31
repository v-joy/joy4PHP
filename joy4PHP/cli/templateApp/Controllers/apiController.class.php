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
			//mark:真正使用时 需要修改$break
			$break = "\n";
			//$break = "<br>";
			$item = $this->dev->select("DEV_SN='".$sn."'");
			if(is_array($item) && !empty($item)){
				echo "hostname ".$item[0]["EL_HOSTNAME"].$break;
			}
			$item = $this->nic->select("DEV_SN='".$sn."'");
			if(is_array($item) && !empty($item)){
				foreach($item as $feild){
					echo $feild["NIC_NAME"]." ".$feild["IPADDR"]." ".$feild["NETMASK"]." ".$feild["GATEWAY"].$break;
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
		D($this->getGet());
		$id = "mark";//mark : 需要根据条件查找到之前插入的数据
		
		//request_url = http://localhost/joy4php/app/index.php/api/postback?lo=00:00:00:00:00:00&eth3=d8:9d:67:18:9c:2b&eth2=d8:9d:67:18:9c:2a&eth1=d8:9d:67:18:9c:29&eth0=d8:9d:67:18:9c:28&SN=6CU322ZZBT
		
		return;//=================一下的代码需要重新审视
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
	
	
	/*
	protected function showError($msg){
		echo "success:0\n";
		echo "error_massage:$msg";
	}
	*/
}