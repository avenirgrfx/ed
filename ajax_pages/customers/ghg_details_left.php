<?php
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');

$DB = new DB;

$building_id=$_GET['building_id'];
$start_date = $_GET['from_date'];
$end_date = $_GET['to_date'];

$start_date = date('Y-m-d 00:00:00', strtotime($start_date));
$end_date = date('Y-m-d 23:59:59', strtotime($end_date));

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

$strSQL="Select * from t_mv_baseline where building_id=".$building_id;
$baselineArr=$DB->Returns($strSQL);
if(mysql_num_rows($baselineArr)>0)
{
    $strSQL="Select * from t_subregion_emission_factor where subregion_acr = (select subregion from t_zip_subregion where zip = (select zip from t_building where building_id = $building_id))";
	$strEmissionDArr=$DB->Returns($strSQL);
    if($strEmission=mysql_fetch_object($strEmissionDArr)){
        $co2 = $strEmission->co2/1000;
        $ch4 = $strEmission->ch4/1000;
        $n2o = $strEmission->n2o/1000;
    }else{
        $co2 = 1;
        $ch4 = 1;
        $n2o = 1;
    }
    $baseline=mysql_fetch_object($baselineArr);
    $e_baseline = $baseline->e_mon + $baseline->e_tue + $baseline->e_wed + $baseline->e_thu + $baseline->e_fri + $baseline->e_sat + $baseline->e_sun;
    $g_baseline = $baseline->g_mon + $baseline->g_tue + $baseline->g_wed + $baseline->g_thu + $baseline->g_fri + $baseline->g_sat + $baseline->g_sun;
    
    $e_baseline = $e_baseline*30/7;
    $g_baseline = $g_baseline*30/7;
    
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
            $strSQL="Select * from t_system where system_id=$val1 and display_type=1";
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
                                                        if($strRsSystemNodes->available_system_node_serial<>'')
                                                        {
                                                            //$start_date = '2015-09-25 0:00:00';
                                                            //$end_date = '2015-10-05 23:59:59';
                                                            $strSQL="SELECT DATE_FORMAT(synctime,'%Y-%m-%d') as date, (max(`kwhsystem`)- min(`kwhsystem`)) as unit FROM `t_$strRsSystemNodes->available_system_node_serial` WHERE `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by date";
                                                            $consumptionArr=$DB->Returns($strSQL);
                                                            while($consumption=mysql_fetch_object($consumptionArr))
                                                            {
                                                                if(isset($output[$consumption->date])){
                                                                    $output[$consumption->date] += floatval($consumption->unit);
                                                                }else{
                                                                    $output[$consumption->date] = floatval($consumption->unit);
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
    
    $Mon = $Tue = $Wed = $Thu = $Fri = $Sat = $Sun = 0;
    $days = array();
    $e_consumption = 0;
    foreach ($output as $date => $unit){
        $e_consumption += $unit;
        $dow = date('D', strtotime($date));
        if(isset($days[$dow])){
            $days[$dow] += $unit;
        }else{
            $days[$dow] = $unit;
        }
        $$dow++;
    }
    
    $e_days = array();
    foreach ($days as $dow => $units){
        $day_name = "e_".strtolower($dow);
        $e_days[$dow] = $units - $baseline->{$day_name}*$$dow;
    }
    
    $ghg_electric_sum = array_sum($e_days);
    if($ghg_electric_sum>0){
        $e_color = 'red';
    }else{
        //$ghg_electric_sum = 0 - $ghg_electric_sum;
        $e_color = 'green';
    }    
    //print_r($ghg_electric_sum);exit;
?>
    
    
<?php
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
                                                        if($strRsSystemNodes->available_system_node_serial<>'')
                                                        {
                                                            //$start_date = '2015-09-25 0:00:00';
                                                            //$end_date = '2015-10-05 23:59:59';
                                                            $strSQL="SELECT DATE_FORMAT(synctime,'%Y-%m-%d') as date, (max(`kwhsystem`)- min(`kwhsystem`)) as unit FROM `t_$strRsSystemNodes->available_system_node_serial` WHERE `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by date";
                                                            $consumptionArr=$DB->Returns($strSQL);
                                                            while($consumption=mysql_fetch_object($consumptionArr))
                                                            {
                                                                if(isset($output[$consumption->date])){
                                                                    $output[$consumption->date] += floatval($consumption->unit);
                                                                }else{
                                                                    $output[$consumption->date] = floatval($consumption->unit);
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
    
    $Mon = $Tue = $Wed = $Thu = $Fri = $Sat = $Sun = 0;
    $days = array();
    $g_consumption = 0;
    foreach ($output as $date => $unit){
        $g_consumption += $unit;
        $dow = date('D', strtotime($date));
        if(isset($days[$dow])){
            $days[$dow] += $unit;
        }else{
            $days[$dow] = $unit;
        }
        $$dow++;
    }
    
    $g_days = array();
    foreach ($days as $dow => $units){
        $day_name = "e_".strtolower($dow);
        $g_days[$dow] = $units - $baseline->{$day_name}*$$dow;
    }
    
    $ghg_gas_sum = array_sum($g_days);
    if($ghg_gas_sum>0){
        $g_color = 'red';
    }else{
        //$ghg_gas_sum = 0 - $ghg_gas_sum;
        $g_color = 'green';
    } 
?>
    <div style="float:left; font-weight:bold; font-size:14px; width:205px;">ELECTRICITY GHG EMISSIONS</div>
    <div class="dark_<?=$e_color;?>_box_for_value" style="float:left; margin-left:0px;">
        <?=number_format($ghg_electric_sum, 0);?> kWh
    </div>
    <div class="clear" style="border-bottom:1px solid #DDDDDD; margin:5px 0px;"></div>

    <div style="float:left; width:205px;">CARBON DIOXIDE (CO2)</div>
    <div class="light_<?=$e_color;?>_box_for_value" style="float:left;"><?php $emm = $ghg_electric_sum*$co2; echo $emm>4000?number_format($emm/2000,1)." TONS":number_format($emm,0)." LBS"?></div>
    <div class="clear" style="margin-bottom:3px;"></div>

    <div style="float:left; width:205px;">METHANE (CH4)</div> 
    <div class="light_<?=$e_color;?>_box_for_value" style="float:left;;"><?=number_format($ghg_electric_sum*$ch4, 3);?> LBS</div>
    <div class="clear" style="margin-bottom:3px;"></div>

    <div style="float:left; width:205px;">NITROGEN OXIDE (N2O)</div> 
    <div class="light_<?=$e_color;?>_box_for_value" style="float:left;"><?=number_format($ghg_electric_sum*$n2o, 3);?> LBS</div>
    <div class="clear" style="margin-bottom:20px;"></div>


    <div style="float:left; font-weight:bold; font-size:14px; width:205px;">NATURAL GAS GHG EMISSIONS</div>
    <div class="dark_<?=$g_color;?>_box_for_value" style="float:left; margin-left:0px;">
        <?=number_format($ghg_gas_sum/29.300111, 0);?> therms
    </div>
    <div class="clear" style="border-bottom:1px solid #DDDDDD; margin:5px 0px;"></div>

    <div style="float:left; width:205px;">CARBON DIOXIDE (CO2)</div> 
    <div class="light_<?=$g_color;?>_box_for_value" style="float:left;"><?php $emm = $ghg_gas_sum*$co2; echo $emm>4000?number_format($emm/2000,1)." TONS":number_format($emm,0)." LBS"?></div>
    <div class="clear" style="margin-bottom:3px;"></div>

    <div style="float:left; width:205px;">METHANE (CH4)</div> 
    <div class="light_<?=$g_color;?>_box_for_value" style="float:left;"><?=number_format($ghg_gas_sum*$ch4, 3);?> LBS</div>
    <div class="clear" style="margin-bottom:3px;"></div>

    <div style="float:left; width:205px;">NITROGEN OXIDE (N2O)</div> 
    <div class="light_<?=$g_color;?>_box_for_value" style="float:left;"><?=number_format($ghg_gas_sum*$n2o, 3);?> LBS</div>
    <div class="clear" style="margin-bottom:10px;"></div>
<?php
}
else
{
    echo '<div style="color:#666666; font-weight:bold; font-size:16px; margin-bottom:5px;">
        Baseline not set for this building.
    </div>';
}
?>