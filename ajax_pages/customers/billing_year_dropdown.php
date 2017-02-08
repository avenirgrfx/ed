<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$building_id = $_GET['building_id'];
if($building_id=="" or $building_id==0)
	exit();

$strSQL="SELECT distinct year FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE building_id = '$building_id' group by year order by year desc";
$strBillingYearArr=$DB->Returns($strSQL);
while($strBillingYear=mysql_fetch_object($strBillingYearArr)) { ?>
    <option value="<?=$strBillingYear->year?>"><?=$strBillingYear->year?></option>
<?php } ?>