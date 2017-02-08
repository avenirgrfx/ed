<?php
	ob_start();
	session_start();
	require_once("../configure.php");
	require_once(AbsPath."classes/all.php");
	$macids=$_GET['values'];
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
	for($i = 0; $i < sizeof($macids);$i++){
		$macid = $macids[$i];
		//$macid = strtr ($macid, array ('-' => ''));
		$msg = Message::factory(new Mosquitto\Message());
		$msg->msg->topic = 'energyDAS/'.$macid.'/RFU';
		$msg->msg->payload = $macid;
		$msg->msg->qos = 1;
		$mid = $client->publish($msg->msg->topic, $msg->msg->payload, $msg->msg->qos);
		MQ::addPublish($mid, $msg);
		sleep(1);
	}

/*************************************************************************************/
		echo "Firmware Upgrade Initiated";
?>
