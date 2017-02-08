<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
$DB=new DB;

$building_id=$_GET['building_id'];
$parent_id=$_GET['parent_id'];
$strType=$_GET['type'];

$first_date = $_GET['first_date']?$_GET['first_date']:"";
$second_date = $_GET['second_date']?$_GET['second_date']:"";

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$building_id;
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($time_zone);

if($first_date != "" && $second_date != ""){
    $start_date1 = date('Y-m-1 00:00:00', strtotime($first_date));
    $end_date1 = date('Y-m-d 23:59:59', strtotime($first_date));
    $start_date2 = date('Y-m-1 00:00:00', strtotime($second_date));
    $end_date2 = date('Y-m-d 23:59:59', strtotime($second_date));
}else{
    $start_date1 = date("Y-m-01 00:00:00");
    $end_date1 = date("Y-m-d 23:59:59");
    $start_date2 = date("Y-m-01 00:00:00");
    $end_date2 = date("Y-m-d 23:59:59");
}
?>
<script>
$('#no_of_days1').html("(<?=date('d', strtotime($end_date1));?> days)");
$('#no_of_days2').html("(<?=date('d', strtotime($end_date2));?> days)");
</script>   
<?php


//*********************  Date & Time conversion to UTC  ****************************//

$start_date1 = gmdate('Y-m-d H:i:s', strtotime($start_date1));
$end_date1 = gmdate('Y-m-d H:i:s', strtotime($end_date1));
$start_date2 = gmdate('Y-m-d H:i:s', strtotime($start_date2));
$end_date2 = gmdate('Y-m-d H:i:s', strtotime($end_date2));

//print_r("$start_date1 $end_date1 $start_date2 $end_date2");exit; 

global $level1Arr;
global $level2Arr;
global $level3Arr;
global $level4Arr;

function getParent($strChild, $strType)
{	
	global $level1Arr;
	global $level2Arr;
	global $level3Arr;
	global $level4Arr;
	
	$DB=new DB;
	$strSQL="Select parent_id, system_name, system_id, level from t_system where system_id=$strChild and display_type=$strType ";
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
		
		getParent($strRsGetParentID->parent_id, $strType);
	}
}

