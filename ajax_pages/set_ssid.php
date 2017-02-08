<?php
	ob_start();
	session_start();
	require_once("../configure.php");
	require_once(AbsPath."classes/all.php");
	$option = $_GET['optn'];
	$router_id=$_GET['router_id'];
	$DB=new DB;
	if($option == '1'){
		$newValue=$_GET['value'];
		if($newValue == ''){
			echo "Invalid data";exit();
		}
	}
	if($option == '2'){
		$protocol = $_GET['protocol'];
		$gateway = $_GET['gateway'];
		$ipaddress = $_GET['ipaddress'];
		$ipnetmask = $_GET['ipnetmask'];
		if($ipaddress != ''){
			$strSQL="Select * from t_router where ipaddress='".$ipaddress."' and router_id NOT IN ('".$router_id."')";
			$total = $DB->Total($strSQL);
			if($total != 0){
				echo "Sorry,IP Address already used!";exit();
			}
		}
	}
	$label1 = 'SSID';
	
	
	if($option == '2'){
		$label1 = 'Protocol and IP Details';
		if($protocol == 1){
			$label1 = 'staticip';
			$newValue = $ipaddress.'/'.$ipnetmask.'/'.$gateway;
		}
		else{
			$label1 = 'DHCP';
			$newValue = 'DHCP/'.$ipaddress.'/'.$ipnetmask.'/'.$gateway;
		}
	}
		
		
		$strSQL="Select * from t_router where router_id='".$router_id."'";
		$getMACArr=$DB->Returns($strSQL);	
		$getMac=mysql_fetch_object($getMACArr);
		$mac_id = $getMac->router_macid;
	//	$mac_id = strtr ($mac_id, array ('-' => ''));
		
		/****************************mqttp code************************************************/
		class MQ {
			public static $publish = array();
			public static $receive = array();
			public static function addPublish($mid, $msg) {
			$msg->id = $mid;
			self::$publish[$mid] = $msg;
		}

		public static function confirm($mid) {
			if(array_key_exists($mid, self::$publish)) {
				self::$publish[$mid]->state = true;
			}
		}

		public static function addReceive($msg) {
			$msg = Message::factory($msg, true);
			self::$receive[$msg->id] = $msg;
		}
		}

		class Message {
			public $id;
			public $state = false;
			public $msg;
			public static function factory(Mosquitto\Message $msg, $state = false) {
				$message = new Message();
				$message->state = $state;
				$message->msg = $msg;
				$message->id = $msg->mid;
				return $message;
			}
		}
		$client = new Mosquitto\Client('client.terminal.onpublish', false);

		$client->onMessage(function($msg) {
			print_r(array('receive', $msg));
			MQ::addReceive($msg);
		});

		$client->onPublish(function($mid) {
			MQ::confirm($mid);
			print_r(array('comfirm publish', MQ::$publish[$mid]));
		});
		$client->onConnect(function($rc, $msg) {
			print_r(array('rc' => $rc, 'message' => $msg));
		});

		$client->connect('54.149.195.40', 1883, 60);

		sleep(1);
		$msg = Message::factory(new Mosquitto\Message());
		if($option == '1'){
			$msg->msg->topic = 'energyDAS/'.$mac_id.'/ssid';
			$msg->msg->payload = "uci set wireless.wmesh.ssid=".$newValue;
			$msg->msg->qos = 0;
			$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
			MQ::addPublish($mid, $msg);
			sleep(1);
		}
		else
		{
			if($protocol == '1'){
				$msg->msg->topic = 'energyDAS/'.$mac_id.'/ssid';
				$msg->msg->payload = "uci set network.wan.proto=static";
				$msg->msg->qos = 0;
				$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
				MQ::addPublish($mid, $msg);
				sleep(1);
				
				$msg->msg->topic = 'energyDAS/'.$mac_id.'/ssid';
				$msg->msg->payload = "uci set network.wan.ipaddr=".$ipaddress;
				$msg->msg->qos = 0;
				$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
				MQ::addPublish($mid, $msg);
				sleep(1);
				
				$msg->msg->topic = 'energyDAS/'.$mac_id.'/ssid';
				$msg->msg->payload = "uci set network.wan.netmask=".$ipnetmask;
				$msg->msg->qos = 0;
				$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
				MQ::addPublish($mid, $msg);
				sleep(1);
				
				$msg->msg->topic = 'energyDAS/'.$mac_id.'/ssid';
				$msg->msg->payload = "uci set network.wan.gateway=".$gateway;
				$msg->msg->qos = 0;
				$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
				MQ::addPublish($mid, $msg);
				sleep(1);
			}
			else{
				$msg->msg->topic = 'energyDAS/'.$mac_id.'/ssid';
				$msg->msg->payload = "uci set network.wan.proto=dhcp";
				$msg->msg->qos = 0;
				$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
				MQ::addPublish($mid, $msg);
				sleep(1);
			}
		}
		$msg->msg->topic = 'energyDAS/'.$mac_id.'/ssid';
		$msg->msg->payload = "/etc/init.d/network restart";
		$msg->msg->qos = 0;
		$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
		MQ::addPublish($mid, $msg);
		sleep(1);	

/*************************************************************************************/
		if($option == '1')
			$strSQL = "update t_router set ssid = '".$newValue."' where `router_id` = '".$router_id."'";
		if($option == '2')
			$strSQL = "update t_router set protocol='".$protocol."',ipaddress = '".$ipaddress."',ipnetmask = '".$ipnetmask."',gateway = '".$gateway."'   where `router_id` = '".$router_id."'";
		
		$result_id = $DB->Execute($strSQL);
		$val = $router_ids[0];
		echo strtoupper($label1)." saved";

?>

