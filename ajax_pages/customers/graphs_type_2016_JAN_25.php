<?php
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');

$DB = new DB;
$type = Globals::Get('type');
$building_id = Globals::Get('building_id');

$strSQL="Select time_zone, daylight_saving from t_building where building_id='$building_id'";
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $daylight_saving = $strTime_zone->daylight_saving;
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($time_zone);
?>

<?php
if ($type == 1) {
    if ($building_id == '')
        $building_id = 0;
    ?>
    <div style="float:left;">

        <div style="width:330px; border-radius:5px; border:1px solid #CCCCCC; padding:5px; margin-right:5px; background-image:url(<?php echo URL ?>images/alarm_gray_icon_small.png); background-repeat:no-repeat;">
            <div style="width: 300px; float:left; text-decoration: underline; font-weight: bold; text-align: center; color: #666666; font-size: 20px;">BUILDING</div>
            <div style="border: 1px solid #cccccc; border-radius: 13px; float: right; height: 26px; width: 26px; line-height: 26px; text-align: center; font-size: 11px;">CHK</div>
            <div style="background-position: 0px 30px; font-size: 76px; font-weight: bold; color: #999999; float:left; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; background-position-y: 30px; padding-left: 15px;">73&deg;F</div>
            <div style="background-position: 3px 45px; float:left; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; margin-left:25px; font-size:52px; color: #999999; font-weight: bold; background-position-y: 45px; padding-left: 20px; padding-top: 26px;">25%</div>
            
            <div class="clear"></div>
            
            <div style="opacity: 0.4; background-position: 0px 15px; margin: 0 -5px; padding: 5px; border-top:1px solid #CCCCCC; background-image:url(<?php echo URL ?>images/alarm_gray_icon_small.png); background-repeat:no-repeat;">
                <div style="width: 40px; margin-left: 25px; background-size: 8px auto; background-position: 10px 7px; float: left; font-size: 18px; font-weight: bold; color: #999999;">HIGH</div>
                <div style="float:left; margin-left:10px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;">70&deg;F</div>
                <div style="float:left; margin-left:10px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;">25%</div>
                <div class="clear"></div>
                
                <div style="width: 40px; margin-left: 25px; float: left; font-size: 18px; font-weight: bold; color: #999999;">LOW</div>
                <div style="float:left; margin-left:10px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;">70&deg;F</div>
                <div style="float:left; margin-left:10px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;">25%</div>
                <div class="clear"></div>
            </div>
            
            <div class="clear"></div>
        </div>

        <div style="border:1px solid #CCCCCC; border-radius:5px;margin-top: 8px; width: 320px; margin-right: 5px; padding: 10px;">
            <div style="float:left; text-decoration:underline; font-size: 18px; margin-top: 18px;">EXTERNAL</div>
            <div style="font-weight: bold; background-position: 8px 15px; background-size: 13px auto; float:left; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 45px; color: #999999; background-position-y: 7px;">70&deg;F</div>
                    <div style="margin-top: 10px; font-weight: bold; background-position: 7px 11px; background-size: 15px auto; float:left; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 33px; color: #999999; background-position-y: 7px;">25%</div>
            <div class="clear"></div>
        </div>

    </div>

    <div style="float:left;">
        <div style="width:204px; border:1px solid #CCCCCC; border-radius: 5px; height: 219px;">
            <?php
            $strSQL = "Select * from t_room where building_id=$building_id";
            $strRsRoomsArr = $DB->Returns($strSQL);
            
            if (mysql_num_rows($strRsRoomsArr) > 0) { ?>
            
            <div style="border-radius:5px; margin-bottom: 8px;  padding: 5px;">

                <div style="margin-bottom:5px; float:left; text-align: center;"><span style="text-decoration: underline;">ROOM</span>&nbsp;
                    <div style="border: 1px solid #cccccc;  margin-bottom: 5px; border-radius: 13px; float: right; height: 26px; width: 26px; line-height: 26px; text-align: center; font-size: 11px;">OK</div>
                    
                        <select name="ddlBuildingRoomList" id="ddlBuildingRoomList" style="width: 190px; height:30px; font-size:15px; font-family: UsEnergyEngineers;">
                            <?php
                            while ($strRsRooms = mysql_fetch_object($strRsRoomsArr)) {
                                print '<option value="' . $strRsRooms->room_id . '">' . $strRsRooms->room_name . '</option>';
                            }
                            ?>                                    
                        </select>
                </div>

                <div style="float:left; height: 67px; margin-top: -4px;">
                    <div style="font-weight: bold; background-position: 8px 15px; background-size: 13px auto; float:left; margin-left: -6px; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 45px; color: #999999; background-position-y: 7px;">70&deg;F</div>
                    <div style="margin-top: 10px; font-weight: bold; background-position: 7px 11px; background-size: 15px auto; float:left; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 33px; color: #999999; background-position-y: 7px;">25%</div>
                    <div class="clear"></div>
                </div>

                <div class="clear"></div>

            </div>

            <div class="clear"></div>
            
            <div style="opacity: 0.4; background-position: 0px 15px; padding-top: 5px; border-top:1px solid #CCCCCC; background-image:url(<?php echo URL ?>images/alarm_gray_icon_small.png); background-repeat:no-repeat;">
                <div style="width: 40px; margin-left: 30px; float: left; font-size: 18px; font-weight: bold; color: #999999;">HIGH</div>
                <div style="float:left; margin-left:5px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;">70&deg;F</div>
                <div style="float:left; margin-left:10px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;">25%</div>
                <div class="clear"></div>
                
                <div style="width: 40px; margin-left: 30px; float: left; font-size: 18px; font-weight: bold; color: #999999;">LOW</div>
                <div style="float:left; margin-left:5px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/thermometer_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;">70&deg;F</div>
                <div style="float:left; margin-left:10px; background-size: 8px auto; background-position: 10px 7px; background-image:url(<?php echo URL ?>images/humidity_icon.png); background-repeat:no-repeat; padding-left:20px; font-size: 18px; color: #999999; background-position-y: 7px;">25%</div>
                <div class="clear"></div>
            </div>
            
            <?php } else { ?>
                No Room
            <?php } ?>

        </div>
        
        <div style="float:left; width: 204px; font-size: 16px; padding: 13px 1px">
            <div style="float:left; width: 160px; margin-left: 10px; color: #999999; font-size: 18px;">BUILDING NODES: </div><div style="float:left;">5</div>
            <div style="float:left; width: 160px; margin-left: 10px; color: #999999; font-size: 18px;">ROOM NODES: </div><div style="float:left;">16</div>
            <div style="float:left; width: 160px; margin-left: 10px; color: #999999; font-size: 18px;">EXTERNAL: </div><div style="float:left;">2</div>
        </div>

    </div>

    <div class="clear"></div>
<?php } elseif ($type == 2) { ?>

    <?php
    global $level1Arr;
    global $level2Arr;
    global $level3Arr;
    global $level4Arr;

    if (Globals::Get('graphtype') == '') {
        $strType = 1;
    } else {
        $strType = Globals::Get('graphtype');
    }

    function getParent($strChild) {
        global $level1Arr;
        global $level2Arr;
        global $level3Arr;
        global $level4Arr;

        $DB = new DB;
        $strSQL = "Select parent_id, system_name, system_id, level from t_system where system_id=$strChild ";
        $strRsGetParentIDArr = $DB->Returns($strSQL);
        while ($strRsGetParentID = mysql_fetch_object($strRsGetParentIDArr)) {
            if ($strRsGetParentID->level == 1) {
                if (is_array($level1Arr) && count($level1Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level1Arr)) {
                        $level1Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level1Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 2) {
                //$level2Arr[]=$strRsGetParentID->system_id;

                if (is_array($level2Arr) && count($level2Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level2Arr)) {
                        $level2Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level2Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 3) {
                // $level3Arr[]=$strRsGetParentID->system_id;
                if (is_array($level3Arr) && count($level3Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level3Arr)) {
                        $level3Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level3Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 4) {
                //$level4Arr[]=$strRsGetParentID->system_id;
                if (is_array($level4Arr) && count($level4Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level4Arr)) {
                        $level4Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level4Arr[] = $strRsGetParentID->system_id;
                }
            }

            getParent($strRsGetParentID->parent_id);
        }
    }

    $strSQL = "select Distinct system_id from t_system_node where building_id=$building_id";
    $strRsSystemsArr = $DB->Returns($strSQL);
    while ($strRsSystems = mysql_fetch_object($strRsSystemsArr)) {
        getParent($strRsSystems->system_id);
    }
    
    $start_date = gmdate('Y-m-1 00:00:00', strtotime("-2 Months"));
    $end_date = gmdate('Y-m-d H:i:s', strtotime(date("Y-m-d 23:59:59")));
    ?>

    <div style="  text-align: center;  font-size: 14px;  text-transform: uppercase;  font-weight: bold;"><?php echo ($strType == 1 ? "Electric" : "Natural Gas" ); ?> Consumption by System</div>

    <script type="text/javascript">
        //google.load("visualization", "1", {packages:["corechart"]});
        google.load("visualization", "1", {"packages": ["corechart"], "callback": drawChart});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['System', 'Consumption'],
                <?php
                if (is_array($level1Arr) && count($level1Arr) > 0) {
                    foreach ($level1Arr as $val1) {
                        $strSQL = "Select * from t_system where system_id=$val1 and display_type=$strType";
                        $strRsLevel1Arr = $DB->Returns($strSQL);
                        while ($strRsLevel1 = mysql_fetch_object($strRsLevel1Arr)) {

                            if (is_array($level2Arr) && count($level2Arr) > 0) {
                                foreach ($level2Arr as $val2) {
                                    $strSQL = "Select * from t_system where system_id=$val2 and parent_id=" . $strRsLevel1->system_id;
                                    $strRsLevel2Arr = $DB->Returns($strSQL);
                                    while ($strRsLevel2 = mysql_fetch_object($strRsLevel2Arr)) {
                                        if (is_array($level3Arr) && count($level3Arr) > 0) {
                                            foreach ($level3Arr as $val3) {
                                                $strSQL = "Select * from t_system where system_id=$val3 and parent_id=" . $strRsLevel2->system_id;
                                                $strRsLevel3Arr = $DB->Returns($strSQL);
                                                while ($strRsLevel3 = mysql_fetch_object($strRsLevel3Arr)) {
                                                    $strSQL="select custom_name, system_node_id, available_system_node_serial from t_system_node where system_id in (Select system_id from t_system where parent_id = $strRsLevel3->system_id)";
                                                    $strRsSystemsArr=$DB->Returns($strSQL);
                                                    
                                                    $meter_consumption = 0;
                                                    while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
                                                    {
                                                        if($strRsSystems->available_system_node_serial<>'')
                                                        {
                                                            $strSQL="Select max(kwhsystem)-min(kwhsystem) as kwh from `t_$strRsSystems->available_system_node_serial` where synctime >='$start_date' and synctime <='$end_date'";
                                                            $consumptionArr=$DB->Returns($strSQL);
                                                            while($consumption=mysql_fetch_object($consumptionArr))
                                                            {
                                                                $meter_consumption += floatval($consumption->kwh);
                                                            }
                                                        }

                                                    }
                                                    ?>
                                                        ['<?php echo $strRsLevel3->system_name . " (" . $strRsLevel2->system_name . ") " . ($strType == 1 ? "kWh" : "Therms" ) ?>', <?= round(($strType == 1 ? $meter_consumption : $meter_consumption/50 ), 2)?>],
                                                    <?php
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

            ]);

            /*var dataTable = new google.visualization.DataTable();
             dataTable.addColumn('string', 'System');
             dataTable.addColumn('number', 'Consumption');
                 
             dataTable.addColumn({type: 'string', role: 'tooltip'});
             dataTable.addRows([
             ['Compressed Air', 70,'70,000 kWh'],
             ['HVAC', 140, '140, 000 kWh'],
             ['process', 800, '$800K in 2012.'],
                 
             ]);*/


            var options = {
                title: '',
                pieHole: 0.4,
                width: 540,
                height: 240,
                colors: ['#00004d', '#000066', '#000080', '#000099', '#0000b3', '#000066', '#191975', '#323284', '#4c4c93', '#6666a3', '#7f7fb2', '#9999c1', '#b2b2d1', '#cccce0', '#e5e5ef'],
                chartArea: {width: '100%', left: 30, top: 30},
                tooltip: {text: 'both'},
                vAxis: {maxValue: 10},
                legend: {position: 'left', width: '100%'},
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
            //chart.draw(dataTable, options);
        }
    </script>

    <div id="donutchart"></div>

<?php } elseif ($type == 3) { ?>
    <?php
    global $level1Arr;
    global $level2Arr;
    global $level3Arr;
    global $level4Arr;

    if (Globals::Get('graphtype') == '') {
        $strType = 1;
    } else {
        $strType = Globals::Get('graphtype');
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
    
    function getParent($strChild) {
        global $level1Arr;
        global $level2Arr;
        global $level3Arr;
        global $level4Arr;

        $DB = new DB;
        $strSQL = "Select parent_id, system_name, system_id, level from t_system where system_id=$strChild ";
        $strRsGetParentIDArr = $DB->Returns($strSQL);
        while ($strRsGetParentID = mysql_fetch_object($strRsGetParentIDArr)) {
            if ($strRsGetParentID->level == 1) {
                if (is_array($level1Arr) && count($level1Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level1Arr)) {
                        $level1Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level1Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 2) {
                //$level2Arr[]=$strRsGetParentID->system_id;

                if (is_array($level2Arr) && count($level2Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level2Arr)) {
                        $level2Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level2Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 3) {
                // $level3Arr[]=$strRsGetParentID->system_id;
                if (is_array($level3Arr) && count($level3Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level3Arr)) {
                        $level3Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level3Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 4) {
                //$level4Arr[]=$strRsGetParentID->system_id;
                if (is_array($level4Arr) && count($level4Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level4Arr)) {
                        $level4Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level4Arr[] = $strRsGetParentID->system_id;
                }
            }

            getParent($strRsGetParentID->parent_id);
        }
    }

    $strSQL = "select Distinct system_id from t_system_node where building_id=$building_id";
    $strRsSystemsArr = $DB->Returns($strSQL);
    while ($strRsSystems = mysql_fetch_object($strRsSystemsArr)) {
        getParent($strRsSystems->system_id);
    }
    
    $start_date = gmdate('Y-m-1 00:00:00', strtotime("-2 Months"));
    $end_date = gmdate('Y-m-d H:i:s', strtotime(date("Y-m-d 23:59:59")));
    ?>

    <div style="  text-align: center;  font-size: 14px;  text-transform: uppercase;  font-weight: bold;"><?php echo ($strType == 1 ? "Electric" : "Natural Gas" ); ?> Cost by System</div>

    <script type="text/javascript">

        google.load("visualization", "1", {"packages": ["corechart"], "callback": drawChart});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['System', 'Consumption'],
                <?php
                if (is_array($level1Arr) && count($level1Arr) > 0) {
                    foreach ($level1Arr as $val1) {
                        $strSQL = "Select * from t_system where system_id=$val1 and display_type=$strType";
                        $strRsLevel1Arr = $DB->Returns($strSQL);
                        while ($strRsLevel1 = mysql_fetch_object($strRsLevel1Arr)) {

                            if (is_array($level2Arr) && count($level2Arr) > 0) {
                                foreach ($level2Arr as $val2) {
                                    $strSQL = "Select * from t_system where system_id=$val2 and parent_id=" . $strRsLevel1->system_id;
                                    $strRsLevel2Arr = $DB->Returns($strSQL);
                                    while ($strRsLevel2 = mysql_fetch_object($strRsLevel2Arr)) {
                                        if (is_array($level3Arr) && count($level3Arr) > 0) {
                                            foreach ($level3Arr as $val3) {
                                                $strSQL = "Select * from t_system where system_id=$val3 and parent_id=" . $strRsLevel2->system_id;
                                                $strRsLevel3Arr = $DB->Returns($strSQL);
                                                while ($strRsLevel3 = mysql_fetch_object($strRsLevel3Arr)) {
                                                    $strSQL="select custom_name, system_node_id, available_system_node_serial from t_system_node where system_id in (Select system_id from t_system where parent_id = $strRsLevel3->system_id)";
                                                    $strRsSystemsArr=$DB->Returns($strSQL);
                                                    
                                                    $meter_consumption = 0;
                                                    while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
                                                    {
                                                        if($strRsSystems->available_system_node_serial<>'')
                                                        {
                                                            $strSQL="Select max(kwhsystem)-min(kwhsystem) as kwh from `t_$strRsSystems->available_system_node_serial` where synctime >='$start_date' and synctime <='$end_date'";
                                                            $consumptionArr=$DB->Returns($strSQL);
                                                            while($consumption=mysql_fetch_object($consumptionArr))
                                                            {
                                                                $meter_consumption += floatval($consumption->kwh);
                                                            }
                                                        }

                                                    }
                                                    ?>
                                                                ['<?php echo $strRsLevel3->system_name . " (" . $strRsLevel2->system_name . ")" . ($strType == 1 ? "" : "" ) ?>', <?= round(($strType == 1 ? $meter_consumption*$cost : $meter_consumption*$cost/50 ), 2)?>],
                                                    <?php
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

            ]);

            var options = {
                title: '',
                pieHole: 0.4,
                width: 540,
                height: 240,
                colors: ['#00004d', '#000066', '#000080', '#000099', '#0000b3', '#000066', '#191975', '#323284', '#4c4c93', '#6666a3', '#7f7fb2', '#9999c1', '#b2b2d1', '#cccce0', '#e5e5ef'],
                chartArea: {width: '100%', left: 30, top: 30},
                tooltip: {text: 'both'},
                vAxis: {maxValue: 10},
                legend: {position: 'left', width: '100%'},
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
            //chart.draw(dataTable, options);
        }
    </script>

    <div id="donutchart"></div>

<?php } elseif ($type == 4) { ?>
    <?php
    global $level1Arr;
    global $level2Arr;
    global $level3Arr;
    global $level4Arr;

    if (Globals::Get('graphtype') == '') {
        $strType = 1;
    } else {
        $strType = Globals::Get('graphtype');
    }

    function getParent($strChild) {
        global $level1Arr;
        global $level2Arr;
        global $level3Arr;
        global $level4Arr;

        $DB = new DB;
        $strSQL = "Select parent_id, system_name, system_id, level from t_system where system_id=$strChild ";
        $strRsGetParentIDArr = $DB->Returns($strSQL);
        while ($strRsGetParentID = mysql_fetch_object($strRsGetParentIDArr)) {
            if ($strRsGetParentID->level == 1) {
                if (is_array($level1Arr) && count($level1Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level1Arr)) {
                        $level1Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level1Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 2) {
                //$level2Arr[]=$strRsGetParentID->system_id;

                if (is_array($level2Arr) && count($level2Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level2Arr)) {
                        $level2Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level2Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 3) {
                // $level3Arr[]=$strRsGetParentID->system_id;
                if (is_array($level3Arr) && count($level3Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level3Arr)) {
                        $level3Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level3Arr[] = $strRsGetParentID->system_id;
                }
            } elseif ($strRsGetParentID->level == 4) {
                //$level4Arr[]=$strRsGetParentID->system_id;
                if (is_array($level4Arr) && count($level4Arr) > 0) {
                    if (!in_array($strRsGetParentID->system_id, $level4Arr)) {
                        $level4Arr[] = $strRsGetParentID->system_id;
                    }
                } else {
                    $level4Arr[] = $strRsGetParentID->system_id;
                }
            }

            getParent($strRsGetParentID->parent_id);
        }
    }

    $strSQL = "select Distinct system_id from t_system_node where building_id=$building_id";
    $strRsSystemsArr = $DB->Returns($strSQL);
    while ($strRsSystems = mysql_fetch_object($strRsSystemsArr)) {
        getParent($strRsSystems->system_id);
    }
    ?>
    <div style="  text-align: center;  font-size: 14px;  text-transform: uppercase;  font-weight: bold;"><?php echo ($strType == 1 ? "Electric" : "Natural Gas" ); ?> Savings by System</div>
    <script type="text/javascript">

        google.load("visualization", "1", {"packages": ["corechart"], "callback": drawChart});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['System', 'Consumption'],
                <?php
                if (is_array($level1Arr) && count($level1Arr) > 0) {
                    foreach ($level1Arr as $val1) {
                        $strSQL = "Select * from t_system where system_id=$val1 and display_type=$strType";
                        $strRsLevel1Arr = $DB->Returns($strSQL);
                        while ($strRsLevel1 = mysql_fetch_object($strRsLevel1Arr)) {

                            if (is_array($level2Arr) && count($level2Arr) > 0) {
                                foreach ($level2Arr as $val2) {
                                    $strSQL = "Select * from t_system where system_id=$val2 and parent_id=" . $strRsLevel1->system_id;
                                    $strRsLevel2Arr = $DB->Returns($strSQL);
                                    while ($strRsLevel2 = mysql_fetch_object($strRsLevel2Arr)) {
                                        if (is_array($level3Arr) && count($level3Arr) > 0) {
                                            foreach ($level3Arr as $val3) {
                                                $strSQL = "Select * from t_system where system_id=$val3 and parent_id=" . $strRsLevel2->system_id;
                                                $strRsLevel3Arr = $DB->Returns($strSQL);
                                                while ($strRsLevel3 = mysql_fetch_object($strRsLevel3Arr)) {
                                                    ?>
                                                                ['<?php echo $strRsLevel3->system_name . " (" . $strRsLevel2->system_name . ") - ($100,000) " . ($strType == 1 ? "" : "" ) ?>', 670.56],
                                                    <?php
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
            ]);

            var options = {
                title: '',
                pieHole: 0.4,
                width: 540,
                height: 240,
                colors: ['#00004d', '#000066', '#000080', '#000099', '#0000b3', '#000066', '#191975', '#323284', '#4c4c93', '#6666a3', '#7f7fb2', '#9999c1', '#b2b2d1', '#cccce0', '#e5e5ef'],
                chartArea: {width: '100%', left: 30, top: 30},
                tooltip: {text: 'both'},
                vAxis: {maxValue: 10},
                legend: {position: 'left', width: '100%'},
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
            //chart.draw(dataTable, options);
        }
    </script>
    <div id="donutchart"></div>
<?php } ?>