$strSQL="select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsSystemsArr=$DB->Returns($strSQL);
while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
{
	getParent($strRsSystems->system_id, $strType);
}

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
	$totalKwh1____=0;
    $totalKwh2____=0;
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1 and display_type=$strType";
		//$strSQL="Select * from t_system where system_id=$val1 and display_type=1";
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
						print "<div style='margin-left:0px; cursor:pointer; color:#0088cc; font-weight:bold; float:left; width:175px; overflow:hidden; white-space:nowrap;' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel2->system_id.")'><span class='System_ID_Expand_".$strRsLevel2->system_id."'>+</span>".$strRsLevel2->system_name."</div>
						<div class='$valuBoxStyle' style='float:left; font-weight:normal; min-width: 80px;' id='totalKwh1___".$strRsLevel2->system_id."'>0 $ValueUnit</div>
						<div style='float:left; margin-left:5px; width:40px' id='totalKwhPercent1___".$strRsLevel2->system_id."'>22%</div>
						<div class='$valuBoxStyle' style='float:left; font-weight:normal; min-width: 80px;' id='totalKwh2___".$strRsLevel2->system_id."'>0 $ValueUnit</div>
						<div style='float:left; margin-left:5px;' id='totalKwhPercent2___".$strRsLevel2->system_id."'>22%</div>
						<div class='clear' style='margin-bottom:1px;'></div>
						";
						
						if(is_array($level3Arr) && count($level3Arr)>0)
						{
							$totalKwh1___=0;
                            $totalKwh2___=0;
                            //$Percentage_Calculation_IDArr___ = array();
							foreach($level3Arr as $val3)
							{
									
								$strSQL="Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=".$strRsLevel2->system_id;
								$strRsLevel3Arr=$DB->Returns($strSQL);
								while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
								{
									$DataVal1=0;
									$DataVal2=0;
                                    print "<div style='margin-left:10px; display:none; cursor:pointer; color:#0088cc;' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel3->system_id.")' class='System_ID_".$strRsLevel2->system_id."'>
									
									<div style='width:170px; float:left; overflow:hidden; white-space:nowrap;' title='".$strRsLevel3->system_name."'> <span class='System_ID_Expand_".$strRsLevel3->system_id."'>+</span> ".$strRsLevel3->system_name."</div> 
									<div style='float:left; min-width:90px' id='totalKwh1__".$strRsLevel3->system_id."'>$DataVal1 $ValueUnit</div>
									<div style='float:left; margin-left:5px; width:40px;' id='totalKwhPercent1__".$strRsLevel3->system_id."'>23%</div>
									<div style='float:left; min-width:90px;' id='totalKwh2__".$strRsLevel3->system_id."'>$DataVal2 $ValueUnit</div>
									<div style='float:left; margin-left:5px;' id='totalKwhPercent2__".$strRsLevel3->system_id."'>23%</div>
									<div class='clear' style='margin-bottom:1px;'></div>
									</div>
									
									";									
									if(is_array($level4Arr) && count($level4Arr)>0)
									{
                                        $totalKwh1__=0;
                                        $totalKwh2__= 0;
                                        //$Percentage_Calculation_IDArr__ = array();
										foreach($level4Arr as $val4)
										{
											$strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
											$strRsLevel4Arr=$DB->Returns($strSQL);
                                            //$Percentage_Calculation_IDArr_ = array();
											while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
											{											
												$DataVal1=0;	
                                                $DataVal2=0;
												print "<div style='margin-left:20px; display:none; font-style:italic;' class='System_ID_".$strRsLevel3->system_id." System_ID_Sub_".$strRsLevel2->system_id."'>
												<div style='float:left; width:160px; overflow:hidden; white-space:nowrap;' title='".$strRsLevel4->system_name."'><span>".$strRsLevel4->system_name."</span></div>
												<div style='float:left; min-width:90px' id='totalKwh1_".$strRsLevel4->system_id."'>$DataVal1 $ValueUnit</div>
												<div style='float:left; margin-left:5px; width:40px' id='totalKwhPercent1_".$strRsLevel4->system_id."'>24%</div>
												<div style='float:left; min-width:90px' id='totalKwh2_".$strRsLevel4->system_id."'>$DataVal2 $ValueUnit</div>
												<div style='float:left; margin-left:5px;' id='totalKwhPercent2_".$strRsLevel4->system_id."'>24%</div>
												<div class='clear' style='margin-bottom:1px;'></div>
												";
												
												$strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=".$strRsLevel4->system_id;
												$strRsSystemNodesArr=$DB->Returns($strSQL);
												
												$iCtr=0;
												$totalKwh1_=0;
                                                $totalKwh2_ = 0;
												while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
												{
													##########################################
													# Calculating Kwh
													##########################################
												
													$DataVal1=0;
                                                    $DataVal2=0;
													if($strRsSystemNodes->available_system_node_serial<>'')
													{
														$DataVal1=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date1, $end_date1);
														$DataVal2=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date2, $end_date2);
                                                        if($strType==2){
                                                            $DataVal1 = $DataVal1/50;
                                                            $DataVal2 = $DataVal2/50;
                                                        }
                                                        $totalKwh1_=$totalKwh1_+floatval($DataVal1);
														$totalKwh2_=$totalKwh2_+floatval($DataVal2);
                                                        $Percentage_Calculation_IDArr_[]=$strRsSystemNodes->system_node_id;
													}
													
													
													$iCtr++;
													if($iCtr % 2==0)
														$bgColor='#FFFFFF';
													else
														$bgColor='#EFEFEF';
                                                    
													print "<div style='margin-left:10px;font-style:normal; color:#0088cc; text-decoration:underline; background-color:".$bgColor."'>
													<div style=' width:150px; overflow:hidden; float:left; text-decoration:underline; white-space:nowrap;' title='".($strRsSystemNodes->custom_name=='' ? $strRsSystemNodes->node_serial : $strRsSystemNodes->custom_name." (".$strRsSystemNodes->node_serial.")")."'>".($strRsSystemNodes->custom_name=='' ? $strRsSystemNodes->node_serial : $strRsSystemNodes->custom_name." (".$strRsSystemNodes->node_serial.")")."</div>
													
													<div style='float:left; text-decoration:underline; min-width:90px' id='totalKwh1".$strRsSystemNodes->system_node_id."'>".number_format($DataVal1,2)." $ValueUnit</div>
													<div style='float:left; margin-left:5px; width:40px' id='totalKwhPercent1".$strRsSystemNodes->system_node_id."'>25%</div>
													<div style='float:left; text-decoration:underline; min-width:90px;' id='totalKwh2".$strRsSystemNodes->system_node_id."'>".number_format($DataVal2,2)." $ValueUnit</div>
													<div style='float:left; margin-left:5px;' id='totalKwhPercent2".$strRsSystemNodes->system_node_id."'>25%</div>
													<div class='clear' style='margin-bottom:1px;'></div>
													
													</div>
													
													";
												?>
                                                <script type="text/javascript">$('#totalKwh1_<?php echo $strRsLevel4->system_id;?>').html('<?php echo number_format($totalKwh1_,2);?> <?php echo $ValueUnit;?>')</script> 
                                                <script type="text/javascript">$('#totalKwh2_<?php echo $strRsLevel4->system_id;?>').html('<?php echo number_format($totalKwh2_,2);?> <?php echo $ValueUnit;?>')</script> 
                                                <?php
												}
												$totalKwh1__=$totalKwh1__	+$totalKwh1_;																		
												$totalKwh2__=$totalKwh2__	+$totalKwh2_;	
                                                $Percentage_Calculation_IDArr__[]=$strRsLevel4->system_id;
												print "</div>";
											?>
                                            <script type="text/javascript">$('#totalKwh1__<?php echo $strRsLevel3->system_id;?>').html('<?php echo number_format($totalKwh1__,0);?> <?php echo $ValueUnit;?>')</script>
                                            <script type="text/javascript">$('#totalKwh2__<?php echo $strRsLevel3->system_id;?>').html('<?php echo number_format($totalKwh2__,0);?> <?php echo $ValueUnit;?>')</script>
                                            <?php
											}
                                        }
										$totalKwh1___=$totalKwh1___+$totalKwh1__;
										$totalKwh2___=$totalKwh2___+$totalKwh2__;
                                        $Percentage_Calculation_IDArr___[]=$strRsLevel3->system_id;
                                    }									
									?>
                                    	<script type="text/javascript">$('#totalKwh1___<?php echo $strRsLevel2->system_id;?>').html('<?php echo number_format($totalKwh1___,0);?> <?php echo $ValueUnit;?>')</script>
                                    	<script type="text/javascript">$('#totalKwh2___<?php echo $strRsLevel2->system_id;?>').html('<?php echo number_format($totalKwh2___,0);?> <?php echo $ValueUnit;?>')</script>
                                    <?php								
								}								
							}
							$totalKwh1____=$totalKwh1____+$totalKwh1___;	
                            $totalKwh2____=$totalKwh2____+$totalKwh2___;
							$Percentage_Calculation_IDArr____[]=$strRsLevel2->system_id;
                        }
                        print "</div>";
					}
					
				}
			}

			
		}
		
	}
}
?>
                                        
