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
}else{
    $start_date = date("Y-m-01 00:00:00");
    $end_date = date("Y-m-d 23:59:59");
}

//*********************  Date & Time conversion to UTC  ****************************//

$start_date = gmdate('Y-m-d H:i:s', strtotime($start_date));
$end_date = gmdate('Y-m-d H:i:s', strtotime($end_date));


// Setting multiplier

$strSQL="Select * from t_energy_multiplier where building_id=".$building_id;
$multiplierArr=$DB->Returns($strSQL);
$strMultiplier=mysql_fetch_object($multiplierArr);
//print_r($strMultiplier);exit;
if($strType == 1 && $strMultiplier->energy_multiplier && $strMultiplier->energy_multiplier != ""){
    $multiplier = $strMultiplier->energy_multiplier;
}else if($strType == 2 && $strMultiplier->gas_multiplier && $strMultiplier->gas_multiplier != ""){
    $multiplier = $strMultiplier->gas_multiplier;
}else{
    $multiplier = 1;
}

$strSQL="Select * from t_energy_cost where building_id=".$building_id;
$costArr=$DB->Returns($strSQL);
$coststr=mysql_fetch_object($costArr);

if($strType==1)
{
	$cost = $coststr->energy_cost?$coststr->energy_cost:0;
}
else
{
	$cost = $coststr->gas_cost?$coststr->gas_cost:0;
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

$strSQL="select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsSystemsArr=$DB->Returns($strSQL);
while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
{
	getParent($strRsSystems->system_id);
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

$totalKwh____=0;
$ActualTotalKwh=0;

if(is_array($level1Arr) && count($level1Arr)>0)
{
    $totalKwh____=0;
    $ActualTotalKwh=0;
	foreach($level1Arr as $val1)
	{		
		$strSQL="Select * from t_system where system_id=$val1 and display_type=$strType.";
		$strRsLevel1Arr=$DB->Returns($strSQL);
		while($strRsLevel1=mysql_fetch_object($strRsLevel1Arr))
		{			
			//print "<div style='font-weight:bold;'>".$strRsLevel1->system_name."</div>";
			
			if(is_array($level2Arr) && count($level2Arr)>0)
			{
                if($strRsLevel1->exclude_in_calculation == 1){
                    $strSQL="select available_system_node_serial from t_system_node where delete_flag=0 and building_id=$building_id and system_id in (select system_id from t_system where parent_id in (Select system_id from t_system where parent_id in (Select system_id from t_system where parent_id=$strRsLevel1->system_id and exclude_in_calculation=1)))";
                    $strRsSystemNodesArr=$DB->Returns($strSQL);
                    while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
                    {
                        $DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date, $end_date);
                        $ActualTotalKwh+=floatval($DataVal);
                    }
                }else{
                    foreach($level2Arr as $val2)
                    {		
                        $strSQL="Select * from t_system where system_id=$val2 and parent_id=".$strRsLevel1->system_id." and exclude_in_calculation!=1";
                        $strRsLevel2Arr=$DB->Returns($strSQL);
                        while($strRsLevel2=mysql_fetch_object($strRsLevel2Arr))
                        {						
                            print "<div style='margin-left:0px; cursor:pointer; color:#0088cc; font-weight:bold; float:left; width:250px; ' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel2->system_id.")'><span class='System_ID_Expand_".$strRsLevel2->system_id."'>+</span>".$strRsLevel2->system_name."</div>
                            <div class='$valuBoxStyle' style='float:left; font-weight:normal; min-width: 80px;' id='totalKwh___".$strRsLevel2->system_id."'>0 $ValueUnit</div>
                            <div style='float:right; margin-right:20px;' id='cost_totalKwh___".$strRsLevel2->system_id."'>$0</div>
                            <div class='clear' style='margin-bottom:1px;'></div>
                            ";

                            if(is_array($level3Arr) && count($level3Arr)>0)
                            {
                                $totalKwh___=0;
                                foreach($level3Arr as $val3)
                                {	
                                    $strSQL="Select * from t_system where system_id=$val3 and parent_id=".$strRsLevel2->system_id;
                                    $strRsLevel3Arr=$DB->Returns($strSQL);
                                    while($strRsLevel3=mysql_fetch_object($strRsLevel3Arr))
                                    {		
                                        $DataVal=0;
                                        print "<div style='margin-left:10px; display:none; cursor:pointer; color:#0088cc;' onclick='Expand_Collapse_System_Node_For_Building(".$strRsLevel3->system_id.")' class='System_ID_".$strRsLevel2->system_id."'>

                                        <div style='width:240px; float:left;'> <span class='System_ID_Expand_".$strRsLevel3->system_id."'>+</span> ".$strRsLevel3->system_name."</div> <div style='float:left;' id='totalKwh__".$strRsLevel3->system_id."'>0 $ValueUnit</div>
                                        <div style='float:right; margin-right:20px;' id='cost_totalKwh__".$strRsLevel3->system_id."'>$0</div>
                                        <div class='clear' style='margin-bottom:1px;'></div>
                                        </div>

                                        ";									
                                        if(is_array($level4Arr) && count($level4Arr)>0)
                                        {
                                            $totalKwh__=0;
                                            foreach($level4Arr as $val4)
                                            {		
                                                $DataVal=0;	
                                                $strSQL="Select * from t_system where system_id=$val4 and parent_id=".$strRsLevel3->system_id;
                                                $strRsLevel4Arr=$DB->Returns($strSQL);
                                                while($strRsLevel4=mysql_fetch_object($strRsLevel4Arr))
                                                {												
                                                    print "<div style='margin-left:20px; display:none; font-style:italic;' class='System_ID_".$strRsLevel3->system_id." System_ID_Sub_".$strRsLevel2->system_id."'>
                                                    <div style='float:left; width:230px;'><span>".$strRsLevel4->system_name."</span></div>
                                                    <div style='float:left;' id='totalKwh_".$strRsLevel4->system_id."'>0 $ValueUnit</div>
                                                    <div style='float:right; margin-right:20px;' id='cost_totalKwh_".$strRsLevel4->system_id."'>$0</div>
                                                    <div class='clear' style='margin-bottom:1px;'></div>
                                                    ";

                                                    $strSQL="Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=".$strRsLevel4->system_id;
                                                    $strRsSystemNodesArr=$DB->Returns($strSQL);
                                                    $iCtr=0;
                                                    $totalKwh_=0;
                                                    while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
                                                    {
                                                        ##########################################
                                                        # Calculating Kwh
                                                        ##########################################

                                                        $DataVal=0;
                                                        if($strRsSystemNodes->available_system_node_serial<>'')
                                                        {
                                                            $DataVal=Globals::MarginalValueCalcBySystem("t_".$strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date, $end_date);
                                                            $DataVal *= $multiplier;
                                                            if($strType==2){
                                                                $DataVal = $DataVal/50;
                                                            }
                                                            $totalKwh_=$totalKwh_+floatval($DataVal);
                                                        }

                                                        $iCtr++;
                                                        if($iCtr % 2==0)
                                                            $bgColor='#FFFFFF';
                                                        else
                                                            $bgColor='#EFEFEF';
                                                        print "<div style='margin-left:10px;font-style:normal; color:#0088cc; text-decoration:underline; background-color:".$bgColor."'><div style=' width:220px; float:left; text-decoration:underline;'>".($strRsSystemNodes->custom_name=='' ? $strRsSystemNodes->node_serial : $strRsSystemNodes->custom_name." (".$strRsSystemNodes->node_serial.")")."</div>

                                                        <div style='float:left; text-decoration:underline;'>".number_format($DataVal,2)." $ValueUnit</div>
                                                        <div style='float:right; margin-right:20px;'>$".number_format($cost*$DataVal,2)."</div>
                                                        <div class='clear' style='margin-bottom:1px;'></div>

                                                        </div>

                                                        ";
                                                    ?>
                                                    <script type="text/javascript">$('#totalKwh_<?php echo $strRsLevel4->system_id;?>').html('<?php echo number_format($totalKwh_,2);?> <?php echo $ValueUnit;?>')</script>
                                                    <script type="text/javascript">$('#cost_totalKwh_<?php echo $strRsLevel4->system_id;?>').html('$<?php echo number_format($cost*$totalKwh_,2);?>')</script> 
                                                    <?php
                                                    }
                                                    $totalKwh__=$totalKwh__	+$totalKwh_;	
                                                    print "</div>";
                                                ?>
                                                <script type="text/javascript">$('#totalKwh__<?php echo $strRsLevel3->system_id;?>').html('<?php echo number_format($totalKwh__,0);?> <?php echo $ValueUnit;?>')</script>
                                                <script type="text/javascript">$('#cost_totalKwh__<?php echo $strRsLevel3->system_id;?>').html('$<?php echo number_format($cost*$totalKwh__,0);?>')</script>
                                                <?php
                                                }											
                                            }
                                        $totalKwh___=$totalKwh___+$totalKwh__;
                                        }
                                        ?>
                                            <script type="text/javascript">$('#totalKwh___<?php echo $strRsLevel2->system_id;?>').html('<?php echo number_format($totalKwh___,0);?> <?php echo $ValueUnit;?>')</script>
                                            <script type="text/javascript">$('#cost_totalKwh___<?php echo $strRsLevel2->system_id;?>').html('$<?php echo number_format($cost*$totalKwh___,0);?>')</script>
                                        <?php

                                    }

                                }
                                $totalKwh____=$totalKwh____+$totalKwh___;
                            }

                        }

                    }
                }
				
			}

			
		}
		
	}
}

if($strType==1)
{
    ?>
    <script type="text/javascript">
        $('#total_electric_site_detail').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');
        $('#cost_total_electric_site_detail').html('$<?php echo number_format($cost*$totalKwh____,0);?>');
        $('#total_electric_main_site_detail').html('<?php echo number_format($ActualTotalKwh,0);?> <?php echo $ValueUnit;?>');
        $('#cost_total_electric_main_site_detail').html('$<?php echo number_format($cost*$ActualTotalKwh,0);?>');
    </script>    
    <?php
}
else
{
    $ActualTotalKwh = $ActualTotalKwh/50;
    ?>
    <script type="text/javascript">
        $('#total_gas_site_detail').html('<?php echo number_format($totalKwh____,0);?> <?php echo $ValueUnit;?>');
        $('#cost_total_gas_site_detail').html('$<?php echo number_format($cost*$totalKwh____,0);?>');
        $('#total_gas_main_site_detail').html('<?php echo number_format($ActualTotalKwh,0);?> <?php echo $ValueUnit;?>');
        $('#cost_total_gas_main_site_detail').html('$<?php echo number_format($cost*$ActualTotalKwh,0);?>');
    </script>    
    <?php
}

?>