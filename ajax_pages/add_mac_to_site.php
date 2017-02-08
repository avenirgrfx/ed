<?php
	ob_start();
	session_start();
	require_once("../configure.php");
	require_once(AbsPath."classes/all.php");
	$router_ids=$_GET['router_ids'];
	$router_idsF=$_GET['router_idsF'];
	$siteID=$_GET['siteID'];
	$i = 0;
	$DB=new DB;
	while($router_ids[$i] != NULL){
		$strSQL = "update t_router set site_id = '".$siteID."'  where `router_id` = '".$router_ids[$i]."'";
		$result_id = $DB->Execute($strSQL);
		$strSQL = "update t_router set site_id = 0  where `router_id` = '".$router_idsF[$i]."'";
		$result_id = $DB->Execute($strSQL);
		$i++;
	}
	$i = 0;
	while($router_idsF[$i] != NULL){
		$strSQL = "update t_router set site_id = 0  where `router_id` = '".$router_idsF[$i]."'";
		$result_id = $DB->Execute($strSQL);
		$i++;
	}
	$val = $router_ids[0];
	echo "MACs added to/removed from the site";
?>

