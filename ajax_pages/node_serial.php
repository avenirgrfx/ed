<?php

require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/system.class.php');
require_once(AbsPath . 'classes/gallery.class.php');
$DB = new DB;
//$prefix = $_GET['prefix'];
if ($_GET["mode"] == "prefix") {
    $parent_id = $_GET['parent_id'];
    $strSystemID = $_GET['strSystemID'];
    $name = $_GET['name'];

    $string = split(" ", $name);
    $length = count($string);
    for ($i = 0; $i <= $length; $i++) {
        $prefix = strtoupper($prefix . $string[$i][0]);
    }
    // echo $prefix;
    $strSQL = "select * from t_system where prefix = '" . $prefix . "' order by system_id";
    $node_serial = $DB->Returns($strSQL);
    $chr = 'A';
    while ($node_serialArr = mysql_fetch_object($node_serial)) {
        $prefixArr[$node_serialArr->system_id] = $chr;
        $chr = chr(ord($chr) + 4);
    }
    $strSQL = "select * from t_system where parent_id = " . $parent_id . " order by system_id";
    $node_serial = $DB->Returns($strSQL);
    $secondchr = 'A';
    $count = 0;
    while ($node_serialArr = mysql_fetch_object($node_serial)) {
        
        $secondprefixArr[$node_serialArr->system_id] = $secondchr;
        $secondchr = chr(ord($secondchr) + 1);
        $count++;
    }

//echo $prefixArr[$parent_id].$secondchr;
//print_r($prefixArr);

    $strSQL = "Select count(1) as SerialNumbers from t_system_node where system_id=$strSystemID and year_of_creation=" . date("Y");
    // THN150001A to THN159999A then THN150001B and so on
    $strRsNodeSerialArr = $DB->Returns($strSQL);
    //$parent_id=$_GET['parent_parent_id'];

    if ($strRsNodeSerial = mysql_fetch_object($strRsNodeSerialArr)) {
        $AvailableCount = $strRsNodeSerial->SerialNumbers;
        $AvailableCount++;

        if ($AvailableCount >= 0 and $AvailableCount < 10)
            $SerialNumber = '000' . $AvailableCount . "A";
        elseif ($AvailableCount >= 10 and $AvailableCount < 100)
            $SerialNumber = '00' . $AvailableCount . "A";
        elseif ($AvailableCount >= 100 and $AvailableCount < 1000)
            $SerialNumber = '0' . $AvailableCount . "A";
        elseif ($AvailableCount >= 1000 and $AvailableCount < 10000)
            $SerialNumber = $AvailableCount . "A";
    }

    if (!empty($prefixArr[$parent_id])) {
        echo $prefix . $prefixArr[$parent_id] . $secondprefixArr[$strSystemID] . $SerialNumber = $strRsSystems->prefix . $x . date("y") . $SerialNumber;
    } else {
        echo $prefix . 'A' . $secondprefixArr[$strSystemID] . $SerialNumber = $strRsSystems->prefix . $x . date("y") . $SerialNumber;
    }
} else if ($_GET['mode'] == "delete_entire_node") {
    $parent_id = $_GET['parent_id'];
    $strNodeID = $_GET['strNodeID'];
    $strSQL = "select count(1) as count from t_system_node where delete_flag = 0 and system_id=" . $strNodeID;
    $count_of_node = $DB->Returns($strSQL);
    while ($count_of_nodeArr = mysql_fetch_object($count_of_node)) {
        $count = $count_of_nodeArr->count;
    }
    if ($count == 0) {
        $strSQL = "delete from t_system_node where system_id=" . $strNodeID;
        $DB->Execute($strSQL);
        
        $strSQL = "delete from t_system where system_id=" . $strNodeID;
        $delete_flag_set = $DB->Execute($strSQL);
        echo "deleted sucessfully";
    } else {
        echo "Node is not Empty, delete sub nodes first";
    }
}
?>