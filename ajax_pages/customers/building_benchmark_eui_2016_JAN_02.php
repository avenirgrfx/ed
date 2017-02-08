<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
ob_start();
session_start();
$DB=new DB;

//$adminUsername = Globals::GetPortfolioUsername();
//$adminPassword = Globals::GetPortfolioPassword();
$customerUsername = '';
$customerPassword = '';

//echo $customerUsername." ".$customerPassword;exit;

$strSQL="Select time_zone, daylight_saving, portfolio_status, property_id, username, password from t_building left join t_portfolio_client on t_building.client_id = t_portfolio_client.client_id where building_id=".$_GET['building_id'];
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
    $portfolio_status = $strTime_zone->portfolio_status;
    $property_id = $strTime_zone->property_id;
    $customerUsername = $strTime_zone->username;
    $customerPassword = $strTime_zone->password;
}

if($portfolio_status == 1){ ?>
    <script>
        $('#portfolio_link').css("background-color","#FFFFFF");
        $('#portfolio_link a').css("cursor","pointer");
        $('#j_username').val("<?=$customerUsername?>");
        $('#j_password').val("<?=$customerPassword?>");
        //$('#portfolio_link a').attr("href","javascript:void(0);");
    </script>    
<?php 
    $building_score = '0';
    $district_score = '0';
    
    //$response = Globals::CallAPI("GET",'https://'.$customerUsername.':'.$customerPassword.'@portfoliomanager.energystar.gov/wstest/property/'.$property_id);
    //echo $response;exit;
    if(simplexml_load_string($response)){
        $response = new SimpleXMLElement($response);
        if($response['status'][0]=="Ok"){
            //$meter_id = $response->id[0];
            //echo $meter_id;
           // $DB = new DB;
           // $strSQL = "insert into t_portfolio_client values ('".$_POST['txtClientId']."', '".$_POST['primary_business']."', '".$_POST['energystar_partner']."', '".$_POST['partner_type']."', '".$_POST['question1']."', '".$_POST['question2']."', '".$_POST['txtAnswer1']."', '".$_POST['txtAnswer2']."', '".$_POST['txtPassword']."', '$customer_id');";
           // $DB->Returns($strSQL);
        }else{
            foreach($response->errors[0]->error as $error){
                //echo ($error['errorDescription']);
                //echo "</br>";
            };
        }
    }else{
        //echo "Error in request";
    }
    
}else{ ?>
    <script>
        $('#portfolio_link').css("background-color","#A9A9A9");
        $('#portfolio_link a').css("cursor","default");
        $('#j_username').val("");
        $('#j_password').val("");
        //$('#portfolio_link a').attr("href","");
    </script> 
<?php }
date_default_timezone_set($time_zone);

//TO GET BASELINE DATA------------------
$strSQL="SELECT * FROM `t_mv_baseline` WHERE `building_id`=".$_GET['building_id'];
$baseline_data=$DB->Returns($strSQL);
$bl_data_=0;
$bl_today=strtolower(date('D'));
$bl_yesterday=strtolower(date('D',strtotime('-1 day')));

$strSQL="Select * from t_energy_cost where building_id=".$_GET['building_id'];
$costArr=$DB->Returns($strSQL);
$cost=mysql_fetch_object($costArr);
$energy_cost = $cost->energy_cost?$cost->energy_cost:0;
$gas_cost = $cost->gas_cost?$cost->gas_cost:0;

if($bl_data=mysql_fetch_object($baseline_data))
{
   $e_bl_data_=$bl_data->e_mon+$bl_data->e_tue+$bl_data->e_wed+$bl_data->e_thu+$bl_data->e_fri+$bl_data->e_sat+$bl_data->e_sun;
   $g_bl_data_=$bl_data->g_mon+$bl_data->g_tue+$bl_data->g_wed+$bl_data->g_thu+$bl_data->g_fri+$bl_data->g_sat+$bl_data->g_sun;
   $e_bl_today=$bl_data->{e_.$bl_today};
   $g_bl_today=$bl_data->{g_.$bl_today};
   $e_bl_yesterday=$bl_data->{e_.$bl_yesterday};
   $g_bl_yesterday=$bl_data->{g_.$bl_yesterday};
   $baselineFrom = $bl_data->from_date;
   $baselineTo = $bl_data->to_date;
  
}
$e_month_bl_data=($e_bl_data_/7)*30;
$g_month_bl_data=($g_bl_data_/7)*30;
//BASELINE DATA ENDS-------------------------

