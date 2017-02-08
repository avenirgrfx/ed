<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/projects.class.php");

$DB=new DB;
$Project=new Project;

if($_GET['Mode']=='Delete' and $_GET['ProjectID']<>'')
{
	$Project->DeleteProject($_GET['ProjectID']);
	print "Deleted";
	exit();
}


$building_id=$_POST['building_id'];
if($_POST['room_id']=='')
{
	$room_id=0;
}	
else
{
	$room_id=$_POST['room_id'];
	$strSQL="Select building_id from t_room where room_id=$room_id";
	$strRsRoomsArr=$DB->Returns($strSQL);
	while($strRsRooms=mysql_fetch_object($strRsRoomsArr))
	{
		$building_id=$strRsRooms->building_id;
	}
}


if($building_id<>'')
{
	$strSQL="Select client_id from t_building where building_id=".$building_id;
	$strRsClientIDArr=$DB->Returns($strSQL);
	while($strRsClientID=mysql_fetch_object($strRsClientIDArr))
	{
		$client_id=$strRsClientID->client_id;
	}
}

if($client_id<>'' and $client_id<>0)
{
	$strSQL="Select site_id from t_building where building_id=$building_id";
	$strRsSiteDetailsArr=$DB->Returns($strSQL);
	if($strRsSiteDetails=mysql_fetch_object($strRsSiteDetailsArr))
	{
		$site_id=$strRsSiteDetails->site_id;
	}
	
	$arr=array(
		'project_name'=>$_POST['project_name'], 
		'client_id'=>$client_id,
		'building_id'=>$building_id,
		'room_id'=>$room_id,
		'account_manager'=>$_SESSION['user_login']->user_id,
		'created_by'=>$_SESSION['user_login']->user_id,
		'modified_by'=>$_SESSION['user_login']->user_id,
		'delete_flag'=>0);
	
	$Project->setVal($arr);
	$Project->InsertProject();
	print $site_id;
}
else
{
	print "Invalid Building or Room";
}

?>