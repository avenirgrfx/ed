<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
$DB=new DB;

$building_id = Globals::Get('building_id');

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

$strSQL = "select Distinct system_id from t_system_node where delete_flag=0 and building_id=$building_id";
$strRsSystemsArr = $DB->Returns($strSQL);
while ($strRsSystems = mysql_fetch_object($strRsSystemsArr)) {
    getParent($strRsSystems->system_id);
}

print "<option value=''>ALL</option>";

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
                                $strSQL = "Select * from t_system where system_id=$val3 and exclude_in_calculation=0 and parent_id=" . $strRsLevel2->system_id;
                                $strRsLevel3Arr = $DB->Returns($strSQL);
                                while ($strRsLevel3 = mysql_fetch_object($strRsLevel3Arr)) {
                                    print "<option value='".$strRsLevel3->system_id."'>".$strRsLevel3->system_name."</option>";
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}