$building_id= $_GET['building_id'];

$strSQL="Select square_feet from t_building where building_id=$building_id";
$strRsBuildingAreaArr=$DB->Returns($strSQL);
if($strRsBuildingArea=mysql_fetch_object($strRsBuildingAreaArr))
{
	$square_feet=$strRsBuildingArea->square_feet;
}

//TO GET DAYS DATA ----------------------------------------

$start_date = date("Y-m-d 00:00:00");
$end_date = date("Y-m-d h:i:s");
$last_start_date = date('Y-m-d 00:00:00', strtotime("yesterday"));
$last_end_date = date('Y-m-d 23:59:59', strtotime("yesterday"));
$month_start_date = date("Y-m-01 00:00:00");
$month_end_date = date("Y-m-d h:i:s");
$year_start_date = date("Y-01-01 00:00:00");
$year_end_date = date("Y-m-d h:i:s");

//echo date('Y-m-d H:i:s')." ".$start_date." ".$end_date." ".$last_start_date." ".$last_end_date."<br\n";


//*********************  Date & Time conversion to UTC  ****************************//

$start_date = gmdate('Y-m-d H:i:s', strtotime($start_date));
$end_date = gmdate('Y-m-d H:i:s', strtotime($end_date));
$last_start_date = gmdate('Y-m-d H:i:s', strtotime($last_start_date));
$last_end_date = gmdate('Y-m-d H:i:s', strtotime($last_end_date));
$month_start_date = gmdate('Y-m-d H:i:s', strtotime($month_start_date));
$month_end_date = gmdate('Y-m-d H:i:s', strtotime($month_end_date));
$year_start_date = gmdate('Y-m-d H:i:s', strtotime($year_start_date));
$year_end_date = gmdate('Y-m-d H:i:s', strtotime($year_end_date));

//echo date('Y-m-d H:i:s')." ".$start_date." ".$end_date." ".$last_start_date." ".$last_end_date."<br>\n";

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

if(is_array($level1Arr) && count($level1Arr)>0)
{
	$totalKwh____=0;
    $last_totalKwh____=0;
    $month_totalKwh____=0;
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
							$totalKwh___=0;
                            $last_totalKwh___=0;
                            $month_totalKwh___=0;
                            $year_totalKwh___=0;
							foreach($level3Arr as $val3)
							{
									
								$strSQL="Select * from t_system where system_id=$val3 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{
									$DataVal=0;
															
									if(is_array($level4Arr) && count($level4Arr)>0)
									{
										$totalKwh__=0;
                                        $last_totalKwh__= 0;
                                        $month_totalKwh__=0;
                                        $year_totalKwh__=0;
										foreach($level4Arr as $val4)
										{
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{											
												$DataVal=0;																							
												
												$strSQL="Select * from t_system_node where building_id=$building_id and system_id=".$strRsLevel4->system_id;
												$strRsSystemNodesArr=$DB->Returns($strSQL);
												
												$totalKwh_=0;
                                                $last_totalKwh_ = 0;
                                                $month_totalKwh_=0;
                                                $year_totalKwh_=0;
												while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
												{
													##########################################
													# Calculating Kwh
													##########################################
												
													$DataVal=0;
													if($strRsSystemNodes->available_system_node_serial<>'')
													{
														$DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date, $end_date);
														$last_DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $last_start_date, $last_end_date);
                                                        $month_totalKwh_+=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $month_start_date, $month_end_date);
                                                        $year_totalKwh_+=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $year_start_date, $year_end_date);
                                                        $totalKwh_=$totalKwh_+floatval($DataVal);
														$last_totalKwh_=$last_totalKwh_+floatval($last_DataVal);
													}
													
													
												?>
                                               
                                                <?php
												}
												$totalKwh__=$totalKwh__	+$totalKwh_;																		
												$last_totalKwh__=$last_totalKwh__	+$last_totalKwh_;																		
												$month_totalKwh__+=$month_totalKwh_;
                                                $year_totalKwh__+=$year_totalKwh_;
											?>
                                           
                                            <?php
											}
										}
										$totalKwh___=$totalKwh___+$totalKwh__;
										$last_totalKwh___=$last_totalKwh___+$last_totalKwh__;
                                        $month_totalKwh___+=$month_totalKwh__;
                                        $year_totalKwh___+=$year_totalKwh__;
									}									
									?>
                                    	
                                    <?php								
								}								
							}
							$totalKwh____=$totalKwh____+$totalKwh___;	
                            $last_totalKwh____=$last_totalKwh____+$last_totalKwh___;
                            $month_totalKwh____+=$month_totalKwh___;
						    $year_totalKwh____+=$year_totalKwh___;
						}
						
					}
					
				}
			}

			
		}
		
	}
}

