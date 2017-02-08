<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

$building_id = $_GET['building_id'];
$year = $_GET['year'];

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$building_id;
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($time_zone);

$year_start_date = date("$year-01-01 00:00:00");
$year_end_date = date("$year-12-31 23:59:59");

//*********************  Date & Time conversion to UTC  ****************************//

$year_start_date = gmdate('Y-m-d H:i:s', strtotime($year_start_date));
$year_end_date = gmdate('Y-m-d H:i:s', strtotime($year_end_date));


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
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1 and display_type=2";
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
								$strSQL="Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=".$strRsLevel2->system_id;
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
													##########################################
													# Calculating Kwh
													##########################################
												
													if($strRsSystemNodes->available_system_node_serial<>'')
													{
														//$strSQL="select B.device_status_id, DATE_FORMAT(B.synctime,'%m') as month, DATE_FORMAT(B.synctime,'%Y-%m-%d %H:%i:00') as synctime, (B.kwhsystem-A.kwhsystem) as kwh  from ((SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystemNodes->available_system_node_serial` where `synctime` >= '$year_start_date' AND `synctime` <= '$year_end_date') as A inner join (SELECT distinct device_status_id, synctime, kwhsystem FROM `t_$strRsSystemNodes->available_system_node_serial` where  `synctime` >= '$year_start_date' AND `synctime` <= '$year_end_date') as B on ((A.`device_status_id`)+1=B.`device_status_id`) and A.synctime < B.synctime)";
														$strSQL="select DATE_FORMAT(synctime,'%m') as month, max(kwhsystem)-min(kwhsystem) as kwh FROM `t_$strRsSystemNodes->available_system_node_serial` where `synctime` >= '$year_start_date' AND `synctime` <= '$year_end_date' group by month";
                                                        $consumptionArr=$DB->Returns($strSQL);
                                                        while($consumption=mysql_fetch_object($consumptionArr))
                                                        {
                                                            //$output[$consumption->month] += floatval($consumption->kwh);
                                                            if(isset($output[$consumption->month])){
                                                                $output[$consumption->month] += floatval($consumption->kwh);
                                                            }else{
                                                                $output[$consumption->month] = floatval($consumption->kwh);
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
?>

<?php
// Setting multiplier

$strSQL="Select * from t_energy_multiplier where building_id=".$building_id;
$multiplierArr=$DB->Returns($strSQL);
$strMultiplier=mysql_fetch_object($multiplierArr);
if($strMultiplier->gas_multiplier && $strMultiplier->gas_multiplier != ""){
    $multiplier = $strMultiplier->gas_multiplier;
}else{
    $multiplier = 1;
}
?>

<?php
$strSQL="SELECT CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, min(`from`) as `from`, max(`to`) as `to`, sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.building_id = '$building_id' AND utility_account_type = 2 AND year = '$year' group by month order by month asc";
$strGasAccountArr=$DB->Returns($strSQL);

$utitlity_gas_consumption_total = 0;
$utitlity_gas_cost_total = 0;
$utitlity_gas_metered_total = 0;
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr style="background-color:#000000; font-weight:bold; color:#FFFFFF;">
        <td colspan="2" align="center" valign="middle" style="border:1px solid #CCCCCC;">Dates</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">Metered*</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">Billed</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">Diff.</td>
    </tr>
    <tr style="background-color:#EFEFEF; font-weight:bold;">
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">From</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">To</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">(Therms)</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">(Therms)</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">%</td>
    </tr>
    
    <?php if(mysql_num_rows($strGasAccountArr)>0) {
    while($strGasAccount=mysql_fetch_object($strGasAccountArr)) { 
        $utitlity_gas_consumption_total += $strGasAccount->consumption;
        $utitlity_gas_cost_total += $strGasAccount->cost;
        if(isset($output[str_pad($strGasAccount->month, 2, '0', STR_PAD_LEFT)])){
            $utitlity_gas_metered = $output[str_pad($strGasAccount->month, 2, '0', STR_PAD_LEFT)];
            $utitlity_gas_metered_total *= $multiplier;
            $utitlity_gas_metered_total += $utitlity_gas_metered;
            $diff = number_format((1-($strGasAccount->consumption/$utitlity_gas_metered))*100, 0);
        }else{
            $utitlity_gas_metered = 0;
            if($strGasAccount->consumption == 0){
                $diff = 0;
            }else{
                $diff = -100; 
            }
        }
        
    ?>
    <tr>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=$strGasAccount->from?></td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=$strGasAccount->to?></td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($utitlity_gas_metered/50, 0);?></td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($strGasAccount->consumption/50, 0);?></td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;" class="<?=$diff>10?'green_font':($diff<-10?'red_font':'');?>"><?=$diff?>%</td>
    </tr>
    <?php }} else { ?>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">--</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0%</td>
        </tr>
    <?php } ?>
    
    <?php 
        if($utitlity_gas_metered_total == 0){
            if($utitlity_gas_consumption_total == 0){
                $total_diff = 0;
            }else{
                $total_diff = -100; 
            }
        } else {
            $total_diff = number_format((1-($utitlity_gas_consumption_total/$utitlity_gas_metered_total))*100, 0);
        }
    ?>
    
    <tr style="font-weight:bold;">
        <td colspan="2" align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($utitlity_gas_metered_total/50, 0)?></td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($utitlity_gas_consumption_total/50, 0)?></td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;" class="<?=$total_diff>10?'green_font':($total_diff<-10?'red_font':'');?>"><?=$total_diff?>%</td>
    </tr>                       
</table>
<div style="font-size: 13px; margin-top: 5px;">*Adjusted with a calculated average utility rate multiplier</div>