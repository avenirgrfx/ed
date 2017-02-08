<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

$account_id = $_GET['account_id'];

$strSQL="Select * from t_utility_accounts where utility_account_id = '$account_id'";
$strAccountArr=$DB->Returns($strSQL);

$strSQL="Select * from t_utility_account_meters where utility_account_id = '$account_id'";
$strMeterArr=$DB->Returns($strSQL);

$i=1; 
while($strMeter=mysql_fetch_object($strMeterArr)) {
?>
    <option value="<?=$strMeter->utility_meter_number?>">METER <?=$i?></option>
<?php 
    $i++;
} 
?>
<script>
    $('#utility_name').html("");
    $('#utility_account_number').html("");
</script>    
<?php
while($strAccount=mysql_fetch_object($strAccountArr)) {
?>    
    <script>
        $('#utility_name').html("<?=$strAccount->utility_name?>");
        $('#utility_account_number').html("<?=$strAccount->utility_account_number?>");
    </script>
<?php
}
?>