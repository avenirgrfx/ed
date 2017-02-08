<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/widgets.class.php");


$DB=new DB;
$Widgets=new Widgets;

$system_node_id=$_GET['strSystemNodeID'];
$strSQL="Select t_system_node.*, t_system.prefix from t_system_node, t_system where t_system_node.system_id=t_system.system_id And system_node_id=$system_node_id";
$strRsNodeDetailsArr=$DB->Returns($strSQL);
if($strRsNodeDetails=mysql_fetch_object($strRsNodeDetailsArr))
{
	$prefix=strtolower($strRsNodeDetails->prefix);
	
	# For THN Widgets
	if($prefix=='thn')
	{
		$strSQL="Select * from t_thn_widget where system_node_id=".$strRsNodeDetails->system_node_id." and external_flag=0";
		$strRsWidgetsArr=$DB->Returns($strSQL);
		while($strRsWidgets=mysql_fetch_object($strRsWidgetsArr))
		{
			print "<div style='margin-left:150px; border-top:1px dashed #CCCCCC;'><div style='float:left; width:308px;'>".$strRsWidgets->widget_serial_number."</div>
			<div id='Node_Settings_Behavior_Link_1_".$strRsWidgets->thn_widget_id."' style='float:left; text-decoration:underline; color:#999999; cursor:pointer; text-transform:uppercase; width:65px;' onclick='THN_Node_Settings_Behavior_Link(".$strRsNodeDetails->system_node_id.",1,".$strRsWidgets->thn_widget_id.")'>Settings</div>
			<div id='Node_Settings_Behavior_Link_2_".$strRsWidgets->thn_widget_id."' style='float:left; margin-left:50px; text-decoration:underline; color:#999999; cursor:pointer; text-transform:uppercase; width:65px;' onclick='THN_Node_Settings_Behavior_Link(".$strRsNodeDetails->system_node_id.",2,".$strRsWidgets->thn_widget_id.")' >Behavior</div>
			<div id='Node_Settings_Behavior_Link_3_".$strRsWidgets->thn_widget_id."' style='float:left; margin-left:50px; text-decoration:underline; color:#999999; cursor:pointer; text-transform:uppercase; width:65px;' onclick='THN_Node_Settings_Behavior_Link(".$strRsNodeDetails->system_node_id.",3,".$strRsWidgets->thn_widget_id.")'>Link</div>
			<div class='clear'></div>
			</div>";
			print "<div id='Node_Settings_Behavior_Link_".$strRsWidgets->thn_widget_id."' style='display:none; margin-top:10px;'></div>";
		}
		
		$strSQL="Select * from t_thn_widget where system_node_id=".$strRsNodeDetails->system_node_id." and external_flag=1 and external_visible=1";
		$strRsWidgetsArr=$DB->Returns($strSQL);
		while($strRsWidgets=mysql_fetch_object($strRsWidgetsArr))
		{
			print "<div style='margin-left:150px; border-top:1px dashed #CCCCCC;'><div style='float:left; width:308px;'>".$strRsWidgets->widget_serial_number."</div>			
			<div id='Node_Settings_Behavior_Link_1_".$strRsWidgets->thn_widget_id."' style='float:left; text-decoration:underline; color:#999999; cursor:pointer; text-transform:uppercase; width:65px;' onclick='THN_Node_Settings_Behavior_Link(".$strRsNodeDetails->system_node_id.",1,".$strRsWidgets->thn_widget_id.")'>Settings</div>
			<div id='Node_Settings_Behavior_Link_2_".$strRsWidgets->thn_widget_id."' style='float:left; margin-left:50px; text-decoration:underline; color:#999999; cursor:pointer; text-transform:uppercase; width:65px;' onclick='THN_Node_Settings_Behavior_Link(".$strRsNodeDetails->system_node_id.",2,".$strRsWidgets->thn_widget_id.")'>Behavior</div>
			<div id='Node_Settings_Behavior_Link_3_".$strRsWidgets->thn_widget_id."' style='float:left; margin-left:50px; text-decoration:underline; color:#999999; cursor:pointer; text-transform:uppercase; width:65px;' onclick='THN_Node_Settings_Behavior_Link(".$strRsNodeDetails->system_node_id.",3,".$strRsWidgets->thn_widget_id.")'>Link</div>
			<div class='clear'></div>
			</div>";
			print "<div id='Node_Settings_Behavior_Link_".$strRsWidgets->thn_widget_id."' style='display:none; margin-top:10px;'></div>";
		}
		
		
		
	}
	else
	{
		print "<div style='margin-left:150px;'>Under Construction</div>";
	}
}
?>