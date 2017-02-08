<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

$strRoomID=$_GET['id'];

/*$strSQL="Select * from t_system_node where room_id=$strRoomID";
$strRsRoonWidgetsArr=$DB->Returns($strSQL);
while($strRsRoonWidgets=mysql_fetch_object($strRsRoonWidgetsArr))
{
echo "
	<div style='float:left; margin-left:130px; width:205px; font-size:14px; cursor:pointer; margin-top:5px;' id='room_widget_serial_icon_".$strRoomID."_".$strRsRoonWidgets->system_node_id."' class='room_widget_icon' onclick=ShowWidgetSerialNumber('".$strRoomID."','".$strRsRoonWidgets->system_node_id."')><span style='font-weight:bold;' id='PlusMinus_Node_".$strRsRoonWidgets->system_node_id."'>+</span>Node: ".$strRsRoonWidgets->node_serial."</div>
	<div class='clear'></div>
	<div id='room_widget_serial_".$strRoomID."_".$strRsRoonWidgets->system_node_id."'></div>";
}*/



//$strSQL="Select Distinct t_projects.project_name, t_system_node.project_id from t_system_node,t_projects where t_system_node.project_id=t_projects.projects_id and t_system_node.room_id=$strRoomID";
$strSQL="Select Distinct t_projects.project_name, projects_id project_id from t_projects where t_projects.room_id=$strRoomID";
$strRsProjectsArr=$DB->Returns($strSQL);
if(mysql_num_rows($strRsProjectsArr)>0)
{
	print "<div style='margin-left:90px; margin-top:10px; margin-bottom:20px;'>";
	while($strRsProjects=mysql_fetch_object($strRsProjectsArr))
	{
        print '<div style="border-bottom:1px dashed #CCCCCC; background-color:#DDDDDD; text-decoration:underline; font-size:14px; font-weight:bold;">Room Projects</div>';
		print "Project: <b>".$strRsProjects->project_name."</b><br />";
		
		//$strSQL="Select system_node_id, node_serial, custom_name from t_system_node where project_id=".$strRsProjects->project_id." and room_id=".$strRoomID;
		$strSQL="Select system_node_id, node_serial, custom_name from t_system_node where delete_flag=0 and project_id=".$strRsProjects->project_id;
		$strRsNodeByProjectsArr=$DB->Returns($strSQL);
		while($strRsNodeByProjects=mysql_fetch_object($strRsNodeByProjectsArr))
		{
			if(strstr( strtolower($strRsNodeByProjects->node_serial) ,'thn'))
				$ExternalNode=1;
			else
				$ExternalNode=0;
			
			
			if($ExternalNode==1)
			{
				$strSQL="Select thn_widget_id from t_thn_widget where system_node_id=".$strRsNodeByProjects->system_node_id." and external_visible=1";
				$strRsExternalVisibleArr=$DB->Returns($strSQL);
				
				if($strRsExternalVisible=mysql_fetch_object($strRsExternalVisibleArr))
				{
					$strChecked=' Checked ';
				}
				else
				{
					$strChecked='';
				}
				print "
				<div style='margin-left:100px; width:350px; float:left;  cursor:pointer;'><span style='font-weight:bold;' id=Node_Details_Plus_Minus_".$strRsNodeByProjects->system_node_id.">+</span>Node: <a href='javascript:ShowNode_Details(".$strRsNodeByProjects->system_node_id.")' style='text-decoration:underline;'>".$strRsNodeByProjects->node_serial. ($strRsNodeByProjects->custom_name<>'' ? " (".$strRsNodeByProjects->custom_name.")" : "" ). "</a>
				</div>
				<div style='float:left; margin-left:10px;'>
					<div style='float:left; margin-top: -3px;'><input $strChecked  onclick='Link_External_Sensor(".$strRsNodeByProjects->system_node_id.")' type='checkbox' name='chkExternal_Sensor_For_".$strRsNodeByProjects->system_node_id."' id='chkExternal_Sensor_For_".$strRsNodeByProjects->system_node_id."' value='1' /></div>
					<div style='float:left; margin-left:3px;'>External Sensor</div>
					<div class='clear'></div>
				</div>
				<div class='clear'></div>";
			}
			else
			{
				print "
				<div style='margin-left:100px;  cursor:pointer; color:'><span style='font-weight:bold;' id=Node_Details_Plus_Minus_".$strRsNodeByProjects->system_node_id.">+</span>Node: <a href='javascript:ShowNode_Details(".$strRsNodeByProjects->system_node_id.")' style='text-decoration:underline;'>".$strRsNodeByProjects->node_serial.($strRsNodeByProjects->custom_name<>'' ? " (".$strRsNodeByProjects->custom_name.")" : "" )."</a>
				</div>";
			}
		
			
			print "<div id='Node_Details_".$strRsNodeByProjects->system_node_id."' style='display:none;'></div>";
		}
	}
	print "</div>";
}


?>