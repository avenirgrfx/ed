<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

print "<div style='margin-bottom:50px;'>";
$strSiteID=$_GET['id'];
$strSQL="Select * from t_sites where client_id=$strSiteID order by site_name";
$strRsSiteArr=$DB->Returns($strSQL);
while($strRsSite=mysql_fetch_object($strRsSiteArr))
{
	echo "<div style='font-size:20px;' class='site_folder' onclick=ShowBuildingNameForProject('".$strRsSite->site_id."')><span style='font-weight:normal; '>Site: </span> ".$strRsSite->site_name."</div><div id='Project_".$strRsSite->site_id."'></div>";
}
print "</div>";
?>