<?php
	ob_start();
	session_start();
	require_once("../configure.php");
	require_once(AbsPath."classes/all.php");
	$mac_id=$_GET['mac_id'];
	$name=$_GET['mac_name'];
	$mac_name = $string = str_replace(' ', '', $name);
	$action = $_GET['action'];
	$router_id = $_GET['router_id'];
	$date = date('Y-m-d');
	$counter = 0;
	$DB=new DB;
	$mac_id = strtr ($mac_id, array (':' => '-'));
	$mac_id = strtolower($mac_id);
	if(strcmp($action,'Add') == 0){
		$strSQL = "select * from t_router where `router_macid` = '".$mac_id."'";
		$total = $DB->Total($strSQL);
		if($total == 0 && $mac_id != ''){
			$strSQL="insert into t_router (`router_name`,`router_macid`,`router_date`) values ('".$mac_name."','".$mac_id."','".$date."')";
			$returnId=$DB->Execute($strSQL);
			$output = "New MAC-ID Added!";
		}
		else{
			$output = "Sorry,MAC-ID already used!";
		}
		echo $output;
	}
	else if(strcmp($action,'Change') == 0){
		$strSQL = "update t_router set router_name = '".$mac_name."',`router_macid` = '".$mac_id."',`router_date` = '".$date."'  where `router_id` = '".$router_id."'";
		$result_id = $DB->Execute($strSQL);
		echo "MAC Name changed";
	}
	else {}
?>
