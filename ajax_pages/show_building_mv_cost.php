<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/system.class.php");
require_once(AbsPath."classes/projects.class.php");

$DB=new DB;

if($_POST['id']){
    $strSiteID=$_POST['id'];
    $building_id = $_POST['building_id'];
    if(isset($_POST['energy_multiplier'])){
        $energy_multiplier = $_POST['energy_multiplier'];
        $gas_multiplier = $_POST['gas_multiplier'];

        $strSQL = "insert into t_energy_multiplier (building_id, energy_multiplier, gas_multiplier, created) values ('$building_id', '$energy_multiplier', '$gas_multiplier', now()) ON DUPLICATE KEY UPDATE energy_multiplier = values(energy_multiplier), gas_multiplier = values(gas_multiplier), created = now()";
    }else{
        $energy_cost = $_POST['energy_cost'];
        $gas_cost = $_POST['gas_cost'];

        $strSQL = "insert into t_energy_cost (building_id, energy_cost, gas_cost, created) values ('$building_id', '$energy_cost', '$gas_cost', now()) ON DUPLICATE KEY UPDATE energy_cost = values(energy_cost), gas_cost = values(gas_cost), created = now()";
    }
    $DB->Returns($strSQL);
}else{
    $strSiteID=$_GET['id'];
}
?>


<script type="text/javascript">
function set_cost(building_id, site_id)
{
    var energy_cost = $('#ecost_' + building_id).val();
    var gas_cost = $('#gcost_' + building_id).val();
    $('#'+site_id).html("Loading...");
    $.post("<?php echo URL ?>ajax_pages/show_building_mv_cost.php",
            {
                id: site_id,
                building_id: building_id,
                energy_cost: energy_cost,
                gas_cost: gas_cost,
            },
    function (data, status) {
        $('#'+site_id).html(data);
    });
}

function set_multiplier(building_id, site_id)
{
    var energy_multiplier = $('#emultiplier_' + building_id).val();
    var gas_multiplier = $('#gmultiplier_' + building_id).val();
    $('#'+site_id).html("Loading...");
    $.post("<?php echo URL ?>ajax_pages/show_building_mv_cost.php",
            {
                id: site_id,
                building_id: building_id,
                energy_multiplier: energy_multiplier,
                gas_multiplier: gas_multiplier,
            },
    function (data, status) {
        $('#'+site_id).html(data);
    });
}
</script>

