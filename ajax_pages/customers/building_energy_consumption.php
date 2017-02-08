<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$type=Globals::Get('type');
$building_id=$_GET['building_id'];

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$building_id;
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($time_zone);

$date = $_GET['date']?$_GET['date']:"";

if($date != ""){
    $start_date = date('Y-m-d 00:00:00', strtotime($date));
    $end_date = date('Y-m-d 23:59:59', strtotime($date));
}else{
    $start_date = date("Y-m-d 00:00:00");
    $end_date = date("Y-m-d 23:59:59");
}

$start_month = date("Y-m-1 00:00:00");
$end_month = date_create("last day of $start_month")->format('Y-m-d 23:59:59');

//*********************  Date & Time conversion to UTC  ****************************//

$start_date = gmdate('Y-m-d H:i:s', strtotime($start_date));
$end_date = gmdate('Y-m-d H:i:s', strtotime($end_date));

$start_month = gmdate('Y-m-d H:i:s', strtotime($start_month));
$end_month = gmdate('Y-m-d H:i:s', strtotime($end_month));

$grand_total_kwh = 0;

$output = array();

$strSQL="Select * from t_system where system_id in (select Distinct parent_parent_parent_id from t_system_node where delete_flag=0 and building_id=$building_id)";
$strRsLevel1Arr=$DB->Returns($strSQL);
while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
{			
	$strSQL="Select * from t_system where system_id in(select Distinct parent_parent_id from t_system_node where delete_flag=0 and building_id=$building_id) and parent_id=".$strRsLevel1->system_id;
	$strRsLevel2Arr=$DB->Returns($strSQL);
	while($strRsLevel2=mysql_fetch_object($strRsLevel2Arr))
	{		
        $strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id in (Select system_id from t_system where parent_id in (Select system_id from t_system where parent_id in (".$strRsLevel2->system_id.")))";
        $strRsSystemNodesArr=$DB->Returns($strSQL);
        while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
        {
            if($strRsSystemNodes->available_system_node_serial<>'')
            {
                $DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date, $end_date);
                $grand_total_kwh=$grand_total_kwh+floatval($DataVal);
                
                $strSQL="Select DAY(synctime) as day_of_month, min(kwhsystem) as StartVal, max(kwhsystem) as EndVal from t_$strRsSystemNodes->available_system_node_serial where synctime >='$start_month' and synctime <='$end_month' group by day_of_month";
                $strRsValueArr=$DB->Returns($strSQL);
                while($strRsValue=mysql_fetch_object($strRsValueArr))
                {
                    $StartVal=$strRsValue->StartVal;
                    $EndVal=$strRsValue->EndVal;
                    $DataVal = $EndVal-$StartVal;
                    
                    if(isset($output[$strRsValue->day_of_month])){
                        $output[$strRsValue->day_of_month] += floatval($DataVal);
                    }else{
                        $output[$strRsValue->day_of_month] = floatval($DataVal);
                    }
                }
            }
        }
    }
}
rsort($output);
if(isset($output[0])){
    $highest = $output[0];
}else{
    $highest = 0;
}
?>

<div id="CircualChart_graph<?=$type?>" style="margin:0px auto;" data-dimension="130" data-text="<?=number_format($grand_total_kwh/293.071107, 2)?> MMBTU" data-info="" data-width="10" data-fontsize="14" data-percent="<?=($grand_total_kwh*100/$highest)?>" data-fgcolor="#61a9dc" data-bgcolor="#eee" data-fill="#ddd"></div>
<script>
$('#CircualChart_graph<?=$type?>').circliful();    
</script>