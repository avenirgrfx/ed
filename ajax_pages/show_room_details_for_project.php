<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

$strRoomID=$_GET['id'];
$strSQL="Select * from t_projects where room_id=".$_GET['id'];
$strRsRoomProjectsArr=$DB->Returns($strSQL);
if(mysql_num_rows($strRsRoomProjectsArr)>0)
{
	print "<div style='border-bottom:1px dashed #CCCCCC; background-color:#DDDDDD; margin-left:100px; text-decoration:underline; font-size:14px; font-weight:bold;'>Room Projects</div>";
}
while($strRsRoomProjects=mysql_fetch_object($strRsRoomProjectsArr))
{
	print "<div style='border-bottom:1px dashed #CCCCCC; margin-left:100px;'>
		<div style='width:400px; float:left;'><a href='javascript:LoadProjectDetails(".$strRsRoomProjects->projects_id.")'>".$strRsRoomProjects->project_name."</a></div>
		<div style='float:left; font-size:13px; color:#999999; font-style:italic;'>Created On: ".Globals::DateFormat($strRsRoomProjects->doc)." by - XYZ</div>
		<div style='float:right; font-size:12px;'><a href='javascript:DeleteProject(".$strRsRoomProjects->projects_id.",".$strRoomID.",2)'>Delete</a></div>
		<div class='clear'></div>
	</div>";
}
?>