<?php
$strSQL="Select * from t_building where site_id=$strSiteID";
$strRsBuildingArr=$DB->Returns($strSQL);
while($strRsBuilding=mysql_fetch_object($strRsBuildingArr))
{
	//echo "<div class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:normal;'>Building:</span> ".$strRsBuilding->building_name."</div>";
	echo "<div onclick='PlusMinusBuilding(".$strRsBuilding->building_id.")' class='building_folder' style='float:left; width:90%; background-color:#DDDDDD; font-size:16px; margin-bottom:10px; '><span style='font-weight:bold; font-size:20px;' id='Building_Details_Plus_Minus_".$strRsBuilding->building_id."'>-</span><span style='font-weight:normal;'>Building:</span> <span style='text-decoration:underline;'>".$strRsBuilding->building_name."</span></div>";
    
    $strSQL="Select * from t_energy_cost where building_id=".$strRsBuilding->building_id;
    $costArr=$DB->Returns($strSQL);
    $cost=mysql_fetch_object($costArr);
    
    if(!$cost){
        
        $start_date = gmdate('Y-m-1 00:00:00', strtotime("-3 Months"));
        $end_date = gmdate('Y-m-d 23:59:59', strtotime("last day of -1 Months"));
        
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

        $strSQL="select Distinct system_id from t_system_node where building_id=$strRsBuilding->building_id";
        $strRsSystemsArr=$DB->Returns($strSQL);
        while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
        {
            getParent($strRsSystems->system_id);
        }

        //=============  For Electricity ==============//
        $output = array();
        if(is_array($level1Arr) && count($level1Arr)>0)
        {
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
                                                        $strSQL="Select * from t_system_node where building_id=$strRsBuilding->building_id and system_id=".$strRsLevel4->system_id;
                                                        $strRsSystemNodesArr=$DB->Returns($strSQL);

                                                        while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
                                                        {
                                                            ##########################################
                                                            # Calculating Kwh
                                                            ##########################################

                                                            if($strRsSystemNodes->available_system_node_serial<>'')
                                                            {
                                                                $strSQL="Select DATE_FORMAT(synctime,'%m') as month, min(kwhsystem) as StartVal, max(kwhsystem) as EndVal FROM `t_$strRsSystemNodes->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by month";
                                                                $consumptionArr=$DB->Returns($strSQL);
                                                                while($consumption=mysql_fetch_object($consumptionArr))
                                                                {
                                                                    if(isset($output[$consumption->month])){
                                                                        $output[$consumption->month] += (floatval($consumption->EndVal)-floatval($consumption->StartVal));
                                                                    }else{
                                                                        $output[$consumption->month] = (floatval($consumption->EndVal)-floatval($consumption->StartVal));
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
       
        if(sizeof($output) > 0){
            $total_output = 0;
            $count = 0;
            foreach ($output as $month_output){
                $total_output += $month_output;
                $count++;
            }
            $metered_electric = $total_output/$count;
            
            $from_date = gmdate('m/01/Y', strtotime("-3 Months"));
            $to_date = gmdate('m/d/Y', strtotime("last day of -1 Months"));
            $strSQL="SELECT CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, sum(consumption) as consumption FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.building_id = '$strRsBuilding->building_id' AND utility_account_type = 1 AND `from` >= '$from_date' AND `to` <= '$to_date' group by month";
            $strElectricAccountArr=$DB->Returns($strSQL);
            
            if(mysql_num_rows($strElectricAccountArr) > 0){
                $total_output = 0;
                $count = 0;
                while($strElectricAccount=mysql_fetch_object($strElectricAccountArr)) {
                    $total_output += $strElectricAccount->consumption;
                    $count++;
                }
                $billed_electric = $total_output/$count;
                
                $cost->energy_cost = number_format($billed_electric/$metered_electric, 3);
            }
        }
        
        //=============  For Natural Gas ==============//
        $output = array();
        if(is_array($level1Arr) && count($level1Arr)>0)
        {
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
                                                        $strSQL="Select * from t_system_node where building_id=$strRsBuilding->building_id and system_id=".$strRsLevel4->system_id;
                                                        $strRsSystemNodesArr=$DB->Returns($strSQL);

                                                        while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
                                                        {
                                                            ##########################################
                                                            # Calculating Kwh
                                                            ##########################################

                                                            if($strRsSystemNodes->available_system_node_serial<>'')
                                                            {
                                                                $strSQL="Select DATE_FORMAT(synctime,'%m') as month, min(kwhsystem) as StartVal, max(kwhsystem) as EndVal FROM `t_$strRsSystemNodes->available_system_node_serial` where `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by month";
                                                                $consumptionArr=$DB->Returns($strSQL);
                                                                while($consumption=mysql_fetch_object($consumptionArr))
                                                                {
                                                                    if(isset($output[$consumption->month])){
                                                                        $output[$consumption->month] += (floatval($consumption->EndVal)-floatval($consumption->StartVal));
                                                                    }else{
                                                                        $output[$consumption->month] = (floatval($consumption->EndVal)-floatval($consumption->StartVal));
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
       
        if(sizeof($output) > 0){
            $total_output = 0;
            $count = 0;
            foreach ($output as $month_output){
                $total_output += $month_output;
                $count++;
            }
            $metered_gas = $total_output/$count;
            
            $from_date = gmdate('m/01/Y', strtotime("-3 Months"));
            $to_date = gmdate('m/d/Y', strtotime("last day of -1 Months"));
            $strSQL="SELECT CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, sum(consumption) as consumption FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.building_id = '$strRsBuilding->building_id' AND utility_account_type = 2 AND `from` >= '$from_date' AND `to` <= '$to_date' group by month";
            $strGasAccountArr=$DB->Returns($strSQL);
            
            if(mysql_num_rows($strGasAccountArr) > 0){
                $total_output = 0;
                $count = 0;
                while($strGasAccount=mysql_fetch_object($strGasAccountArr)) {
                    $total_output += $strGasAccount->consumption;
                    $count++;
                }
                $billed_gas = $total_output/$count;
                
                $cost->gas_cost = number_format($billed_gas/$metered_gas, 3);
            }
        }
    
    }
?>
    <div class='clear'></div>
    
    <div style='float:left; margin-left:50px;'><b style='text-decoration:underline;'>SET COST</b></div>
    <div class='clear'></div>
    <div id="Building_Node_Details_<?php echo $strRsBuilding->building_id;?>">

        <div style="float: left;">
            <div style='float:left; margin-top:5px; margin-left:50px;'>
                <span style="float:left; margin-top:5px;width:280px;">Default Utility Electricity Cost ($/kwh):</span>
                <input type="text" style="float:left; margin-left:15px;" value="<?=$cost->energy_cost?>" id="ecost_<?=$strRsBuilding->building_id;?>">
            </div>
            <div class='clear'></div>
            <div style='float:left; margin-top:5px; margin-left:50px;'>
                <span style="float:left; margin-top:5px;width:280px;">Default Utility Natural Gas Cost ($/therm):</span>
                <input type="text" style="float:left; margin-left:15px;" value="<?=$cost->gas_cost?>" id="gcost_<?=$strRsBuilding->building_id;?>">
            </div>
            <div class='clear'></div>
            <div class='clear'></div>
            <div style='float:left; margin-top:5px; margin-left:50px;'>
                <span style="float:left; margin-top:5px;width:280px;">Updated At:</span>
                <span style="float:left; margin-left:15px;"><?=$cost->created?></span>
            </div>
            <div class='clear'></div>
        </div>
        <div style="float: left; margin-top: 25px;">
            <input type="button" style="float:left; margin-left:30px;" value="SET" name="btnSET" id="btnSET" onclick="set_cost(<?=$strRsBuilding->building_id;?>, <?=$strSiteID?>)">
        </div>
        <div class='clear'></div>
    </div>
    <div class='clear'></div>
    
    <?php
    $strSQL="Select * from t_energy_multiplier where building_id=".$strRsBuilding->building_id;
    $multiplierArr=$DB->Returns($strSQL);
    $multiplier=mysql_fetch_object($multiplierArr);
    ?>
    <div style='float:left; margin-left:50px; margin-top:40px;'><b style='text-decoration:underline;'>ADJUSTED UTILITY MULTIPLIER</b></div>
    <div class='clear'></div>
    <div id="Building_Node_Details_<?php echo $strRsBuilding->building_id;?>">

        <div style="float: left;">
            <div style='float:left; margin-top:5px; margin-left:50px;'>
                <span style="float:left; margin-top:5px;width:280px;">Electricity Multiplier:</span>
                <input type="text" style="float:left; margin-left:15px;" value="<?=$multiplier->energy_multiplier?>" id="emultiplier_<?=$strRsBuilding->building_id;?>">
            </div>
            <div class='clear'></div>
            <div style='float:left; margin-top:5px; margin-left:50px;'>
                <span style="float:left; margin-top:5px;width:280px;">Natural Gas Multiplier:</span>
                <input type="text" style="float:left; margin-left:15px;" value="<?=$multiplier->gas_multiplier?>" id="gmultiplier_<?=$strRsBuilding->building_id;?>">
            </div>
            <div class='clear'></div>
        </div>
        <div style="float: left; margin-top: 25px;">
            <input type="button" style="float:left; margin-left:30px;" value="SET" name="btnSET" id="btnSET" onclick="set_multiplier(<?=$strRsBuilding->building_id;?>, <?=$strSiteID?>)">
        </div>
        <div class='clear'></div>
    </div>
    <div class='clear'></div>
<?php } ?>