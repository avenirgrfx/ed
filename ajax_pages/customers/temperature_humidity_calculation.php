<?php
require_once('../../configure.php');
require_once(AbsPath . 'classes/all.php');

$DB = new DB;
$type = Globals::Get('type');
$building_id = Globals::Get('building_id');
$t2=0;
$strSQL="SELECT available_system_node_serial,count(available_system_node_serial) as newcount FROM `t_system_node` where building_id = $building_id and delete_flag = 0";
$resultArr =  $DB->Returns($strSQL);
while ($result = mysql_fetch_object($resultArr)) {
  //echo  $result->available_system_node_serial;
       $strSQL="select temperature from t_$result->available_system_node_serial order by created_date desc limit 1 ";
       $resultArr =  $DB->Returns($strSQL);
       $t1 = $resultArr->temperature;
       $t2 = $t2 + $t1;
}
echo $t2/$resultArr->newcount;