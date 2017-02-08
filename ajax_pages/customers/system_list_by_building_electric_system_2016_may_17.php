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

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$building_id;
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($time_zone);

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
   
//*********************  Date & Time conversion to UTC  ****************************//

$start_date = gmdate('Y-m-d H:i:s', strtotime($start_date));
$end_date = gmdate('Y-m-d H:i:s', strtotime($end_date));
$last_start_date = gmdate('Y-m-d H:i:s', strtotime($last_start_date));
$last_end_date = gmdate('Y-m-d H:i:s', strtotime($last_end_date));


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
	$strSQL="Select parent_id, system_name, system_id, level from t_system where system_id=$strChild";
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

//print_r($level1Arr);
//print_r($level2Arr);
//print_r($level3Arr);
//print_r($level4Arr);
//exit;

if($strType==1)
{
	$valuBoxStyle='light_blue_box_for_value';
	$ValueUnit='kWh';
}
else
{
	$valuBoxStyle='gray_box_for_value';
	$ValueUnit='Therms';
}

if(is_array($level1Arr) && count($level1Arr)>0)
{
	$totalKwh____=0;
    $last_totalKwh____=0;
    $MAXtotalKwh_=0;
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1 and display_type=$strType";
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
                        $strSQL="select available_system_node_serial from t_system_node where system_id in (select system_id from t_system where parent_id in (Select system_id from t_system where parent_id=$strRsLevel2->system_id and exclude_in_calculation=1))";
					    $strRsLevel3Arr=$DB->Returns($strSQL);
					    while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
                        {
                           
                         $DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsLevel3->available_system_node_serial, "kwhsystem", $start_date, $end_date);
                         //$last_DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsLevel3->available_system_node_serial, "kwhsystem", $last_start_date, $last_end_date);
                        
			             //$last_totalKwh_=$last_totalKwh_+floatval($last_DataVal);
                         $MAXtotalKwh_+=floatval($DataVal);;
                         }
                                        
                         
                                       
						print "<div><div style='margin-left:0px; cursor:pointer; color:#0088cc; font-weight:bold; float:left; width:350px; ' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel2->system_id.")'><span class='System_ID_Expand_".$strRsLevel2->system_id."'>+</span>".$strRsLevel2->system_name."</div>
						<div class='$valuBoxStyle' style='float:left; font-weight:normal; min-width: 80px;' id='totalKwh___".$strRsLevel2->system_id."'>0 $ValueUnit</div>
						<div style='float:right; margin-right:20px;' id='totalKwhPercent___".$strRsLevel2->system_id."'>33%</div>
						<div class='clear' style='margin-bottom:1px;'></div>
						";
						if(is_array($level3Arr) && count($level3Arr)>0)
						{
							$totalKwh___=0;
                            $last_totalKwh___=0;
							foreach($level3Arr as $val3)
							{
									
								$strSQL="Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{
									$DataVal=0;
															
									print "<div style='margin-left:10px; display:none; cursor:pointer; color:#0088cc;' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel3->system_id.")' class='System_ID_".$strRsLevel2->system_id."'>
									
									<div style='width:340px; float:left;'> <span class='System_ID_Expand_".$strRsLevel3->system_id."'>+</span> ".$strRsLevel3->system_name."</div> <div style='float:left;' id='totalKwh__".$strRsLevel3->system_id."'>$DataVal $ValueUnit</div><div class='clear' style='margin-bottom:1px;'></div>
									</div>
									
									";									
									if(is_array($level4Arr) && count($level4Arr)>0)
									{
										$totalKwh__=0;
                                        $last_totalKwh__= 0;
										foreach($level4Arr as $val4)
										{
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{											
												$DataVal=0;																							
												print "<div style='margin-left:20px; display:none; font-style:italic;' class='System_ID_".$strRsLevel3->system_id." System_ID_Sub_".$strRsLevel2->system_id."'>
												<div style='float:left; width:330px;'><span>".$strRsLevel4->system_name."</span></div>
												<div style='float:left;' id='totalKwh_".$strRsLevel4->system_id."'>$DataVal $ValueUnit</div><div class='clear' style='margin-bottom:1px;'></div>
												";
												
												$strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=".$strRsLevel4->system_id;
												$strRsSystemNodesArr=$DB->Returns($strSQL);
												
												$iCtr=0;
												$totalKwh_=0;
                                                $last_totalKwh_ = 0;
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
                                                        if($strType==2){
                                                            $DataVal = $DataVal/50;
                                                            $last_DataVal = $last_DataVal/50;
                                                        }
                                                        $totalKwh_=$totalKwh_+floatval($DataVal);
														$last_totalKwh_=$last_totalKwh_+floatval($last_DataVal);
													}
													
													
													$iCtr++;
													if($iCtr % 2==0)
														$bgColor='#FFFFFF';
													else
														$bgColor='#EFEFEF';
													print "<div style='margin-left:10px;font-style:normal; color:#0088cc; text-decoration:underline; background-color:".$bgColor."'><div style=' width:320px; float:left; text-decoration:underline;'>".($strRsSystemNodes->custom_name=='' ? $strRsSystemNodes->node_serial : $strRsSystemNodes->custom_name." (".$strRsSystemNodes->node_serial.")")."</div>
													
													<div style='float:left; text-decoration:underline;'>".number_format($DataVal,2)." $ValueUnit</div><div class='clear' style='margin-bottom:1px;'></div>
													
													</div>
													
													";
												?>
                                                <script type="text/javascript">$('#totalKwh_<?php echo $strRsLevel4->system_id;?>').html('<?php echo number_format($totalKwh_,2);?> <?php echo $ValueUnit;?>')</script> 
                                                <?php
												}
												$totalKwh__=$totalKwh__	+$totalKwh_;																		
												$last_totalKwh__=$last_totalKwh__	+$last_totalKwh_;																		
												print "</div>";
											?>
                                            <script type="text/javascript">$('#totalKwh__<?php echo $strRsLevel3->system_id;?>').html('<?php echo number_format($totalKwh__,0);?> <?php echo $ValueUnit;?>')</script>
                                            <?php
											}
										}
										$totalKwh___=$totalKwh___+$totalKwh__;
										$last_totalKwh___=$last_totalKwh___+$last_totalKwh__;
									}									
									?>
                                    	<script type="text/javascript">$('#totalKwh___<?php echo $strRsLevel2->system_id;?>').html('<?php echo number_format($totalKwh___,0);?> <?php echo $ValueUnit;?>')</script>
                                    <?php								
								}								
							}
							$totalKwh____=$totalKwh____+$totalKwh___;	
                            $last_totalKwh____=$last_totalKwh____+$last_totalKwh___;
							$Percentage_Calculation_IDArr[]=$strRsLevel2->system_id;
							
						}
                        print "</div>";
						
						
						
					}
					
				}
			}

			
		}
		
	}
}

