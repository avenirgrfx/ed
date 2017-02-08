
<?php
	ob_start();
	session_start();
	require_once("../configure.php");
	require_once(AbsPath."classes/all.php");
	$macid=$_GET['macid'];
	$values=$_GET['values'];
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
	/************WAN*************/
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/WAN/input';
	$msg->msg->payload = "uci set firewall.@zone[1].input=\"".strtoupper($values[0])."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/WAN/output';
	$msg->msg->payload = "uci set firewall.@zone[1].output=\"".strtoupper($values[1])."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/WAN/forward';
	$msg->msg->payload = "uci set firewall.@zone[1].forward=\"".strtoupper($values[2])."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/WAN/masquerading/mms';
	$msg->msg->payload = "uci set firewall.@zone[1].masquerading=\"".strtoupper($values[3])."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/WAN/mmscalmping';
	$msg->msg->payload = "uci set firewall.@zone[1].mmscalmping=\"".strtoupper($values[4])."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	/************LAN***********/
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/LAN/input';
	$msg->msg->payload = "uci set firewall.@zone[0].input=\"".strtoupper($values[5])."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/LAN/output';
	$msg->msg->payload = "uci set firewall.@zone[0].output=\"".strtoupper($values[6])."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/LAN/forward';
	$msg->msg->payload = "uci set firewall.@zone[0].forward=\"".strtoupper($values[7])."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/LAN/masquerading/mms';
	$msg->msg->payload = "uci set firewall.@zone[0].masquerading=\"".$values[8]."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg = Message::factory(new Mosquitto\Message());
	$msg->msg->topic = 'energyDAS/'.$macid.'/LAN/mmscalmping';
	$msg->msg->payload = "uci set firewall.@zone[0].mmscalmping=\"".$values[9]."\"";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);
	
	$msg->msg->topic = 'energyDAS/'.$macid.'/firewall';
	$msg->msg->payload = "/etc/init.d/firewall restart";
	$msg->msg->qos = 1;
	$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
	MQ::addPublish($mid, $msg);
	sleep(1);	
	
/*************************************************************************************/
		echo "Firewall settings saved";
?>
