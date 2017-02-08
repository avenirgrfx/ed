<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath.'classes/system.class.php');

$DB=new DB;
$System=new System;


if($_GET['Mode']=='Delete' and $_GET['SystemProjectID']<>'')
{
	$strSQL="Delete from t_project_system where project_system_id=".$_GET['SystemProjectID'];
	$DB->Execute($strSQL);
	print "Done";
	exit();
}


if($_POST['SystemID']<>"" and $_POST['ProjectID']<>"")
{
	$strSQL="Insert into t_project_system (project_id, system_id, doc)
	Values(".$_POST['ProjectID'].",".$_POST['SystemID'].", now() )";
	$DB->Execute($strSQL);
	print "Done";
	exit();
}

$project_id=$_GET['project_id'];

$strSQL="Select * from t_projects where projects_id=".$project_id;
$strRsProjectDetailsArr=$DB->Returns($strSQL);
while($strRsProjectDetails=mysql_fetch_object($strRsProjectDetailsArr))
{
?>
<h2><?php if($strRsProjectDetails->room_id==0){ print "Building "; } else { print "Room "; }?>Project: <?php echo $strRsProjectDetails->project_name?></h2>
<div style="padding:5px;">	
	Add Systems to Project<br />

<div style="float:left;">
    <select id="ddlSystemForProject" name="ddlSystemForProject">    	
       <?php $System->ListSystem();?>
    </select>
    <input type="hidden" name="txt_project_id" id="txt_project_id" value="<?php echo $project_id;?>" />
</div>

<div style="float:left; width:50px; height:20px; border:1px solid #999999; padding:3px; text-align: center; margin-left: 5px; cursor:pointer;" id="AddProjectSystem" onclick="AddProjectSystem()">Add</div>

<div class="clear"></div>
<div style="border-bottom:1px dashed #CCCCCC; margin-top:10px;"></div>
<div style="font-weight:bold; background-color:#DDDDDD; padding:2px; font-size:16px;">System Projects</div>
<?php
}
$strSQL="Select t_system.system_name, t_project_system.project_system_id from t_system, t_project_system  where t_system.system_id=t_project_system.system_id and t_project_system.project_id=".$project_id;
$strRsProjectSystemArr=$DB->Returns($strSQL);
while($strRsProjectSystem=mysql_fetch_object($strRsProjectSystemArr))
{
	print "<div style='float:left;'>".$strRsProjectSystem->system_name."</div>
	<div style='float:right; font-size:12px;'><a href='javascript:DeleteProjectSystem(".$strRsProjectSystem->project_system_id.",".$project_id.")'>Delete</a></div>
	<div class='clear'></div>";
}
?>
</div>

<div style="padding:3px; width:50px; height:20px; border:1px solid #CCCCCC; cursor:pointer; bottom: 5px; right: 5px; position: absolute; text-align: center; background-color: #CCC;" onclick="CloseProjectDetailsDiv()">Close</div>