$e_today = $totalKwh____;
$e_yesterday = $last_totalKwh____;
$e_month = $month_totalKwh____;
$e_year = $year_totalKwh____;

if(is_array($level1Arr) && count($level1Arr)>0)
{
	$totalKwh____=0;
    $last_totalKwh____=0;
    $month_totalKwh____=0;
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
							$totalKwh___=0;
                            $last_totalKwh___=0;
                            $month_totalKwh___=0;
                            $year_totalKwh___=0;
							foreach($level3Arr as $val3)
							{
									
								$strSQL="Select * from t_system where system_id=$val3 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{
									$DataVal=0;
															
									if(is_array($level4Arr) && count($level4Arr)>0)
									{
										$totalKwh__=0;
                                        $last_totalKwh__= 0;
                                        $month_totalKwh__=0;
                                        $year_totalKwh__=0;
										foreach($level4Arr as $val4)
										{
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{											
												$DataVal=0;																							
												
												$strSQL="Select * from t_system_node where building_id=$building_id and system_id=".$strRsLevel4->system_id;
												$strRsSystemNodesArr=$DB->Returns($strSQL);
												
												$totalKwh_=0;
                                                $last_totalKwh_ = 0;
                                                $month_totalKwh_=0;
                                                $year_totalKwh_=0;
												while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
												{
													##########################################
													# Calculating Kwh
													##########################################
												
													$DataVal=0;
													if($strRsSystemNodes->available_system_node_serial<>'')
													{
														$DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date, $end_date);
														$last_DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $last_start_date, $last_end_date);
                                                        $month_totalKwh_+=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $month_start_date, $month_end_date);
                                                        $year_totalKwh_+=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $year_start_date, $year_end_date);
                                                        $totalKwh_=$totalKwh_+floatval($DataVal);
														$last_totalKwh_=$last_totalKwh_+floatval($last_DataVal);
													}
													
													
												?>
                                               
                                                <?php
												}
												$totalKwh__=$totalKwh__	+$totalKwh_;																		
												$last_totalKwh__=$last_totalKwh__	+$last_totalKwh_;																		
												$month_totalKwh__+=$month_totalKwh_;
                                                $year_totalKwh__+=$year_totalKwh_;
											?>
                                           
                                            <?php
											}
										}
										$totalKwh___=$totalKwh___+$totalKwh__;
										$last_totalKwh___=$last_totalKwh___+$last_totalKwh__;
                                        $month_totalKwh___+=$month_totalKwh__;
                                        $year_totalKwh___+=$year_totalKwh__;
									}									
									?>
                                    	
                                    <?php								
								}								
							}
							$totalKwh____=$totalKwh____+$totalKwh___;	
                            $last_totalKwh____=$last_totalKwh____+$last_totalKwh___;
                            $last_totalKwh____=  round($last_totalKwh____,3);
							$month_totalKwh____+=$month_totalKwh___;
						    $year_totalKwh____+=$year_totalKwh___;
						}
						
					}
					
				}
			}

			
		}
		
	}
}

