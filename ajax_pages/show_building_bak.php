<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

$strSiteID=$_GET['id'];
$strSQL="Select * from t_building where site_id=$strSiteID";
$strRsBuildingArr=$DB->Returns($strSQL);
while($strRsBuilding=mysql_fetch_object($strRsBuildingArr))
{
	echo "<div class='building_folder' style='float:left; width:300px;'  onclick=ShowRoomName('".$strRsBuilding->building_id."')><span style='font-weight:normal;'>Building:</span> ".$strRsBuilding->building_name."</div>
	
	<div class='clear'></div>
	
	<div id='building_".$strRsBuilding->building_id."'></div>";
}
?>