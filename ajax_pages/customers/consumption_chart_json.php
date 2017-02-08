<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
$DB=new DB;

$building_id=$_GET['building_id'];
$parent_id=$_GET['parent_id'];
$strType=$_GET['type'];
$month = isset($_GET['month']) ? $_GET['month'] : "";
$year = isset($_GET['year']) ? $_GET['year'] : "";

$strSQL="Select time_zone, daylight_saving from t_building where building_id=$building_id ";
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $daylight_saving = $strTime_zone->daylight_saving;
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
    $timezoneOffset = Globals::GetTimezoneOffset($strTime_zone->time_zone, $daylight_saving);
}
date_default_timezone_set($time_zone);
$timezoneOffsetMilliSec = $timezoneOffset*60*60*1000;

//$time_zone_array = explode("_", $time_zone);
//if(sizeof($time_zone_array) == 2){
//    $timezoneOffset = $time_zone_array[1];
//}else{
//    $timezoneOffset = -5;
//}

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


global $level1Arr;
global $level2Arr;
global $level3Arr;
global $level4Arr;


function getParent($strChild)
{	
	global $level1Arr;
	global $level2Arr;
	global $level3Arr;
	global $level4Arr;
	
	$DB=new DB;
	$strSQL="Select parent_id, system_name, system_id, level from t_system where system_id=$strChild ";
	$strRsGetParentIDArr=$DB->Returns($strSQL);
	while($strRsGetParentID=mysql_fetch_object($strRsGetParentIDArr))
	{		
		if($strRsGetParentID->level==1)
		{
			if(is_array($level1Arr) && count($level1Arr)>0)
			{
				if(! in_array($strRsGetParentID->system_id, $level1Arr))
				{
					$level1Arr[]=$strRsGetParentID->system_id;
				}				
			}
			else
			{
				$level1Arr[]=$strRsGetParentID->system_id;
			}
		}
		elseif($strRsGetParentID->level==2)
		{
			 //$level2Arr[]=$strRsGetParentID->system_id;
			 
			if(is_array($level2Arr) && count($level2Arr)>0)
			{
				if(! in_array($strRsGetParentID->system_id, $level2Arr))
				{
					$level2Arr[]=$strRsGetParentID->system_id;
				}				
			}
			else
			{
				$level2Arr[]=$strRsGetParentID->system_id;
			}
			 
		}
		elseif($strRsGetParentID->level==3)
		{
			// $level3Arr[]=$strRsGetParentID->system_id;
			if(is_array($level3Arr) && count($level3Arr)>0)
			{
				if(! in_array($strRsGetParentID->system_id, $level3Arr))
				{
					$level3Arr[]=$strRsGetParentID->system_id;
				}				
			}
			else
			{
				$level3Arr[]=$strRsGetParentID->system_id;
			}
		}
		elseif($strRsGetParentID->level==4)
		{
			 //$level4Arr[]=$strRsGetParentID->system_id;
			if(is_array($level4Arr) && count($level4Arr)>0)
			{
				if(! in_array($strRsGetParentID->system_id, $level4Arr))
				{
					$level4Arr[]=$strRsGetParentID->system_id;
				}				
			}
			else
			{
				$level4Arr[]=$strRsGetParentID->system_id;
			}
		}
		
		getParent($strRsGetParentID->parent_id);
	}
}

$strSQL="select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsSystemsArr=$DB->Returns($strSQL);
while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
{
	getParent($strRsSystems->system_id);
}
$output = array();
if(is_array($level1Arr) && count($level1Arr)>0)
{
	$totalKwh____=0;
    $last_totalKwh____=0;
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1 and exclude_in_calculation=0 and display_type=$strType";
		$strRsLevel1Arr=$DB->Returns($strSQL);
		while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
		{						
			if(is_array($level2Arr) && count($level2Arr)>0)
			{
				foreach($level2Arr as $val2)
				{					
					$strSQL="Select * from t_system where system_id=$val2 and parent_id=".$strRsLevel1->system_id;
					$strRsLevel2Arr=$DB->Returns($strSQL);
					while($strRsLevel2=mysql_fetch_object($strRsLevel2Arr))
					{						
						if(is_array($level3Arr) && count($level3Arr)>0)
						{
							foreach($level3Arr as $val3)
							{	
								$strSQL="Select * from t_system where system_id=$val3 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{	
                                    if(is_array($level4Arr) && count($level4Arr)>0)
									{
										foreach($level4Arr as $val4)
										{
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{											
												$strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=".$strRsLevel4->system_id;
												$strRsSystemNodesArr=$DB->Returns($strSQL);
												
												while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
                                                {
                                                    if($strRsSystemNodes->available_system_node_serial<>'')
                                                    {  
                                                        //$start_date = '2015-09-24 0:00:00';
                                                        $strSQL="select device_status_id, UNIX_TIMESTAMP(DATE_FORMAT(synctime,'%Y-%m-%d %H:%i:00')) as synctime, kwh_diff as kwh  from `t_$strRsSystemNodes->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date'";
                                                        //$strSQL="select B.device_status_id, UNIX_TIMESTAMP(DATE_FORMAT(B.synctime,'%Y-%m-%d %H:%i:00')) as synctime, (B.kwhsystem-A.kwhsystem) as kwh  from ((SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystemNodes->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by synctime) as A inner join (SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystemNodes->available_system_node_serial` where  `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by synctime) as B on (A.`device_status_id`)+1=B.`device_status_id`)";
                                                        $consumptionArr=$DB->Returns($strSQL);
                                                        while($consumption=mysql_fetch_object($consumptionArr))
                                                        {
                                                            if(isset($output[intval($consumption->synctime)*1000 + $timezoneOffsetMilliSec])){
                                                                $output[intval($consumption->synctime)*1000 + $timezoneOffsetMilliSec] += floatval($consumption->kwh);
                                                            }else{
                                                                $output[intval($consumption->synctime)*1000 + $timezoneOffsetMilliSec] = floatval($consumption->kwh);
                                                            }
                                                        }
                                                    }
                                                }
											}
										}
									}																		
								}								
							}
						}
					}				
				}
			}	
		}		
	}
}
$final_output = array();
foreach ($output as $key=>$value){
    array_push($final_output, array($key, $value/293.071107));
}
usort($final_output, function($a, $b) {
    return $a[0] - $b[0];
});
echo json_encode($final_output);exit;



//// dummy data
//$output = array();
//$strSQL="Select UNIX_TIMESTAMP(synctime) as synctime, kwhsystem from t_lhnode_001EC6051601001";
//$strRsGetParentIDArr=$DB->Returns($strSQL);
//while($strRsGetParentID=mysql_fetch_object($strRsGetParentIDArr))
//{
//    array_push($output, array(intval($strRsGetParentID->synctime), floatval($strRsGetParentID->kwhsystem)));
//}
//echo json_encode($output);exit;


?>
