<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
/*print "<pre>";
print_r($_POST);
print "</pre>";
*/
# For THN
$WidgetSerailNumber=Globals::GenWidgetSerial('thn',$_POST['NodeSerial']);
$widget_type=12;

$room_id=$_POST['Room_ID'];
$strSQL="Select t_building.building_id, t_sites.site_id from t_building, t_room, t_sites 
where t_building.site_id=t_sites.site_id 
And t_room.building_id=t_building.building_id 
And t_room.room_id=".$room_id;
$strRsRoomSiteArr=$DB->Returns($strSQL);
while($strRsRoomSite=mysql_fetch_object($strRsRoomSiteArr))
{
	$building_id=$strRsRoomSite->building_id;
	$site_id=$strRsRoomSite->site_id;
}

$widget_serial_number=$WidgetSerailNumber;
$temperature_flag=1; # If Temperature Widget is checked for this Project
$temperature_type=($_POST['TempType']=='F' ? '1' : '2');
$temperature_alarm_low=$_POST['TempLow'];
$temperature_alarm_high=$_POST['TempHigh']; 
$temperature_1=$_POST['Widget_Temp1'];
$temperature_color_1=$_POST['Widget_Temp_Color_1'];
$temperature_2=$_POST['Widget_Temp2'];
$temperature_color_2=$_POST['Widget_Temp_Color_2'];
$temperature_3=$_POST['Widget_Temp3'];
$temperature_color_3=$_POST['Widget_Temp_Color_3']; 
$humidity_flag=1; # If Humidity Widget is checked for this Project
$humidity_low=$_POST['HumidityLow'];
$humidity_high=$_POST['HumidityHigh'];
$humidity_1=$_POST['Widget_Humidity1'];
$humidity_color_1=$_POST['Widget_Humidity_Color_1'];
$humidity_2=$_POST['Widget_Humidity2'];
$humidity_color_2=$_POST['Widget_Humidity_Color_2'];
$humidity_3=$_POST['Widget_Humidity3'];
$humidity_color_3=$_POST['Widget_Humidity_Color_3'];

$strSQL="Insert into  t_thn_widget(widget_serial_number, temperature_flag, temperature_type, temperature_alarm_low, temperature_alarm_high, 
temperature_1, temperature_color_1, temperature_2, temperature_color_2, temperature_3, temperature_color_3, 
humidity_flag, humidity_low, humidity_high, humidity_1, humidity_color_1, humidity_2, humidity_color_2, humidity_3, humidity_color_3)

Values('$widget_serial_number', $temperature_flag, $temperature_type, $temperature_alarm_low, $temperature_alarm_high, 
$temperature_1, '$temperature_color_1', $temperature_2, '$temperature_color_2', $temperature_3, '$temperature_color_3', 
$humidity_flag, $humidity_low, $humidity_high, $humidity_1, '$humidity_color_1', $humidity_2, '$humidity_color_2', $humidity_3, '$humidity_color_3')";

$widget_id=$DB->Execute($strSQL);


$device_serial="THN".$_POST['NodeSerial'];
$project_id=$_POST['id'];
$strSQL="Insert into t_project_widget_links(project_id, site_id, building_id, room_id, widget_type, widget_id, device_serial, doc)
Values($project_id, $site_id, $building_id, $room_id, $widget_type, $widget_id, '$device_serial' , now() )";
$DB->Execute($strSQL);


print $widget_id."~".$widget_serial_number;


?>