<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

$isChecked=$_GET['isChecked'];
$system_node_id=$_GET['strSystemNodeID'];


$strSQL="Select * from t_thn_widget where system_node_id=$system_node_id and external_flag=1";
$strRsLink_Unlink_ExternalArr=$DB->Returns($strSQL);
if($strRsLink_Unlink_External=mysql_fetch_object($strRsLink_Unlink_ExternalArr))
{
	# If Already Exist
	if($isChecked=='true')
	{
		$strSQL="Update t_thn_widget set external_visible=1 where system_node_id=$system_node_id";
		$DB->Execute($strSQL);
	}
	else
	{
		$strSQL="Update t_thn_widget set external_visible=0 where system_node_id=$system_node_id";
		$DB->Execute($strSQL);
	}
	

	
}
else
{
	# Create New
	$strSQL="Select * from t_thn_widget where system_node_id=$system_node_id";
	$strRsLink_Unlink_ExternalArr=$DB->Returns($strSQL);
	while($strRsLink_Unlink_External=mysql_fetch_array($strRsLink_Unlink_ExternalArr))
	{
		//
		foreach($strRsLink_Unlink_External as $key=>$Val)
		{
			$$key=$Val;
		}
		
		$widget_serial_number=$widget_serial_number.'EXT';
		$external_flag=1;
		$external_visible=1;
		
		$strSQL="Insert into t_thn_widget(system_id, system_node_id, widget_serial_number, temperature_flag, temperature_type, temperature_alarm_low, 
		temperature_alarm_high, temperature_1, temperature_color_1, temperature_2, temperature_color_2, temperature_3, temperature_color_3, 
		humidity_flag, humidity_low, humidity_high, humidity_1, humidity_color_1, humidity_2, humidity_color_2, humidity_3, humidity_color_3, 
		external_flag, external_visible)
		
		Values($system_id, $system_node_id, '$widget_serial_number', $temperature_flag, $temperature_type, $temperature_alarm_low, 
		$temperature_alarm_high, $temperature_1, '$temperature_color_1', $temperature_2, '$temperature_color_2', $temperature_3, '$temperature_color_3', 
		$humidity_flag, $humidity_low, $humidity_high, $humidity_1, '$humidity_color_1', $humidity_2, '$humidity_color_2', $humidity_3, '$humidity_color_3', 
		$external_flag, $external_visible)";
		
		$DB->Execute($strSQL);
	}
}

print 'Done';

?>