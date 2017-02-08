<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
$DB=new DB;

$building_id=$_GET['building_id'];
$strType=$_GET['strType'];
$month = isset($_GET['month']) ? $_GET['month'] : "";
$year = isset($_GET['year']) ? $_GET['year'] : "";

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$building_id;
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($time_zone);

if($year != "" && $month != ""){
    $start_date = date("$year-$month-01 00:00:00");
    $end_date = date_create("last day of $start_date")->format('Y-m-d 23:59:59');
}else{
    $start_date = date("Y-m-01 00:00:00");
    $end_date = date("Y-m-d 23:59:59");
}
   
//*********************  Date & Time conversion to UTC  ****************************//

$start_date = gmdate('Y-m-d H:i:s', strtotime($start_date));
$end_date = gmdate('Y-m-d H:i:s', strtotime($end_date));

print '<div style="height: 200px; overflow-y: scroll;">';

$grand_total_kwh = 0;

$strSQL="Select * from t_system where system_id in (select Distinct parent_parent_parent_id from t_system_node where delete_flag=0 and display_type=$strType and building_id=$building_id)";
$strRsLevel1Arr=$DB->Returns($strSQL);
while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
{			
	print '<div><div style="color:#607BA7; font-weight: bold;">'.$strRsLevel1->system_name.'</div>';
	$strSQL="Select * from t_system where system_id in(select Distinct parent_parent_id from t_system_node where delete_flag=0 and building_id=$building_id) and parent_id=".$strRsLevel1->system_id;
	$strRsLevel2Arr=$DB->Returns($strSQL);
	while($strRsLevel2=mysql_fetch_object($strRsLevel2Arr))
	{		
        $total_kwh = 0;
        
        $strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id in (Select system_id from t_system where parent_id in (Select system_id from t_system where parent_id in (".$strRsLevel2->system_id.")))";
        $strRsSystemNodesArr=$DB->Returns($strSQL);
        while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
        {
            if($strRsSystemNodes->available_system_node_serial<>'')
            {
                $DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date, $end_date);
                $total_kwh=$total_kwh+floatval($DataVal);
            }
        }
        
        $grand_total_kwh = $grand_total_kwh + $total_kwh;
		print '<div style="margin-left: 10px; float:left;">'.$strRsLevel2->system_name.'</div><div style="margin-right: 10px; float:right;">'.number_format($total_kwh,0).' kWh</div><div class="clear"></div>';
	}
	print '</div><div class="clear"></div>';
}

print '</div>';

?>

<div class="clear" style="border-bottom:2px solid #DDDDDD; margin:10px 0px;"></div>

<div style="margin-left: 10px; float:left;font-weight: bold;">TOTAL <?=$strType==1?"ELECTRIC":($strType==2?"NATURAL GAS":"WATER")?> SYSTEMS</div>
<div style="margin-right: 5px; float:right; background: #607BA7;padding: 5px 15px;color:#fff"><?=number_format($grand_total_kwh,0)?> kWh</div><div class="clear"></div>