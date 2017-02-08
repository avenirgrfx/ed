<?php

ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');
$DB = new DB;

$building_id = $_GET['building_id'];
$month = isset($_GET['month']) ? $_GET['month'] : "";
$year = isset($_GET['year']) ? $_GET['year'] : "";

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$building_id;
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($time_zone);

if ($year != "" && $month != "") {
    $start_date = date("$year-$month-01 00:00:00");
    $end_date = date_create("last day of $start_date")->format('Y-m-d 23:59:59');
} else {
    $start_date = date("Y-m-01 00:00:00");
    $end_date = date("Y-m-d 23:59:59");
}


//*********************  Date & Time conversion to UTC  ****************************//

$start_date = gmdate('Y-m-d H:i:s', strtotime($start_date));
$end_date = gmdate('Y-m-d H:i:s', strtotime($end_date));


global $level1Arr;
global $level2Arr;
global $level3Arr;
global $level4Arr;

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

if (is_array($level1Arr) && count($level1Arr) > 0) {
    $totalKwh____ = 0;
    foreach ($level1Arr as $val1) {
        $strSQL = "Select * from t_system where system_id=$val1 and display_type=1";
        $strRsLevel1Arr = $DB->Returns($strSQL);
        while ($strRsLevel1 = mysql_fetch_object($strRsLevel1Arr)) {
            if (is_array($level2Arr) && count($level2Arr) > 0) {
                foreach ($level2Arr as $val2) {
                    $strSQL = "Select * from t_system where system_id=$val2 and parent_id=" . $strRsLevel1->system_id;
                    $strRsLevel2Arr = $DB->Returns($strSQL);
                    while ($strRsLevel2 = mysql_fetch_object($strRsLevel2Arr)) {
                        if (is_array($level3Arr) && count($level3Arr) > 0) {
                            $totalKwh___ = 0;
                            foreach ($level3Arr as $val3) {

                                $strSQL = "Select * from t_system where system_id=$val3 and parent_id=" . $strRsLevel2->system_id;
                                $strRsLevel3Arr = $DB->Returns($strSQL);
                                while ($strRsLevel3 = mysql_fetch_object($strRsLevel3Arr)) {
                                    $DataVal = 0;

                                    if (is_array($level4Arr) && count($level4Arr) > 0) {
                                        $totalKwh__ = 0;
                                        foreach ($level4Arr as $val4) {
                                            $strSQL = "Select * from t_system where system_id=$val4 and parent_id=" . $strRsLevel3->system_id;
                                            $strRsLevel4Arr = $DB->Returns($strSQL);
                                            while ($strRsLevel4 = mysql_fetch_object($strRsLevel4Arr)) {
                                                $DataVal = 0;

                                                $strSQL = "Select * from t_system_node where building_id=$building_id and system_id=" . $strRsLevel4->system_id;
                                                $strRsSystemNodesArr = $DB->Returns($strSQL);

                                                $totalKwh_ = 0;
                                                while ($strRsSystemNodes = mysql_fetch_object($strRsSystemNodesArr)) {
                                                    ##########################################
                                                    # Calculating Kwh
                                                    ##########################################

                                                    $DataVal = 0;
                                                    if ($strRsSystemNodes->available_system_node_serial <> '') {
                                                        $DataVal = Globals::MarginalValueCalcBySystem("t_" . $strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date, $end_date);
                                                        $totalKwh_ = $totalKwh_ + floatval($DataVal);
                                                    }
                                                    ?>

                                                    <?php

                                                }
                                                $totalKwh__ = $totalKwh__ + $totalKwh_;
                                            }
                                        }
                                        $totalKwh___ = $totalKwh___ + $totalKwh__;
                                    }
                                    ?>

                                    <?php

                                }
                            }
                            $totalKwh____ = $totalKwh____ + $totalKwh___;
                        }
                    }
                }
            }
        }
    }
}

$e_total = $totalKwh____;