$g_today = $totalKwh____;
$g_yesterday = $last_totalKwh____;
$g_month = $month_totalKwh____;
$g_year = $year_totalKwh____;

$strSQL="Select * from t_building_benchmark where building_id=$building_id";
$strRsBuildingBenchmarkArr=$DB->Returns($strSQL);
if($strRsBuildingBenchmark=mysql_fetch_object($strRsBuildingBenchmarkArr))
{
//	$building_EUI_value=$strRsBuildingBenchmark->building_EUI_value;
//	$building_PEER_value=$strRsBuildingBenchmark->building_PEER_value;
//	$building_Benchmark_value=$strRsBuildingBenchmark->building_Benchmark_value;
//	$building_Benchmark_target=$strRsBuildingBenchmark->building_Benchmark_target;
	$building_EUI_value=round(($e_year+$g_year)/($square_feet*0.293071),0);
	$building_PEER_value=$strRsBuildingBenchmark->building_PEER_value;
	$building_Benchmark_value=$strRsBuildingBenchmark->building_Benchmark_value;
	$building_Benchmark_target=$strRsBuildingBenchmark->building_Benchmark_target;
    $building_EUI_target=$strRsBuildingBenchmark->building_EUI_value;
}
else
{
	$building_EUI_value=0;
	$building_PEER_value=0;
	$building_Benchmark_value=0;
	$building_Benchmark_target=0;
}

$TodaySiteEUI=round($building_Benchmark_target/date("j"),0);
if($TodaySiteEUI*date("j")>$TodaySiteEUI)
	$TodaySiteEUI--;

$YesterdaySiteEUI=$TodaySiteEUI+1.5;

# Calculating Margins for showing benchmark
$FullBuildingEUIWidth=390;

if($building_PEER_value<=100)
{
	$building_PEER_margin=$building_PEER_value*$FullBuildingEUIWidth/100;
	$building_PEER_margin=$building_PEER_margin-15;
}

$building_EUI_margin=$building_EUI_value*$FullBuildingEUIWidth/100;
$building_EUI_margin=$building_EUI_margin-60;

$BuilingEUI_Text_Margin=0;
if($building_EUI_margin>288 and $building_EUI_margin<=319)
{
	$BuilingEUI_Text_Margin=288-$building_EUI_margin;
}
if($building_EUI_margin>319)
{
	$building_EUI_margin=319;
	$BuilingEUI_Text_Margin=-37;
}
# END - Calculating Margins for showing benchmark

?>
<script type="text/javascript">
$(function(){
	$('#source_site.switch_button input').switchButton({on_label:'SOURCE EUI', off_label:'SITE EUI'});
});

$(document).ready(function(){
	$('#chkSite_Source').change(function()
	{
		//alert($('#chkSite_Source').is(':checked')); 		
	});
});