if($strType==1)
{
   
    $ActualTotalKwh = $MAXtotalKwh_;
    $actualPercent = ($totalKwh____/$ActualTotalKwh)*100;
?>
    <script type="text/javascript">
        Month_To_Date_Electric_Consumption = '<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>';
        Last_Month_Electric_Consumption = '<?php echo number_format($last_totalKwh____,0);?> <?php echo $ValueUnit;?>';
        
        $('#Total_Electric_Gas_Value').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');
        $('#Month_To_Date_Electric_Consumption').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');	
        //$('#Month_To_Date_NaturalGas_Consumption').html('<?php echo number_format($totalKwh____/50,0);?> <?php echo 'Therms';?>');	
        $('#Last_Month_Electric_Consumption').html('<?php echo number_format($last_totalKwh____,0);?> <?php echo $ValueUnit;?>');
        //$('#Last_Month_NaturalGas_Consumption').html('<?php echo number_format($last_totalKwh____/50,0);?> <?php echo 'Therms';?>');
        $('#electric_energy_consumption_now').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');	
        //$('#natural_gas_energy_consumption_now').html('<?php echo number_format($totalKwh____/50,0);?> <?php echo 'Therms';?>');	
        $('#Main_Utility_Electric_Gas_Value').html('<?php echo number_format($MAXtotalKwh_,0);?> <?php echo $ValueUnit;?>');
        $('#actualPercent').html('<?php echo number_format($actualPercent,0)."%"; ?>');
    </script>
<?php
}
else
{
    
    $ActualTotalKwh = $MAXtotalKwh_/50;
    //$actualPercent = (1-$ActualTotalKwh/$totalKwh____)*100;
    $actualPercent = ($totalKwh____/$ActualTotalKwh)*100;
?>
    <script type="text/javascript">
        Month_To_Date_NaturalGas_Consumption = '<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>';
        Last_Month_NaturalGas_Consumption = '<?php echo number_format($last_totalKwh____,0);?> <?php echo $ValueUnit;?>';
        
        $('#Total_Electric_Gas_Value').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');
        //$('#Month_To_Date_Electric_Consumption').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');	
        $('#Month_To_Date_NaturalGas_Consumption').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');	
        //$('#Last_Month_Electric_Consumption').html('<?php echo number_format($last_totalKwh____,0);?> <?php echo $ValueUnit;?>');
        $('#Last_Month_NaturalGas_Consumption').html('<?php echo number_format($last_totalKwh____,0);?> <?php echo $ValueUnit;?>');
        $('#natural_gas_energy_consumption_now').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');	
        $('#Main_Utility_Electric_Gas_Value').html('<?php echo number_format($ActualTotalKwh,0);?> <?php echo $ValueUnit;?>');
        $('#actualPercent').html('<?php echo number_format($actualPercent,0)."%"; ?>');
    </script>
<?php
}
?>
<script type="text/javascript">
<?php
	if(is_array($Percentage_Calculation_IDArr) and count($Percentage_Calculation_IDArr)>0)
	{
		?>
		var TotalPercent=100;
        var finalArray = []
		<?php
		foreach($Percentage_Calculation_IDArr as $Percentage_Calculation_ID)
		{?>
			var TotalValByID=($('#totalKwh___<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
			TotalValByID=parseFloat(TotalValByID.replace("<?php echo $ValueUnit;?>",""));
            if(TotalValByID != 0){
                TotalValByID=Math.round((TotalValByID/<?php echo $totalKwh____?>)*100);
            }
			TotalPercent=TotalPercent-TotalValByID;
			//console.log(TotalValByID);
			if(TotalPercent<0)
			{
				TotalValByID=TotalValByID+TotalPercent;
			}
			$('#totalKwhPercent___<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID+'%');
            finalArray.push({
                htmlContent: $('#totalKwh___<?php echo $Percentage_Calculation_ID;?>').parent(),
                percentValue: TotalValByID
            });
		<?php			
		}?>
            function dynamicSort(property) {
                var sortOrder = 1;
                if(property[0] === "-") {
                    sortOrder = -1;
                    property = property.substr(1);
                }
                return function (a,b) {
                    var result = (a[property] > b[property]) ? -1 : (a[property] < b[property]) ? 1 : 0;
                    return result * sortOrder;
                }
            }
            finalArray.sort(dynamicSort("percentValue"));
            var i=1;
            for(var ele in finalArray){
                $(finalArray[ele].htmlContent).attr('data-position',i++);
            }
            $( '#Consumption_Electric_System > script' ).each(function() {
                $(this).attr('data-position',i++);
            });
            $("#Consumption_Electric_System").children().sort(sort_li) // sort elements
                  .appendTo('#Consumption_Electric_System'); // append again to the list
            // sort function callback
            function sort_li(a, b){
                return ($(b).data('position')) < ($(a).data('position')) ? 1 : -1;    
            }
        <?php    
	}
?>
</script>