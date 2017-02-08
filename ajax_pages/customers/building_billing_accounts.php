<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

$building_id = $_GET['building_id'];
$year = $_GET['year'];
$account_type = $_GET['account_type'];

//$strSQL="Select * from t_utility_accounts as A inner join t_utility_account_meters as M on A.utility_account_id = M.utility_account_id where building_id = '$building_id' AND utility_account_type = '$account_type' AND utility_meter_number in (Select utility_meter_id from t_utility_bills where year = '$year' group by utility_meter_id)";
$strSQL="Select * from t_utility_accounts where building_id = '$building_id' AND utility_account_type = '$account_type'";
$strAccountArr=$DB->Returns($strSQL);

$i=1; 
while($strAccount=mysql_fetch_object($strAccountArr)) { 
?>
    <option value="<?=$strAccount->utility_account_id?>">ACCOUNT <?=$i?></option>
<?php 
    $i++;
} ?>