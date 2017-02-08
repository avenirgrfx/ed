<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$project_details_name=$_POST['name'];
$json_data=$_POST['d'];
$project_id=$_POST['project_id'];
$edit_id=$_POST['edit_id'];

$strSQL="Select * from t_projects where projects_id=$project_id";
$strRsProjectsArr=$DB->Returns($strSQL);
if($strRsProjects=mysql_fetch_object($strRsProjectsArr))
{
	$client_id=$strRsProjects->client_id;
	$building_id=$strRsProjects->building_id;
	$room_id=$strRsProjects->room_id;
	$project_id=$strRsProjects->projects_id;
	
	$strSQL="Select site_id from t_building where building_id=$building_id";
	$strRsSiteArr=$DB->Returns($strSQL);
	while($strRsSite=mysql_fetch_object($strRsSiteArr))
	{
		$site_id=$strRsSite->site_id;
	}
	
	$created_by=$modified_by=$_SESSION['user_login']->user_id;
	
	
	if($edit_id=='' or $edit_id==0)
	{
		$strSQL="Insert into t_project_details (client_id, site_id, building_id, room_id, project_id,  project_details_name, json_data, created_by, modified_by, doc, dom, delete_flag) 
		values($client_id, $site_id, $building_id, $room_id, $project_id,   '".$project_details_name."','".$json_data."', $created_by, $modified_by, now(), now(), 0)";
		$DB->Execute($strSQL);
	}
	else
	{
		$strSQL="Update t_project_details set project_details_name='".$project_details_name."',  json_data='".$json_data."', modified_by=$modified_by, dom=now() where project_details_id= $edit_id";
		$DB->Execute($strSQL);
	}
	print "Saved";
}
else
{
	print "Couldn't saved!";
}


?>