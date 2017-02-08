<?php
ob_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$strSQL="select node_serial, custom_name as node_name, description from t_system_node where delete_flag=0 and node_serial <> ''";
//echo $strSQL;
$nodesArr = $DB->Lists(array("Query"=>$strSQL));

if(sizeof($nodesArr) > 0){
    echo json_encode($nodesArr);
}else{
    echo json_encode(array("error" => "no data available"));
}

exit;