</script>
<div id="Building_Square_Feet_For_Calculation" style="display:none;"><?php echo $square_feet;?></div>  
    <div style="float:left; width:72%;">
    	<div style="color:#666666; font-weight:bold; font-size:16px;">BUILDING SITE EUI</div>

       <div style="position:relative; height:50px;">
       		
       		<div title="<?php echo $building_EUI_value;?> kBtu" style="position:absolute; z-index:2; width:120px; margin-left:<?php echo $building_EUI_margin?>px; margin-top:-40px; text-align:center; background-image:url(../images/building_benchmark_dark_arrow.png); background-repeat:no-repeat; height:62px; background-position:bottom;">
                <div style="color:#000000; font-weight:bold; font-size:16px; text-decoration:underline; text-align:left; margin-left:<?php echo $BuilingEUI_Text_Margin;?>px;">BUILDING</div>
                <div style="text-decoration:none; font-size:12px; margin-top:-3px; text-align:left; margin-left:<?php echo $BuilingEUI_Text_Margin;?>px;"><?=date('Y')?> EUI <strong><?php echo $building_EUI_value;?></strong> KBtu/ft<sup>2</sup></div>
            </div>
            
            <div title="<?php echo $building_PEER_value?> kBtu" style="position:absolute; z-index:1; width:30px; margin-left:<?php echo $building_PEER_margin?>px; margin-top:60px; text-align:center; background-image:url(../images/building_benchmark_light_arrow.png); background-repeat:no-repeat; height:80px; background-position:bottom; transform: rotate(180deg); -webkit-transform: rotate(180deg); -moz-transform: rotate(180deg); -ms-transform: rotate(180deg); -o-transform: rotate(180deg); ">
                <div style="color:#999999; font-size:14px; margin-top:38px; transform: rotate(180deg); -webkit-transform: rotate(180deg); -moz-transform: rotate(180deg); -ms-transform: rotate(180deg); -o-transform: rotate(180deg);">PEER</div>                                    
            </div>
            
            <div style="margin-top:60px;">&nbsp;</div>
            	<!--Full width = 390px;-->
                <div style="background-color:#CCCCCC; position:absolute; height:40px; width:100%;"></div>
                <div style="background-color:#2284c4; position:absolute; height:37px; width:42%; font-size:10px; font-weight:bold; text-align:center; padding-top:3px;" title="LOWEST USE">LOWEST USE<br />(42 or below)</div>
                <div style="background-color:#ffcd31; position:absolute; height:37px; width:18%; margin-left:42%; font-size:10px; font-weight:bold; text-align:center; padding-top:3px;" title="MEDIUM LOW">MEDIUM LOW<br />(43 - 60)</div>
                <div style="background-color:#f8981d; position:absolute; height:37px; width:20%; margin-left:60%; font-size:10px; font-weight:bold; text-align:center; padding-top:3px;" title="MEDIUM HIGH">MEDIUM HIGH<br />(61 - 80)</div>
                <div style="background-color:#ef3823; position:absolute; height:37px; width:20%; margin-left:80%; font-size:10px; font-weight:bold; text-align:center; padding-top:3px;" title="HIGHEST USE">HIGHEST USE<br />(81 and above)</div>              
                <div style="position:absolute; margin-top:40px; margin-left:1%; z-index:2;">0</div>
                <div style="position:absolute; margin-top:40px; margin-left:22.5%; z-index:2;">25</div>
                <div style="position:absolute; margin-top:40px; margin-left:42.5%; z-index:2;">50</div>
                <div style="position:absolute; margin-top:40px; margin-left:72.5%; z-index:2;">75</div>
                <div style="position:absolute; margin-top:40px; margin-left:<?php if($building_EUI_value<=100){?>95<?php }else{?>90<?php }?>%; z-index:2;">100</div>               
        </div>
        <div class="clear"></div>
       
    
    </div>
    
    
     
    
    
    <div style="float:left;">
    	
       
         <div class="switch_button" id="source_site" style="margin-left:5px;">
            <input type="checkbox" value="1" id="chkSite_Source" name="chkSite_Source" />
        </div>
        
        <div class="clear"></div>
        <?php if($portfolio_status == 1){ ?>
            <div style="float:left;" class="portfoli_manager_score_container" >       
                <div style="margin-top:36px;text-align:center;">Your building score</div>
                <div style="text-align: center; margin-top: 4px; font-size: 20px; color: #FFFFFF;"><?=$building_score;?></div>

                <div style="margin-top:11px; text-align:center;">Avg. Score for District</div>
                <div style="text-align: center; margin-top: 4px; font-size: 20px; color: #FFFFFF;"><?=$district_score;?></div>
            </div>
        <?php }else{ ?>
            <div style="float:left;" class="portfoli_manager_score_container" >       
                <div style="margin-top:36px;text-align:center;">Your building score</div>
                <div style="text-align: center; margin-top: 4px; font-size: 20px; color: #FFFFFF;">NA</div>

                <div style="margin-top:11px; text-align:center;">Avg. Score for District</div>
                <div style="text-align: center; margin-top: 4px; font-size: 20px; color: #FFFFFF;">NA</div>
            </div> 
        <?php } ?>
        <div class="clear"></div>
    </div>
    
    <div class="clear"></div>
    
     <!--<div style="border-radius:5px; margin-top:5px; margin-bottom:5px; border:1px solid #CCCCCC; padding:3px; position:relative;">
            <div style="color:#666666; font-weight:bold; font-size:16px;">BUILDING SITE EUI TARGET</div>
            
            <div style="position:relative; height:50px;">
                <div style="background-color:#CCCCCC; position:absolute; height:20px; border-radius:5px; width:100%;"></div>
                <div style="background-color:#2284c4; position:absolute; height:20px; border-radius:5px; width:<?php echo $building_Benchmark_value;?>%;" title="<?php echo $building_Benchmark_value;?>%"></div>
                <div style="position:absolute; margin-top:20px; margin-left:1%;">0</div>
                <div style="position:absolute; margin-top:20px; margin-left:20%;">25</div>
                <div style="position:absolute; margin-top:20px; margin-left:45%;">50</div>
                <div style="position:absolute; margin-top:20px; margin-left:70%;">75</div>
                <div style="position:absolute; margin-top:20px; margin-left:95%;">100</div>
                
                <div style="position:absolute; transform: rotate(180deg); -webkit-transform: rotate(180deg); -moz-transform: rotate(180deg); -ms-transform: rotate(180deg); -o-transform: rotate(180deg);  height:20px; width:20px; margin-top:20px; margin-left:<?php echo $building_Benchmark_target-8?>%;">
                    <img src="<?php echo URL?>images/building_benchmark_dark_arrow.png" width="20px;" title="<?php echo $building_Benchmark_target;?>%" alt="<?php echo $building_Benchmark_target;?>%" />
                </div>
                
                
                
            </div>
     </div>-->
    
    
    
    <div>
        <div style="float:left; border:1px solid #CCCCCC; text-align:center;">
            
            <div style="background-color:#DDDDDD; font-size:14px; padding:3px 7px; width:120px;" id="Today_Site_EUI_ECI">Today Energy Use</div>
            <div class="<?=($e_today+$g_today)>($e_bl_today+$g_bl_today)?'red_font':'green_font'?>" style="font-size:16px; padding:6px 0px;" id="Today_Site_EUI_ECI_Value">
            	<div class="EUI_Val">
                    <b><strong><?php echo number_format(($e_today+$g_today)/0.293071,0);?></strong> kBtu</b>
                </div>
                
                <div class="ECI_Val" style="display:none;">
                	<b><strong>$<?php echo number_format(($e_today*$energy_cost+$g_today*$gas_cost/50),0);?></strong></b>
                </div>
            </div>
        
        </div>
        
        <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
             <div style="background-color:#DDDDDD; font-size:13px; padding:4px 5px;; width:124px;" id="Yesterday_Site_EUI_ECI">Yesterday Energy Use</div>
            <div class="<?=($e_yesterday+$g_yesterday)>($e_bl_yesterday+$g_bl_yesterday)?'red_font':'green_font'?>" style="font-size:16px; padding:6px 0px;" id="Yesterday_Site_EUI_ECI_Value">
            	<div class="EUI_Val">
                    <b><strong><?php echo number_format(($e_yesterday+$g_yesterday)/0.293071,0)?></strong> kBtu</b>
                </div>
                <div class="ECI_Val" style="display:none;">
                    <b><strong>$<?php echo number_format(($e_yesterday*$energy_cost+$g_yesterday*$gas_cost/50),0)?></strong></b>
                </div>
                
            </div>
        
        </div>
        
        <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
             <div style="background-color:#DDDDDD; font-size:14px; padding:3px 7px; width:110px;" id="Month_Site_EUI_ECI">Month Energy Use</div>
            <div class="<?=($e_month+$g_month)>($e_month_bl_data+$g_month_bl_data)?'red_font':'green_font'?>" style="font-size:16px; padding:6px 0px;" id="Month_Site_EUI_ECI_Value">
            	<div class="EUI_Val">
                    <b><strong><?php echo number_format(($e_month+$g_month)/0.293071,0);?></strong> kBtu</b>
                </div>
                
                <div class="ECI_Val" style="display:none;" id="Month_Site_EUI_ECI_Value_Amount">
                	<b><strong>$<?php echo number_format(($e_month*$energy_cost+$g_month*$gas_cost/50),0);?></strong></b>
                </div>                
                
            </div>
        
        </div>
        
         <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
             <div style="background-color:#DDDDDD; font-size:14px; padding:3px 7px; width:100px;" id="Target_Site_EUI_ECI"><?=date('Y')?> EUI</div>
            <div  style="font-size:16px; padding:6px 2px;" id="Target_Site_EUI_ECI_Value">
            	<div class="EUI_Val">
                    <b><strong><?php echo number_format(($e_year+$g_year)/($square_feet*0.293071),0)?></strong>  kBtu/ft<sup>2</sup></b>
                </div>
                
                <div class="ECI_Val" style="display:none;">
                	<b><strong>$<?php echo number_format(($e_year*$energy_cost+$g_year*$gas_cost/50)/$square_feet,2)?></strong>/ft<sup>2</sup></b>
                </div>
                
            </div>
        
        </div>
        
        <div class="clear"></div>
        
    </div>
   
    <div style="margin-top:5px;">
        <div style="float:left; border:1px solid #CCCCCC; text-align:center;">
            
            <div style="background-color:#DDDDDD; font-size:11px; padding:3px 7px; width:120px;" id="Today_Site_EUI_ECI_B">Day Baseline Energy</div>
            <div style="font-size:16px; padding:6px 0px;" id="Today_Site_EUI_ECI_Value">
            	<div class="EUI_Val">
            		<?php echo number_format(($e_bl_today+$g_bl_today)/0.293071,0);?> kBtu
                </div>
                
                <div class="ECI_Val" style="display:none;">
                	$<?php echo number_format(($e_bl_today*$energy_cost+$g_bl_today*$gas_cost/50),0);?>
                </div>
            </div>
        
        </div>
        
        <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
             <div style="background-color:#DDDDDD; font-size:11px; padding:3px 0px; width:134px;" id="Yesterday_Site_EUI_ECI_B">Yesterday Baseline Energy</div>
            <div  style="font-size:16px; padding:6px 0px;" id="Yesterday_Site_EUI_ECI_Value">
            	<div class="EUI_Val">
            		<?php echo number_format(($e_bl_yesterday+$g_bl_yesterday)/0.293071,0);?> kBtu
                </div>
                <div class="ECI_Val" style="display:none;">
            		$<?php echo number_format(($e_bl_yesterday*$energy_cost+$g_bl_yesterday*$gas_cost/50),0);?>
                </div>
                
            </div>
        
        </div>
        
        <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
             <div style="background-color:#DDDDDD; font-size:11px; padding:3px 7px; width:110px;" id="Month_Site_EUI_ECI_B">Month Baseline Energy</div>
            <div style="font-size:16px; padding:6px 0px;" id="Month_Site_EUI_ECI_Value">
            	<div class="EUI_Val">
                	<?php echo number_format(($e_month_bl_data+$g_month_bl_data)/0.293071,0);?> kBtu
                </div>
                
                <div class="ECI_Val" style="display:none;" id="Month_Site_EUI_ECI_Value_Amount">
                	$<?php echo number_format(($e_month_bl_data*$energy_cost+$g_month_bl_data*$gas_cost/50),0);?>
                </div>                
                
            </div>
        
        </div>
        
         <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
             <div style="background-color:#DDDDDD; font-size:11px; padding:3px 7px; width:100px;" id="Target_Site_EUI_ECI_B">Target EUI</div>
            <div  style="font-size:16px; padding:6px 2px;" id="Target_Site_EUI_ECI_Value">
            	<div class="EUI_Val">
            		<?php echo $building_EUI_target;  ?> kBtu/ft<sup>2</sup><!-- EUI -->
                </div>
                
                <div class="ECI_Val" style="display:none;">
                	$<?php echo number_format($building_EUI_target*$energy_cost,2)?>/ft<sup>2</sup>
                </div>
                
            </div>
        
        </div>
        
        <div class="clear"></div>
        
    </div>
    <span style="font-size:14px">Active Baseline period</span> <input type="text" style="width: 135px; height: 11px;margin-top: 1px;margin-left:3px;font-size: 13px" value="<?=$baselineFrom?>-<?=$baselineTo?>" disabled>