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

if(is_array($level1Arr) && count($level1Arr)>0)
{
	$year_totalKwh____=0;
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1 and display_type=1";
		//$strSQL="Select * from t_system where system_id=$val1 and display_type=$strType";
		$strRsLevel1Arr=$DB->Returns($strSQL);
		while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
		{			
			//print "<div style='font-weight:bold;'>".$strRsLevel1->system_name."</div>";
			
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
							$year_totalKwh___=0;
							foreach($level3Arr as $val3)
							{
									
								$strSQL="Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{
									$DataVal=0;
															
									if(is_array($level4Arr) && count($level4Arr)>0)
									{
										$year_totalKwh__=0;
										foreach($level4Arr as $val4)
										{
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{											
												$DataVal=0;																							
												
												$strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=".$strRsLevel4->system_id;
												$strRsSystemNodesArr=$DB->Returns($strSQL);
												
												$year_totalKwh_=0;
												while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
												{
													##########################################
													# Calculating Kwh
													##########################################
												
													$DataVal=0;
													if($strRsSystemNodes->available_system_node_serial<>'')
													{
														$year_totalKwh_+=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $year_start_date, $year_end_date);
                                                    }
												?>
                                               
                                                <?php
												}
												$year_totalKwh__+=$year_totalKwh_;
											?>
                                           
                                            <?php
											}
										}
										$year_totalKwh___+=$year_totalKwh__;
									}									
									?>
                                    	
                                    <?php								
								}								
							}
							$year_totalKwh____+=$year_totalKwh___;
						}
						
					}
					
				}
			}
		
		}
		
	}
}

$e_year = $year_totalKwh____;

if(is_array($level1Arr) && count($level1Arr)>0)
{
	$year_totalKwh____=0;
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1 and display_type=2";
		//$strSQL="Select * from t_system where system_id=$val1 and display_type=$strType";
		$strRsLevel1Arr=$DB->Returns($strSQL);
		while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
		{			
			//print "<div style='font-weight:bold;'>".$strRsLevel1->system_name."</div>";
			
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
							$year_totalKwh___=0;
							foreach($level3Arr as $val3)
							{
									
								$strSQL="Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{
									$DataVal=0;
															
									if(is_array($level4Arr) && count($level4Arr)>0)
									{
										$year_totalKwh__=0;
										foreach($level4Arr as $val4)
										{
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{											
												$DataVal=0;																							
												
												$strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=".$strRsLevel4->system_id;
												$strRsSystemNodesArr=$DB->Returns($strSQL);
												
												$year_totalKwh_=0;
												while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
												{
													##########################################
													# Calculating Kwh
													##########################################
												
													$DataVal=0;
													if($strRsSystemNodes->available_system_node_serial<>'')
													{
														$year_totalKwh_+=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $year_start_date, $year_end_date);
                                                    }
													
													
												?>
                                               
                                                <?php
												}
												$year_totalKwh__+=$year_totalKwh_;
											?>
                                           
                                            <?php
											}
										}
										$year_totalKwh___+=$year_totalKwh__;
									}									
									?>
                                    	
                                    <?php								
								}								
							}
							$year_totalKwh____+=$year_totalKwh___;
						}
						
					}
					
				}
			}

			
		}
		
	}
}

$g_year = $year_totalKwh____;

if(is_array($level1Arr) && count($level1Arr)>0)
{
	$year_totalKwh____=0;
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1 and display_type=3";
		//$strSQL="Select * from t_system where system_id=$val1 and display_type=$strType";
		$strRsLevel1Arr=$DB->Returns($strSQL);
		while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
		{			
			//print "<div style='font-weight:bold;'>".$strRsLevel1->system_name."</div>";
			
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
							$year_totalKwh___=0;
							foreach($level3Arr as $val3)
							{
									
								$strSQL="Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{
									$DataVal=0;
															
									if(is_array($level4Arr) && count($level4Arr)>0)
									{
										$year_totalKwh__=0;
										foreach($level4Arr as $val4)
										{
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{											
												$DataVal=0;																							
												
												$strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=".$strRsLevel4->system_id;
												$strRsSystemNodesArr=$DB->Returns($strSQL);
												
												$year_totalKwh_=0;
												while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
												{
													##########################################
													# Calculating Kwh
													##########################################
												
													$DataVal=0;
													if($strRsSystemNodes->available_system_node_serial<>'')
													{
														$year_totalKwh_+=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $year_start_date, $year_end_date);
                                                    }
													
													
												?>
                                               
                                                <?php
												}
												$year_totalKwh__+=$year_totalKwh_;
											?>
                                           
                                            <?php
											}
										}
										$year_totalKwh___+=$year_totalKwh__;
									}									
									?>
                                    	
                                    <?php								
								}								
							}
							$year_totalKwh____+=$year_totalKwh___;
						}
						
					}
					
				}
			}

			
		}
		
	}
}