<script type="text/javascript">
    $('#Energy_Pick_Month_1').html('<?php echo date('F Y', strtotime($start_date1));?>');
    $('#Energy_Pick_Month_2').html('<?php echo date('F Y', strtotime($start_date2));?>');
    $('#Total_Electric_NaturalGas_Value_1').html('<?php echo number_format($totalKwh1____,0);?> <?php echo $ValueUnit;?>');
    $('#Total_Electric_NaturalGas_Value_2').html('<?php echo number_format($totalKwh2____,0);?> <?php echo $ValueUnit;?>');
<?php
    if(is_array($Percentage_Calculation_IDArr_) and count($Percentage_Calculation_IDArr_)>0)
    {
        ?>
        var TotalPercent=100;
        <?php
        foreach($Percentage_Calculation_IDArr_ as $Percentage_Calculation_ID)
        {?>
            var TotalValByID1=($('#totalKwh1<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
            var TotalValByID2=($('#totalKwh2<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
            TotalValByID1=parseFloat(TotalValByID1.replace("<?php echo $ValueUnit;?>",""));
            TotalValByID2=parseFloat(TotalValByID2.replace("<?php echo $ValueUnit;?>",""));
            if(TotalValByID1 != 0){
                TotalValByID1=Math.round((TotalValByID1/<?php echo $totalKwh1____?>)*100);
            }
            if(TotalValByID2 != 0){
                TotalValByID2=Math.round((TotalValByID2/<?php echo $totalKwh2____?>)*100);
            }
            TotalPercent1=TotalPercent-TotalValByID1;
            TotalPercent2=TotalPercent-TotalValByID2;
            //console.log(TotalValByID);
            if(TotalPercent1<0)
            {
                TotalValByID1=TotalValByID1+TotalPercent1;
            }
            if(TotalPercent2<0)
            {
                TotalValByID2=TotalValByID2+TotalPercent2;
            }
            $('#totalKwhPercent1<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID1+'%');
            $('#totalKwhPercent2<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID2+'%');
        <?php			
        }    
    } 
    
    if(is_array($Percentage_Calculation_IDArr__) and count($Percentage_Calculation_IDArr__)>0)
    {
        ?>
        var TotalPercent=100;
        <?php
        foreach($Percentage_Calculation_IDArr__ as $Percentage_Calculation_ID)
        {?>
            var TotalValByID1=($('#totalKwh1_<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
            var TotalValByID2=($('#totalKwh2_<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
            TotalValByID1=parseFloat(TotalValByID1.replace("<?php echo $ValueUnit;?>",""));
            TotalValByID2=parseFloat(TotalValByID2.replace("<?php echo $ValueUnit;?>",""));
            if(TotalValByID1 != 0){
                TotalValByID1=Math.round((TotalValByID1/<?php echo $totalKwh1____?>)*100);
            }
            if(TotalValByID2 != 0){
                TotalValByID2=Math.round((TotalValByID2/<?php echo $totalKwh2____?>)*100);
            }
            TotalPercent1=TotalPercent-TotalValByID1;
            TotalPercent2=TotalPercent-TotalValByID2;
            //console.log(TotalValByID);
            if(TotalPercent1<0)
            {
                TotalValByID1=TotalValByID1+TotalPercent1;
            }
            if(TotalPercent2<0)
            {
                TotalValByID2=TotalValByID2+TotalPercent2;
            }
            $('#totalKwhPercent1_<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID1+'%');
            $('#totalKwhPercent2_<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID2+'%');
        <?php			
        }    
    }
    
    if(is_array($Percentage_Calculation_IDArr___) and count($Percentage_Calculation_IDArr___)>0)
    {
        ?>
        var TotalPercent=100;
        <?php
        foreach($Percentage_Calculation_IDArr___ as $Percentage_Calculation_ID)
        {?>
            var TotalValByID1=($('#totalKwh1__<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
            var TotalValByID2=($('#totalKwh2__<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
            TotalValByID1=parseFloat(TotalValByID1.replace("<?php echo $ValueUnit;?>",""));
            TotalValByID2=parseFloat(TotalValByID2.replace("<?php echo $ValueUnit;?>",""));
            if(TotalValByID1 != 0){
                TotalValByID1=Math.round((TotalValByID1/<?php echo $totalKwh1____?>)*100);
            }
            if(TotalValByID2 != 0){
                TotalValByID2=Math.round((TotalValByID2/<?php echo $totalKwh2____?>)*100);
            }
            TotalPercent1=TotalPercent-TotalValByID1;
            TotalPercent2=TotalPercent-TotalValByID2;
            //console.log(TotalValByID);
            if(TotalPercent1<0)
            {
                TotalValByID1=TotalValByID1+TotalPercent1;
            }
            if(TotalPercent2<0)
            {
                TotalValByID2=TotalValByID2+TotalPercent2;
            }
            $('#totalKwhPercent1__<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID1+'%');
            $('#totalKwhPercent2__<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID2+'%');
        <?php			
        }    
    }
    
	if(is_array($Percentage_Calculation_IDArr____) and count($Percentage_Calculation_IDArr____)>0)
	{
		?>
		var TotalPercent=100;
        <?php
		foreach($Percentage_Calculation_IDArr____ as $Percentage_Calculation_ID)
		{?>
			var TotalValByID1=($('#totalKwh1___<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
			var TotalValByID2=($('#totalKwh2___<?php echo $Percentage_Calculation_ID;?>').html()).replace(",","");
			TotalValByID1=parseFloat(TotalValByID1.replace("<?php echo $ValueUnit;?>",""));
			TotalValByID2=parseFloat(TotalValByID2.replace("<?php echo $ValueUnit;?>",""));
            if(TotalValByID1 != 0){
                TotalValByID1=Math.round((TotalValByID1/<?php echo $totalKwh1____?>)*100);
            }
            if(TotalValByID2 != 0){
                TotalValByID2=Math.round((TotalValByID2/<?php echo $totalKwh2____?>)*100);
            }
			TotalPercent1=TotalPercent-TotalValByID1;
			TotalPercent2=TotalPercent-TotalValByID2;
			//console.log(TotalValByID);
			if(TotalPercent1<0)
			{
				TotalValByID1=TotalValByID1+TotalPercent1;
			}
			if(TotalPercent2<0)
			{
				TotalValByID2=TotalValByID2+TotalPercent2;
			}
			$('#totalKwhPercent1___<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID1+'%');
			$('#totalKwhPercent2___<?php echo $Percentage_Calculation_ID;?>').html(TotalValByID2+'%');
        <?php			
		}    
	}
?>
</script>