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

if($year != "" && $month != ""){
    $start_date = date("$year-$month-01 00:00:00");
    $end_date = date_create("last day of $start_date")->format('Y-m-d 23:59:59');
    $last_start_date = date_create("$start_date first day of last month")->format('Y-m-d 00:00:00');
    $last_end_date = date_create("$end_date last day of last month")->format('Y-m-d 23:59:59');
}else{
    $start_date = date("Y-m-01 00:00:00");
    $end_date = date("Y-m-d 23:59:59");
    $last_start_date = date_create("first day of last month")->format('Y-m-d 00:00:00');
    $last_end_date = date_create("last day of last month")->format('Y-m-d 23:59:59');
}
   
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

$strSQL="select Distinct system_id from t_system_node where building_id=$building_id";
$strRsSystemsArr=$DB->Returns($strSQL);
while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
{
	getParent($strRsSystems->system_id);
}
$output = array();
if(is_array($level4Arr) && count($level4Arr)>0)
{
    foreach($level4Arr as $val4)
    {
        $strSQL="Select * from t_system where system_id=$val4";
        $strRsLevel4Arr=$DB->Returns($strSQL);
        while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
        {											
            $strSQL="Select * from t_system_node where building_id=$building_id and system_id=".$strRsLevel4->system_id;
            $strRsSystemNodesArr=$DB->Returns($strSQL);
            while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
            {
                if($strRsSystemNodes->available_system_node_serial<>'')
                {
                    //$start_date = '2015-09-24 0:00:00';
                    $strSQL="select B.device_status_id, UNIX_TIMESTAMP(DATE_FORMAT(B.synctime,'%Y-%m-%d %H:%i:00')) as synctime, (B.kwhsystem-A.kwhsystem) as kwh  from ((SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystemNodes->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date') as A inner join (SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystemNodes->available_system_node_serial` where  `synctime` >= '$start_date' AND `synctime` <= '$end_date') as B on ((A.`device_status_id`)+1=B.`device_status_id`) and A.synctime < B.synctime)";
                    //$strSQL="select B.device_status_id, UNIX_TIMESTAMP(DATE_FORMAT(B.synctime,'%Y-%m-%d %H:%i:00')) as synctime, (B.kwhsystem-A.kwhsystem) as kwh  from ((SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystemNodes->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by synctime) as A inner join (SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystemNodes->available_system_node_serial` where  `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by synctime) as B on (A.`device_status_id`)+1=B.`device_status_id`)";
                    $consumptionArr=$DB->Returns($strSQL);
                    while($consumption=mysql_fetch_object($consumptionArr))
                    {
                        $new = 1;
                        foreach ($output as $one_output){
                            if($one_output[0] == intval($consumption->synctime)*1000){
                                $one_output[1] += floatval($consumption->kwh);
                                $new = 0;
                            }
                        }
                        if($new == 1){
                            array_push($output, array(intval($consumption->synctime)*1000, floatval($consumption->kwh)));
                        }
                    }
                }
            }
        }
    }
}
usort($output, function($a, $b) {
    return $a[0] - $b[0];
});
echo json_encode($output);exit;



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