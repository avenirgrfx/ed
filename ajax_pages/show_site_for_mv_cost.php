<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

$strSiteID=$_GET['id'];
$strSQL="Select * from t_sites where client_id=$strSiteID order by site_name";
$strRsSiteArr=$DB->Returns($strSQL);
while($strRsSite=mysql_fetch_object($strRsSiteArr))
{
	echo "<div class='site_folder' style='cursor:auto; font-size:20px;'><span style='font-weight:normal;'>Site: </span> <span style='cursor:pointer;' onclick=ShowBuildingNameMVCost('".$strRsSite->site_id."')>".$strRsSite->site_name."</span></div>
	<div id='".$strRsSite->site_id."'></div>";
}
?>