if (is_array($level1Arr) && count($level1Arr) > 0) {
    $totalKwh____ = 0;
    foreach ($level1Arr as $val1) {
        $strSQL = "Select * from t_system where system_id=$val1 and display_type=2";
        $strRsLevel1Arr = $DB->Returns($strSQL);
        while ($strRsLevel1 = mysql_fetch_object($strRsLevel1Arr)) {
            if (is_array($level2Arr) && count($level2Arr) > 0) {
                foreach ($level2Arr as $val2) {
                    $strSQL = "Select * from t_system where system_id=$val2 and parent_id=" . $strRsLevel1->system_id;
                    $strRsLevel2Arr = $DB->Returns($strSQL);
                    while ($strRsLevel2 = mysql_fetch_object($strRsLevel2Arr)) {
                        if (is_array($level3Arr) && count($level3Arr) > 0) {
                            $totalKwh___ = 0;
                            foreach ($level3Arr as $val3) {

                                $strSQL = "Select * from t_system where system_id=$val3 and parent_id=" . $strRsLevel2->system_id;
                                $strRsLevel3Arr = $DB->Returns($strSQL);
                                while ($strRsLevel3 = mysql_fetch_object($strRsLevel3Arr)) {
                                    $DataVal = 0;

                                    if (is_array($level4Arr) && count($level4Arr) > 0) {
                                        $totalKwh__ = 0;
                                        foreach ($level4Arr as $val4) {
                                            $strSQL = "Select * from t_system where system_id=$val4 and parent_id=" . $strRsLevel3->system_id;
                                            $strRsLevel4Arr = $DB->Returns($strSQL);
                                            while ($strRsLevel4 = mysql_fetch_object($strRsLevel4Arr)) {
                                                $DataVal = 0;

                                                $strSQL = "Select * from t_system_node where building_id=$building_id and system_id=" . $strRsLevel4->system_id;
                                                $strRsSystemNodesArr = $DB->Returns($strSQL);

                                                $totalKwh_ = 0;
                                                while ($strRsSystemNodes = mysql_fetch_object($strRsSystemNodesArr)) {
                                                    ##########################################
                                                    # Calculating Kwh
                                                    ##########################################

                                                    $DataVal = 0;
                                                    if ($strRsSystemNodes->available_system_node_serial <> '') {
                                                        $DataVal = Globals::MarginalValueCalcBySystem("t_" . $strRsSystemNodes->available_system_node_serial, "kwhsystem", $start_date, $end_date);
                                                        $totalKwh_ = $totalKwh_ + floatval($DataVal);
                                                    }
                                                    ?>

                                                    <?php

                                                }
                                                $totalKwh__ = $totalKwh__ + $totalKwh_;
                                            }
                                        }
                                        $totalKwh___ = $totalKwh___ + $totalKwh__;
                                    }
                                    ?>

                                    <?php

                                }
                            }
                            $totalKwh____ = $totalKwh____ + $totalKwh___;
                        }
                    }
                }
            }
        }
    }
}

$g_total = $totalKwh____;

$strSQL = "Select * from t_energy_cost where building_id=" . $building_id;
$costArr = $DB->Returns($strSQL);
$cost = mysql_fetch_object($costArr);
$energy_cost = $cost->energy_cost ? $cost->energy_cost : 0;
$gas_cost = $cost->gas_cost ? $cost->gas_cost : 0;
?>

<div style="width:235px;float:left; border:1px solid #ccc; overflow: hidden; border-radius:10px 0 0 10px;">
<script type="text/javascript">
    google.load("visualization", "1", {"packages": ["corechart"], "callback": drawChart});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['System', 'Consumption'],
          ['Electricity MMBTU', <?=round($e_total/293.071107,3)?>],
          ['Natural Gas MMBTU', <?=round($g_total/293.071107,3)?>]
        ]);

        var options = {
            title: '',
            pieHole: 0.25,
            width: 235,
            height: 120,
            colors: ['#2F7ED8','#85B3E8'],
            chartArea: {width: '100%', left:10, top:30},		 
            tooltip: { text: 'both' },
            vAxis: {maxValue: 10},
            legend:{position:'left', width:'100%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchartleft'));
        chart.draw(data, options);
    }
</script>
<div style="text-align: center; color:#666666;font-size:16px;">Energy Consumption by fuel</div>
<div id="donutchartleft"></div>
<div style="text-align: center; color:#666666;font-size:12px;">Total Energy for <?=date('F',strtotime($start_date))?> - <?=number_format(($e_total+$g_total)/293.071107,1)?> MMBTU</div>
</div>
<div style="width:230px;float:left; border:1px solid #ccc; border-left:none; overflow: hidden; border-radius:0 10px 10px 0;">
<script type="text/javascript">
    google.load("visualization", "1", {"packages": ["corechart"], "callback": drawChart});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['System', 'Consumption'],
          ['Electricity ($)', <?=round($e_total*$energy_cost,3)?>],
          ['Natural Gas ($)', <?=round($g_total*$gas_cost/50,3)?>]
        ]);

        var options = {
            title: '',
            pieHole: 0.25,
            width: 235,
            height: 120,
            colors: ['#2F7ED8','#85B3E8'],
            chartArea: {width: '100%', left:10, top:30},		 
            tooltip: { text: 'both' },
            vAxis: {maxValue: 10},
            legend:{position:'left', width:'100%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchartright'));
        chart.draw(data, options);
    }
</script>
<div style="text-align: center; color:#666666;font-size:16px;">Energy Cost by fuel</div>
<div id="donutchartright"></div>
<div style="text-align: center; color:#666666;font-size:12px;">Total Cost for <?=date('F',strtotime($start_date))?> - $<?=number_format($e_total*$energy_cost+$g_total*$gas_cost/50,0)?></div>
</div>