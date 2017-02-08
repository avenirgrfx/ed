

<?php
	ob_start();
	session_start();
	require_once("../configure.php");
	require_once(AbsPath."classes/all.php");
	$macid=$_GET['macid'];
	$val=$_GET['values'];
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
	//$macid = strtr ($macid, array ('-' => ''));
	/*$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/mesh/mode/essid/bssid/ip4a';
	$msg->msg->payload = uci set firewall.@zone[0].input=strtoupper(values[0]);+'/'+values[1]+'/'+values[2]+'/'+values[3]+'/'+values[4];
	if($values[0] == '2'){ 
		$msg->msg->topic = 'energyDAS/'.$macid.'/mesh/mode/essid/bssid/ip4address/ip4gateway';
		$msg->msg->payload = values[0]+'/'+values[1]+'/'+values[2]+'/'+values[3]+'/'+values[4]+'/'+values[5];
	}
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);*/
	if($val[0] == 1) $mode = 'adhoc1'; else $mode = 'mesh1';
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/mesh';
	$msg->msg->payload = "uci set network.mesh.mode=".$mode;
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/mesh';
	$msg->msg->payload = "uci set wireless.wmesh.ssid=".$val[1];
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	if($values[0] == 1){
		$msg = Message::factory(new Mosquitto\Message());
		$msg->msg->topic = 'energyDAS/'.$macid.'/mesh';
		$msg->msg->payload = "uci set wireless.wmesh.bssid=".$val[2];
		$msg->msg->qos = 1;
		$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
		MQ::addPublish($mid, $msg);
		sleep(1);
	}
	if($values[0] == 0 ){
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/mesh';
	$msg->msg->payload = "uci set network.mesh.ipaddr=".$val[3];
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	

		$msg = Message::factory(new Mosquitto\Message());
		$msg->msg->topic = 'energyDAS/'.$macid.'/mesh';
		$msg->msg->payload = "uci set network.mesh.gateway=".$val[4];
		$msg->msg->qos = 1;
		$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
		MQ::addPublish($mid, $msg);
		sleep(1);
	}
	
	
	
/*************************************************************************************/
		echo "Mesh Configuration settings saved";
?>
