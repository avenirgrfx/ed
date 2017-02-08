
<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$macid = $_GET['macid'];
$DB=new DB;

$strSQL="delete from t_router where router_macid = '".$macid."'";
$result_id = $DB->Execute($strSQL);
echo "Device deleted";
?>