$w_year = $year_totalKwh____;

$strSQL="Select square_feet from t_building where building_id=$building_id";
$strRsBuildingAreaArr=$DB->Returns($strSQL);
if($strRsBuildingArea=mysql_fetch_object($strRsBuildingAreaArr))
{
	$square_feet=$strRsBuildingArea->square_feet;
}

$strSQL="SELECT sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.building_id = '$building_id' AND utility_account_type = 1 AND year = '$year'";
$strElectricAccountArr=$DB->Returns($strSQL);
while($strElectricAccount=mysql_fetch_object($strElectricAccountArr)) {
    $billing_electric_consumption = $strElectricAccount->consumption;
    $billing_electric_cost = $strElectricAccount->cost;
}

$strSQL="SELECT sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.building_id = '$building_id' AND utility_account_type = 2 AND year = '$year'";
$strGasAccountArr=$DB->Returns($strSQL);
while($strGAsAccount=mysql_fetch_object($strGasAccountArr)) {
    $billing_gas_consumption = $strGAsAccount->consumption;
    $billing_gas_cost = $strGAsAccount->cost;
}

$strSQL="SELECT sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.building_id = '$building_id' AND utility_account_type = 3 AND year = '$year'";
$strWaterAccountArr=$DB->Returns($strSQL);
while($strWaterAccount=mysql_fetch_object($strWaterAccountArr)) {
    $billing_water_consumption = $strWaterAccount->consumption;
    $billing_water_cost = $strWaterAccount->cost;
}

?>

<div style="float:left; width:150px;">ELECTRICITY USAGE</div>
<div class="gray_box_for_value" style="float:left; margin-left:10px; width:90px;"><?=number_format($e_year, 0);?> kWh</div>
<div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;"><?=number_format($billing_electric_consumption, 0);?> kWh</div>
<div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">$<?=number_format($billing_electric_cost, 0);?></div>
<div class="clear" style="margin-top:3px;"></div>

<div style="float:left; width:150px;">NATURAL GAS USAGE</div>
<div class="gray_box_for_value" style="float:left; margin-left:10px; width:90px;"><?=number_format($g_year/50, 0);?> therm</div>
<div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;"><?=number_format($billing_gas_consumption/50, 0);?> therm</div>
<div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">$<?=number_format($billing_gas_cost, 0);?></div>
<div class="clear" style="margin-top:3px;"></div>

<div style="float:left; width:150px;">WATER USAGE</div>
<div class="gray_box_for_value" style="float:left; margin-left:10px; width:90px;"><?=number_format($w_year, 0);?> GLN</div>
<div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;"><?=number_format($billing_water_consumption, 0);?> GLN</div>
<div class="gray_box_for_value" style="float:left; margin-left:15px; width:90px;">$<?=number_format($billing_water_cost, 0);?></div>
<div class="clear" style="margin-top:3px;"></div>

<div style="border-bottom:1px solid #DDDDDD; margin:10px 0px;" class="clear"></div>

<div style="float:left; width:150px;">HEATING DEGREE DAYS:</div>
<div class="" style="float:left; margin-left:10px; width:90px;">0</div>

<div class="clear" style="margin-top:3px;"></div>

<div style="float:left; width:150px;">COOLING DEGREE DAYS:</div>
<div class="" style="float:left; margin-left:10px; width:90px;">0</div>

<div class="clear" style="margin-top:3px;"></div>