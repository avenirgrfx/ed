<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/system.class.php");
require_once(AbsPath."classes/projects.class.php");

$DB=new DB;
$System=new System;
$Project=new Project;

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

if($_POST['id']){
    $strSiteID=$_POST['id'];
    $building_id = $_POST['building_id'];
    $strFromDate=$_POST['from_date'];
    $strToDate=$_POST['to_date'];
    
    $start_date = date('Y-m-d 00:00:00', strtotime($strFromDate));
    $end_date = date('Y-m-d 23:59:59', strtotime($strToDate));
    
    $strSQL="select Distinct system_id from t_system_node where building_id=$building_id";
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
                                                    $strSQL="Select * from t_system_node where building_id=$building_id and system_id=".$strRsLevel4->system_id;
                                                    $strRsSystemNodesArr=$DB->Returns($strSQL);

                                                    while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
                                                    {
                                                        if($strRsSystemNodes->available_system_node_serial<>'')
                                                        {
                                                            //$start_date = '2015-09-25 0:00:00';
                                                            //$end_date = '2015-10-05 23:59:59';
                                                            $strSQL="SELECT DATE_FORMAT(synctime,'%Y-%m-%d %H:00:00') as date, (max(`kwhsystem`)- min(`kwhsystem`)) as unit FROM `t_$strRsSystemNodes->available_system_node_serial` WHERE `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by date";
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
    
    $Mon_1 = $Mon_2 = $Mon_3 = $Mon_4 = $Tue_1 = $Tue_2 = $Tue_3 = $Tue_4 = $Wed_1 = $Wed_2 = $Wed_3 = $Wed_4 = $Thu_1 = $Thu_2 = $Thu_3 = $Thu_4 = $Fri_1 = $Fri_2 = $Fri_3 = $Fri_4 = $Sat_1 = $Sat_2 = $Sat_3 = $Sat_4 = $Sun_1 = $Sun_2 = $Sun_3 = $Sun_4 = 0;
    $quarter = array();
    foreach ($output as $date => $unit){
        $hour = intval(date('H', strtotime($date)));
        $dow = date('D', strtotime($date));
        if($hour >= 0 && $hour <= 5){
            if(isset($quarter[$dow."_1"])){
                $quarter[$dow."_1"] += $unit;
            }else{
                $quarter[$dow."_1"] = $unit;
            }
            $count = $dow."_1";
            $$count++;
        }else if($hour >= 6 && $hour <= 11){
            if(isset($quarter[$dow."_2"])){
                $quarter[$dow."_2"] += $unit;
            }else{
                $quarter[$dow."_2"] = $unit;
            }
            $count = $dow."_2";
            $$count++;
        }else if($hour >= 12 && $hour <= 17){
            if(isset($quarter[$dow."_3"])){
                $quarter[$dow."_3"] += $unit;
            }else{
                $quarter[$dow."_3"] = $unit;
            }
            $count = $dow."_3";
            $$count++;
        }else if($hour >= 18 && $hour <= 23){
            if(isset($quarter[$dow."_1"])){
                $quarter[$dow."_4"] += $unit;
            }else{
                $quarter[$dow."_4"] = $unit;
            }
            $count = $dow."_4";
            $$count++;
        }
    }
    //print_r($quarter);
    
    $e_days = array();
    foreach ($quarter as $dow => $units){
        $e_days[$dow] = $units*6/$$dow;
    }
    
    
    //print_r($e_days);
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
                                                    $strSQL="Select * from t_system_node where building_id=$building_id and system_id=".$strRsLevel4->system_id;
                                                    $strRsSystemNodesArr=$DB->Returns($strSQL);

                                                    while($strRsSystemNodes=mysql_fetch_object($strRsSystemNodesArr))
                                                    {
                                                        if($strRsSystemNodes->available_system_node_serial<>'')
                                                        {
                                                            //$start_date = '2015-09-25 0:00:00';
                                                            //$end_date = '2015-10-05 23:59:59';
                                                            $strSQL="SELECT DATE_FORMAT(synctime,'%Y-%m-%d %H:00:00') as date, (max(`kwhsystem`)- min(`kwhsystem`)) as unit FROM `t_$strRsSystemNodes->available_system_node_serial` WHERE `synctime` >= '$start_date' AND `synctime` <= '$end_date' group by date";
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
    
    $Mon_1 = $Mon_2 = $Mon_3 = $Mon_4 = $Tue_1 = $Tue_2 = $Tue_3 = $Tue_4 = $Wed_1 = $Wed_2 = $Wed_3 = $Wed_4 = $Thu_1 = $Thu_2 = $Thu_3 = $Thu_4 = $Fri_1 = $Fri_2 = $Fri_3 = $Fri_4 = $Sat_1 = $Sat_2 = $Sat_3 = $Sat_4 = $Sun_1 = $Sun_2 = $Sun_3 = $Sun_4 = 0;
    $quarter = array();
    foreach ($output as $date => $unit){
        $hour = intval(date('H', strtotime($date)));
        $dow = date('D', strtotime($date));
        if($hour >= 0 && $hour <= 5){
            if(isset($quarter[$dow."_1"])){
                $quarter[$dow."_1"] += $unit;
            }else{
                $quarter[$dow."_1"] = $unit;
            }
            $count = $dow."_1";
            $$count++;
        }else if($hour >= 6 && $hour <= 11){
            if(isset($quarter[$dow."_2"])){
                $quarter[$dow."_2"] += $unit;
            }else{
                $quarter[$dow."_2"] = $unit;
            }
            $count = $dow."_2";
            $$count++;
        }else if($hour >= 12 && $hour <= 17){
            if(isset($quarter[$dow."_3"])){
                $quarter[$dow."_3"] += $unit;
            }else{
                $quarter[$dow."_3"] = $unit;
            }
            $count = $dow."_3";
            $$count++;
        }else if($hour >= 18 && $hour <= 23){
            if(isset($quarter[$dow."_1"])){
                $quarter[$dow."_4"] += $unit;
            }else{
                $quarter[$dow."_4"] = $unit;
            }
            $count = $dow."_4";
            $$count++;
        }
    }
    //print_r($quarter);
    
    $g_days = array();
    foreach ($quarter as $dow => $units){
        $g_days[$dow] = $units*6/$$dow;
    }
    //print_r($g_days);
    
    $day_array = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
    
    $strSQL = "insert into t_mv_baseline (building_id, from_date, to_date, e_mon, e_tue, e_wed, e_thu, e_fri, e_sat, e_sun, g_mon, g_tue, g_wed, g_thu, g_fri, g_sat, g_sun, ";
    foreach($day_array as $dow){
        for($i=1; $i <= 4; $i++){
            $strSQL.= "e_$dow"."_$i, g_$dow"."_$i, ";
        }
    }
    $strSQL.= "created, created_by) ";
    
    $strSQL.= "values ('$building_id', '$strFromDate', '$strToDate', ";
    $Mon = $e_days['Mon_1'] + $e_days['Mon_2'] + $e_days['Mon_3'] + $e_days['Mon_4'];
    $Tue = $e_days['Tue_1'] + $e_days['Tue_2'] + $e_days['Tue_3'] + $e_days['Tue_4'];
    $Wed = $e_days['Wed_1'] + $e_days['Wed_2'] + $e_days['Wed_3'] + $e_days['Wed_4'];
    $Thu = $e_days['Thu_1'] + $e_days['Thu_2'] + $e_days['Thu_3'] + $e_days['Thu_4'];
    $Fri = $e_days['Fri_1'] + $e_days['Fri_2'] + $e_days['Fri_3'] + $e_days['Fri_4'];
    $Sat = $e_days['Sat_1'] + $e_days['Sat_2'] + $e_days['Sat_3'] + $e_days['Sat_4'];
    $Sun = $e_days['Sun_1'] + $e_days['Sun_2'] + $e_days['Sun_3'] + $e_days['Sun_4'];
    $strSQL.= "'$Mon', '$Tue', '$Wed', '$Thu', '$Fri', '$Sat', '$Sun', ";
    $Mon = $g_days['Mon_1'] + $g_days['Mon_2'] + $g_days['Mon_3'] + $g_days['Mon_4'];
    $Tue = $g_days['Tue_1'] + $g_days['Tue_2'] + $g_days['Tue_3'] + $g_days['Tue_4'];
    $Wed = $g_days['Wed_1'] + $g_days['Wed_2'] + $g_days['Wed_3'] + $g_days['Wed_4'];
    $Thu = $g_days['Thu_1'] + $g_days['Thu_2'] + $g_days['Thu_3'] + $g_days['Thu_4'];
    $Fri = $g_days['Fri_1'] + $g_days['Fri_2'] + $g_days['Fri_3'] + $g_days['Fri_4'];
    $Sat = $g_days['Sat_1'] + $g_days['Sat_2'] + $g_days['Sat_3'] + $g_days['Sat_4'];
    $Sun = $g_days['Sun_1'] + $g_days['Sun_2'] + $g_days['Sun_3'] + $g_days['Sun_4'];
    $strSQL.= "'$Mon', '$Tue', '$Wed', '$Thu', '$Fri', '$Sat', '$Sun', ";
    
    
    $day_array = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    foreach($day_array as $dow){
        for($i=1; $i <= 4; $i++){
            $day = $dow."_".$i;
            $strSQL.= "'".$e_days[$day]."', '".$g_days[$day]."', ";
        }
    }
    
    $strSQL.= "now(), '') ";
    $strSQL.= "ON DUPLICATE KEY UPDATE from_date = values(from_date), to_date = values(to_date), ";
	
    $day_array = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
    foreach($day_array as $dow){
        $strSQL.= "e_$dow = values(e_$dow), g_$dow = values(g_$dow), ";
        for($i=1; $i <= 4; $i++){
            $strSQL.= "e_$dow"."_$i = values(e_$dow"."_$i), g_$dow"."_$i = values(g_$dow"."_$i), ";
        }
    }
    $strSQL.= "created = values(created), created_by = values(created_by)";
    //echo $strSQL;exit;
    $DB->Returns($strSQL);
    
}else{
    $strSiteID=$_GET['id'];
}
?>


<script type="text/javascript">
    var month = new Array();
	month[0] = "January";
	month[1] = "February";
	month[2] = "March";
	month[3] = "April";
	month[4] = "May";
	month[5] = "June";
	month[6] = "July";
	month[7] = "August";
	month[8] = "September";
	month[9] = "October";
	month[10] = "November";
	month[11] = "December";

    function set_baseline(building_id, site_id)
    {
        if($('#txt_mv_FromDate_' + building_id).val() != "" && $('#txt_mv_ToDate_' + building_id).val() != ""){
            var from_date = $('#txt_mv_FromDate_' + building_id).val();
            var to_date = $('#txt_mv_ToDate_' + building_id).val();
            $('#'+site_id).html("Loading...");
            $.post("<?php echo URL ?>ajax_pages/show_building_mv.php",
                    {
                        id: site_id,
                        building_id: building_id,
                        from_date: from_date,
                        to_date: to_date,
                    },
            function (data, status) {
                $('#'+site_id).html(data);
            });
        }
    }
    
    function changeSummaryType(value, building_id){
        if(value == ""){
            $.get("<?php echo URL ?>ajax_pages/show_building_mv.php",
                    {
                        id: <?=$strSiteID;?>
                    },
            function (data, status) {
                $('#<?=$strSiteID;?>').html(data);
            });
        }else{
            $.get("<?php echo URL ?>ajax_pages/show_building_mv_summary.php",
                    {
                        displayType: value,
                        building_id: building_id
                    },
            function (data, status) {
                $('#baseline_table_'+building_id).html(data);
            });
        }
    }
    
    function set_peer_eui(building_id)
    {
        var peer = $('#peer_' + building_id).val();
        var eui = $('#eui_' + building_id).val();
        $('#peer_eui_'+building_id).html("Loading...");
        $.post("<?php echo URL ?>ajax_pages/save_building_peer_eui.php",
                {
                    building_id: building_id,
                    peer: peer,
                    eui: eui,
                },
        function (data, status) {
            $('#peer_eui_'+building_id).html(data);
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
	?>
    <script>
        $(function(){
	        var d = new Date();
            d.setDate(d.getDate() - 7);

            var txt_mv_FromDate_<?=$strRsBuilding->building_id;?> = $( "#txt_mv_FromDate_<?=$strRsBuilding->building_id?>" ).datepicker({
                maxDate: d
            });
            var txt_mv_ToDate_<?=$strRsBuilding->building_id;?>  = $( "#txt_mv_ToDate_<?=$strRsBuilding->building_id?>" ).datepicker({
                maxDate: new Date(),
                onSelect: function( selectedDate ) {
                    selectedDate = new Date(selectedDate);
                    selectedDate.setDate(selectedDate.getDate() - 7);
                    txt_mv_FromDate_<?=$strRsBuilding->building_id?>.datepicker( "option", "maxDate", selectedDate);
                }
            });
        });
    </script>
    <div class='clear'></div>
    
    <div style='width:40%;float:left;'>
        <div style='float:left; margin-left:50px;'><b style='text-decoration:underline;'>SET BASELINE PERIOD</b></div>
        <div class='clear'></div>
        <div id="Building_Node_Details_<?php echo $strRsBuilding->building_id;?>">

            <div style='float:left; margin-left:50px;' id='building_system_nodes_<?php echo $strRsBuilding->building_id;?>'>

                <div style="float:left;"></div>        
                <div style="float:left;">From: <input type="text" name="txt_mv_FromDate" id="txt_mv_FromDate_<?=$strRsBuilding->building_id?>" placeholder="Pick Date" value="" style="width:100px; font-size:12px; height:12px;" /></div>
                <div style="float:left; margin-left:30px;">To: <input type="text" name="txt_mv_ToDate" id="txt_mv_ToDate_<?=$strRsBuilding->building_id?>" placeholder="Pick Date" value="" style="width:100px; font-size:12px; height:12px;" /></div>
                <div class="clear"></div>

            </div>
            <div>
                <input type="button" style="float:left; margin-left:30px;" value="SET" name="btnSET" id="btnSET" onclick="set_baseline(<?=$strRsBuilding->building_id;?>, <?=$strSiteID?>)">
            </div>
            <div class='clear'></div>
        </div>
        <div class='clear'></div>
        <?php
            $temp = "";
            $strSQL="Select * from t_mv_baseline where building_id=".$strRsBuilding->building_id;
            $baselineArr=$DB->Returns($strSQL);
            if(mysql_num_rows($baselineArr)>0)
            {
                while($baseline=mysql_fetch_object($baselineArr))
                {
                    $temp = $baseline;
                    ?>	
                    <div style='float:left; margin-left:50px; margin-top: 10px;'><b style='text-decoration:underline;'>ACTIVE PERIOD:</b> <?=$baseline->from_date;?> - <?=$baseline->to_date;?> &nbsp;&nbsp;&nbsp; (<?=(strtotime($baseline->to_date)-strtotime($baseline->from_date))/(60*60*24)?> Days)</div>
                    <div class='clear'></div>
                    <div style='float:left; margin-left:50px;'>Set By: <?=$baseline->created_by!=0?$baseline->created_by:"None";?></div>
                    <div class='clear'></div>
                    <div style='float:left; margin-left:50px;'>Date Created: <?=$baseline->created;?></div>
                    <div class='clear'></div>
        
                    <?php
                }
            }
        ?>
        <div style="margin-top:15px" id="peer_eui_<?=$strRsBuilding->building_id;?>">
            <div style="float: left;">
                <div style='float:left; margin-top:5px; margin-left:50px;'>
                    <span style="float:left; margin-top:5px;width:90px;">PEER:</span>
                    <input type="text" style="float:left; margin-left:15px;" value="" id="peer_<?=$strRsBuilding->building_id;?>">
                </div>
                <div class='clear'></div>
                <div style='float:left; margin-top:5px; margin-left:50px;'>
                    <span style="float:left; margin-top:5px;width:90px;">EUI (kBtu/ft<sup>2</sup>):</span>
                    <input type="text" style="float:left; margin-left:15px;" value="" id="eui_<?=$strRsBuilding->building_id;?>">
                </div>
                <div class='clear'></div>
            </div>
            <div style="float: left; margin-top: 25px;">
                <input type="button" style="float:left; margin-left:30px;" value="SET" name="btnSET" id="btnSET" onclick="set_peer_eui(<?=$strRsBuilding->building_id;?>, <?=$strSiteID?>)">
            </div>
        </div>
    </div>
    
    <?php if($temp && $temp != ""){ $baseline = $temp; ?>
                
    <div style='width:60%;float:right;'>
        <div style='float:left;margin-bottom:5px;'><b style='font-size:17px;'>BASELINE SUMMARY</b>
            <select id="mvSummaryType" name="mvSummaryType" style="width:165px;" onchange="changeSummaryType(this.value, <?=$strRsBuilding->building_id;?>);">
                <option value="">Combined MMBTU</option>
                <option value="1">Electric MMBTU</option>
                <option value="2">Natural Gas MMBTU</option>
            </select>
            <div style="float: right; margin-left: 5px; width: 328px;">The table below shows averages of weekday data over active Baseline date range</div>
        </div>
        <div style='clear:both;'></div>
        <div id="baseline_table_<?=$strRsBuilding->building_id;?>">
            <table>
                <tr>
                    <th>&nbsp;</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Sunday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Monday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Tuesday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Wednesday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Thursday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Friday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Saturday</th>
                    <th style="background-color:#DDDDDD;border: 2px solid black;padding-left: 4px;padding-right: 4px;">Average</th>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">00:00-06:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun_1+$baseline->g_sun_1)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon_1+$baseline->g_mon_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue_1+$baseline->g_tue_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed_1+$baseline->g_wed_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu_1+$baseline->g_thu_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri_1+$baseline->g_fri_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat_1+$baseline->g_sat_1)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">06:01-12:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun_2+$baseline->g_sun_2)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon_2+$baseline->g_mon_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue_2+$baseline->g_tue_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed_2+$baseline->g_wed_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu_2+$baseline->g_thu_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri_2+$baseline->g_fri_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat_2+$baseline->g_sat_2)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">12:01-18:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun_3+$baseline->g_sun_3)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon_3+$baseline->g_mon_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue_3+$baseline->g_tue_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed_3+$baseline->g_wed_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu_3+$baseline->g_thu_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri_3+$baseline->g_fri_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat_3+$baseline->g_sat_3)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr>
                    <td style="background-color:#DDDDDD;border: 2px solid black;">18:01-00:00</td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun_4+$baseline->g_sun_4)/293.071107; $sum = $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon_4+$baseline->g_mon_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue_4+$baseline->g_tue_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed_4+$baseline->g_wed_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu_4+$baseline->g_thu_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri_4+$baseline->g_fri_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat_4+$baseline->g_sat_4)/293.071107; $sum += $total; echo number_format($total,3); ?></td>
                    <td style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></td>
                </tr>
                <tr style="background-color:#DDDDDD;">
                    <th style="background-color:#DDDDDD;border: 2px solid black;">Total</th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sun+$baseline->g_sun)/293.071107; $sum = $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_mon+$baseline->g_mon)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_tue+$baseline->g_tue)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_wed+$baseline->g_wed)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_thu+$baseline->g_thu)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_fri+$baseline->g_fri)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;"><?php $total = ($baseline->e_sat+$baseline->g_sat)/293.071107; $sum += $total; echo number_format($total,3); ?></th>
                    <th style="text-align:right;border: 2px solid black;padding-right: 4px;background-color:#DDDDDD;"><?= number_format($sum/7,3); ?></th>
                </tr>
            </table>
        </div>
    </div>
                
    <?php } ?>
    <div style='clear:both;'></div>
<?php } ?>