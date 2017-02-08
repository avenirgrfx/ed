<?php

ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');
$DB = new DB;

$current_building_id = $_GET['building_id'];
$month = isset($_GET['month']) ? $_GET['month'] : "";
$year = isset($_GET['year']) ? $_GET['year'] : "";

$strSQL="Select time_zone, daylight_saving from t_building where building_id=".$current_building_id;
$strTime_zoneArr=$DB->Returns($strSQL);
while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
    $current_bilding_time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
}
date_default_timezone_set($current_bilding_time_zone);

if ($year != "" && $month != "") {
    $start_date = date("$year-01-01 00:00:00");
    $end_date = date("$year-12-31 23:59:59");
} else {
    $start_date = date("Y-01-01 00:00:00");
    $end_date = date("Y-12-31 23:59:59");
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

$strSQL = "select Distinct building_id from t_building where site_id in (select site_id from t_sites where client_id = (select client_id from t_building where building_id=$current_building_id))";
$strRsClientArr = $DB->Returns($strSQL);

$corporate_e_total = $corporat_g_total = $corporat_e_baseline = $corporat_g_baseline = $corporat_emm = 0;

while ($strRsClient = mysql_fetch_object($strRsClientArr)) {
    $building_id = $strRsClient->building_id;
    $level1Arr = ""; 
    $level2Arr = "";
    $level3Arr = "";
    $level4Arr = "";
    
    $strSQL="Select time_zone, daylight_saving from t_building where building_id=".$building_id;
    $strTime_zoneArr=$DB->Returns($strSQL);
    while($strTime_zone=mysql_fetch_object($strTime_zoneArr)){
        $time_zone = Globals::GetTimezoneCode($strTime_zone->time_zone);
    }
    date_default_timezone_set($time_zone);

    //*********************  Date & Time conversion to UTC  ****************************//

    $start_date = gmdate('Y-m-d H:i:s', strtotime($start_date));
    $end_date = gmdate('Y-m-d H:i:s', strtotime($end_date));
    
    
    $strSQL = "select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id";
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

                                    $strSQL = "Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=" . $strRsLevel2->system_id;
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

                                                    $strSQL = "Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=" . $strRsLevel4->system_id;
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

                                    $strSQL = "Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=" . $strRsLevel2->system_id;
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

                                                    $strSQL = "Select * from t_system_node where delete_flag=0 and building_id=$building_id and system_id=" . $strRsLevel4->system_id;
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

    $strSQL="Select * from t_mv_baseline where building_id=".$building_id;
    $baselineArr=$DB->Returns($strSQL);

    $baseline=mysql_fetch_object($baselineArr);
    $e_baseline = $baseline->e_mon + $baseline->e_tue + $baseline->e_wed + $baseline->e_thu + $baseline->e_fri + $baseline->e_sat + $baseline->e_sun;
    $g_baseline = $baseline->g_mon + $baseline->g_tue + $baseline->g_wed + $baseline->g_thu + $baseline->g_fri + $baseline->g_sat + $baseline->g_sun;

    $e_baseline = $e_baseline*365/7;
    $g_baseline = $g_baseline*365/7;
    
    $emm = ($e_total+$g_total)*$co2;
    //echo "building_id=$building_id current_building_id=$current_building_id ";
    if($building_id == $current_building_id){
        $current_e_total = $e_total;
        $current_g_total = $g_total;
        
        $current_e_baseline = $e_baseline;
        $current_g_baseline = $g_baseline;
        
        $current_emm = $emm;
    }
    
    $corporate_e_total += $e_total;
    $corporat_g_total += $g_total;

    $corporat_e_baseline += $e_baseline;
    $corporat_g_baseline += $g_baseline;

    $corporat_emm += $emm;
}

date_default_timezone_set($current_bilding_time_zone);
?>

<div style="float:left; margin-left:0px; text-align:center;">
    Site <?=date('Y', strtotime($start_date))?>
    <div class="green_bubble"><?=number_format(($current_e_total+$current_g_total)*100/($current_e_baseline+$current_g_baseline),0)?>%</div>
</div>
<div style="float:left; margin-left:20px; text-align:center;">
    Corporate <?=date('Y', strtotime($start_date))?>
    <div class="green_bubble" style="margin-left:15px;"><?=number_format(($corporate_e_total+$corporat_g_total)*100/($corporat_e_baseline+$corporat_g_baseline),0)?>%</div>
</div>

<div style="float:left; margin-left:35px; width:245px; margin-top:10px;">
    <div class="energy_benchmark_boxes">
        <div style="float:left;">Site <?=date('Y', strtotime($start_date))?></div> <div style="float:right;"><strong><?= $current_emm>4000?number_format($current_emm/2000,1)." TONS":number_format($current_emm,0)." LBS";?> CO2</strong></div>
        <div class="clear"></div>
    </div>

    <div class="energy_benchmark_boxes" style="margin-top:3px;">
        <div style="float:left;">Corporate <?=date('Y', strtotime($start_date))?></div> <div style="float:right;"><strong><?= $corporat_emm>4000?number_format($corporat_emm/2000,1)." TONS":number_format($corporat_emm,0)." LBS";?> CO2</strong></div>
        <div class="clear"></div>
    </div>
</div>
<div class="clear"></div>