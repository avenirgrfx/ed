<?php 
	ob_start();
	session_start();
	require_once("../configure.php");
	require_once(AbsPath."classes/all.php");
	$router_macid = $_GET['macid'];
	$router_rx = $_GET['rx'];
	$router_tx = $_GET['tx'];
	$router_alarm = $_GET['alarm'];
	$router_logs = $_GET['logs'];
	$router_version = $_GET['version'];
	$date = date("Y-m-d H:i:s");
	echo ($date);
	$DB=new DB;
	$strSQL = "update t_router set version = '".$router_version."',rx = '".$router_rx."',tx = '".$router_tx."',alarmstatus = '".$router_alarm."',logs = '".$router_logs."',active_time = '".$date."'  where `router_macid` = '".$router_macid."'";
	$result_id = $DB->Execute($strSQL);
	echo "success";
	
?>
