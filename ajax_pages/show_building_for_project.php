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
	echo "
	<div style='background-color:#DDDDDD; margin-bottom:10px; padding:5px;'>
	<div class='building_folder' style='float:left; width:600px; font-size:16px;'  onclick=ShowRoomNameForProject('".$strRsBuilding->building_id."')><span id='Plus_Minus_Project_Building_".$strRsBuilding->building_id."'>-</span><span style='font-weight:normal;'>Building:</span> ".$strRsBuilding->building_name."</div>
	<div style='float:left;'><input type='text' name='txtProjectName_Building_".$strRsBuilding->building_id."' id='txtProjectName_Building_".$strRsBuilding->building_id."' placeholder='New Building Project Name' /></div>
	<div style='float:left; margin-left:10px;  padding:2px 5px; margin-top:3px; cursor:pointer; border:1px solid #CCCCCC;' onclick=AddBuildingProject('".$strRsBuilding->building_id."')>Add</div>
	<div class='clear'></div>
	</div>";
	
	
	echo "<div id='building_for_project_".$strRsBuilding->building_id."' style='margin-bottom:5px;'>";
	
	
	$strSQL="Select * from t_projects where building_id=".$strRsBuilding->building_id." and room_id=0";
	$strRsBuildingProjectsArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsBuildingProjectsArr)>0)
	{
		print "<div style='border-bottom:1px dashed #CCCCCC; background-color:#DDDDDD; margin-left:100px; text-decoration:underline; font-size:14px; font-weight:bold;'>Building Projects</div>";
	}
	while($strRsBuildingProjects=mysql_fetch_object($strRsBuildingProjectsArr))
	{
		print "<div style='border-bottom:1px dashed #CCCCCC; margin-left:100px;'>
			<div style='width:400px; float:left;'><a href='javascript:LoadProjectDetails(".$strRsBuildingProjects->projects_id.")'>".$strRsBuildingProjects->project_name."</a></div>
			<div style='float:left; font-size:13px; color:#999999; font-style:italic;'>Created On: ".Globals::DateFormat($strRsBuildingProjects->doc)." by - XYZ</div>
			<div style='float:right; font-size:12px;'><a href='javascript:DeleteProject(".$strRsBuildingProjects->projects_id.",".$strSiteID.",1)'>Delete</a></div>
			<div class='clear'></div>
		</div>";
	}
	
	
	$strBuildingID=$strRsBuilding->building_id;
	$strSQL="Select * from t_room where building_id=$strBuildingID";
	$strRsRoomArr=$DB->Returns($strSQL);
	while($strRsRoom=mysql_fetch_object($strRsRoomArr))
	{
		echo "
		<div class='room_folder' id='room_icon_".$strRsRoom->room_id."'>
		<div style='float:left; width:350px; cursor:pointer; margin-top:3px; text-decoration:underline; font-weight:bold;' onclick=ShowRoomNodeDetailsForProject('".$strRsRoom->room_id."')><span style='font-weight:normal;'><span id='Room_Project_Plus_Minus_".$strRsRoom->room_id."'>+</span>Room: </span>".$strRsRoom->room_name."</div>
		<div style='float:left;  text-align:center;  width:35px; margin-top:3px;' id='WidgetPrefix_".$strRsRoom->room_id."'></div>
		<div style='float:left; '><input type='text' placeholder='New Room Project Name' name='txtProjectName_Room_".$strRsRoom->room_id."' id='txtProjectName_Room_".$strRsRoom->room_id."' value='' /></div>
		<div style='float:left; margin-left:10px;  padding:2px 5px; margin-top:3px; cursor:pointer; border:1px solid #CCCCCC;' onclick=AddRoomProject('".$strRsRoom->room_id."')>Add</div>
		<div class='clear'></div>
		</div>
		<div id='room_for_project_".$strRsRoom->room_id."'></div>";
	}
	
	echo "</div>";
}
?>