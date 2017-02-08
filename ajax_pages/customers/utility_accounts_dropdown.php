<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

$building_id = $_GET['building_id'];
$year = $_GET['year'];

//$strSQL="Select * from t_utility_accounts as A inner join t_utility_account_meters as M on A.utility_account_id = M.utility_account_id where building_id = '$building_id' AND utility_account_type = 1 AND utility_meter_number in (Select utility_meter_id from t_utility_bills where year = '$year' group by utility_meter_id)";
$strSQL="Select * from t_utility_accounts as A where building_id = '$building_id' AND utility_account_type = 1";
$strElectricAccountArr=$DB->Returns($strSQL);

//$strSQL="Select * from t_utility_accounts as A inner join t_utility_account_meters as M on A.utility_account_id = M.utility_account_id where building_id = '$building_id' AND utility_account_type = 2 AND utility_meter_number in (Select utility_meter_id from t_utility_bills where year='$year' group by utility_meter_id)";
$strSQL="Select * from t_utility_accounts as A where building_id = '$building_id' AND utility_account_type = 2";
$strGasAccountArr=$DB->Returns($strSQL);
?>

<div style="float:left; width:170px;">&nbsp;</div>

<div style="float:left; width:170px; margin-left:10px;">
    <div style="float:left;">
        <select id="ddlEnergyAccount" style="font-weight:bold; width: 130px; font-family: UsEnergyEngineers;">
            <?php $i=1; while($strElectricAccount=mysql_fetch_object($strElectricAccountArr)) { ?>
                <option value="<?=$strElectricAccount->utility_account_id?>">ACCOUNT <?=$i?></option>
            <?php $i++; } ?>
        </select>
    </div>
    <div style="float:left; margin-left:5px; background-color:#CCCCCC; padding:0px 5px; border-radius:15px;"><?=mysql_num_rows($strElectricAccountArr);?></div>
    <div class="clear"></div>
</div>

<div style="float:left; width:170px; margin-left:10px;">
    <div style="float:left;">
        <select id="ddlGasAccount" style="font-weight:bold; width: 130px; font-family: UsEnergyEngineers;">
            <?php $i=1; while($strGasAccount=mysql_fetch_object($strGasAccountArr)) { ?>
                <option value="<?=$strGasAccount->utility_account_id?>">ACCOUNT <?=$i?></option>
            <?php $i++; } ?>
        </select>
    </div>
    <div style="float:left; margin-left:5px; background-color:#CCCCCC; padding:0px 5px; border-radius:15px;"><?=mysql_num_rows($strGasAccountArr);?></div>
    <div class="clear"></div>
</div>

<script>
    $('#ddlEnergyAccount').change(function(){
        //if($('#ddlEnergyAccount').val()){
            $('#electric_utility_data').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/utility_electric_account_data.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        account_id: $('#ddlEnergyAccount').val(),
                        year: $('#ddlBillingSummary2').val(),
                    },
            function (data, status) {
                $('#electric_utility_data').html(data);
            });
            
            $('#combind_utility_data').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/utility_combined_account_data.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        electric_account_id: $('#ddlEnergyAccount').val(),
                        gas_account_id: $('#ddlGasAccount').val(),
                        year: $('#ddlBillingSummary2').val(),
                    },
            function (data, status) {
                $('#combind_utility_data').html(data);
            });
        //}
    });
    
    $('#ddlGasAccount').change(function(){
        //if($('#ddlGasAccount').val()){
            $('#gas_utility_data').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/utility_gas_account_data.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        account_id: $('#ddlGasAccount').val(),
                        year: $('#ddlBillingSummary2').val(),
                    },
            function (data, status) {
                $('#gas_utility_data').html(data);
            });
            
            $('#combind_utility_data').html('Loading...');
            $.get("<?php echo URL ?>ajax_pages/customers/utility_combined_account_data.php",
                    {
                        building_id: $('#ddlBuildingForSite').val(),
                        electric_account_id: $('#ddlEnergyAccount').val(),
                        gas_account_id: $('#ddlGasAccount').val(),
                        year: $('#ddlBillingSummary2').val(),
                    },
            function (data, status) {
                $('#combind_utility_data').html(data);
            });
        //}
    });
    
    $('#ddlEnergyAccount').trigger('change');
    $('#ddlGasAccount').trigger('change');
</script>