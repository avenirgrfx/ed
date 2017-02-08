<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

$building_id = $_GET['building_id'];
$electric_account_id = $_GET['electric_account_id'];
$gas_account_id = $_GET['gas_account_id'];
$year = $_GET['year'];

$strSQL="SELECT CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, min(`from`) as date, `to`, sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.utility_account_id = '$electric_account_id' AND utility_account_type = 1 AND year = '$year' group by month order by month asc";
$strElectricAccountArr=$DB->Returns($strSQL);

$strSQL="SELECT CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, min(`from`) as date, `to`, sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.utility_account_id = '$gas_account_id' AND utility_account_type = 2 AND year = '$year' group by month order by month asc";
$strGasAccountArr=$DB->Returns($strSQL);

$utitlity_combined_consumption_total = 0;
$utitlity_combined_cost_total = 0;
$electric_days = $gas_days = $days = 0;

if(mysql_num_rows($strElectricAccountArr)>0 && mysql_num_rows($strGasAccountArr)>0) { ?>

<div style="font-size:11px; line-height:20px; margin-top: -20px">
    <div>COMBINED ENERGY: <span id="utitlity_combined_consumption_total">0</span> MMBTU</div>
    <div>COMBINED COSTS: $<span id="utitlity_combined_cost_total">0</span></div>
    <div>ELECTRIC ANALYSIS DAYS: <span id="utitlity_combined_days_electric">0</span></div>
    <div>NAT. GAS ANALYSIS DAYS: <span id="utitlity_combined_days_gas">0</span></div> 
</div>

<div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MMBTU</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MMBTU</td>
        </tr>

        <?php while($strElectricAccount=mysql_fetch_object($strElectricAccountArr)) { 
            $from_day_array = explode("/", $strElectricAccount->date);
            $to_day_array = explode("/", $strElectricAccount->to);
            $electric_days += intval($to_day_array[1])-intval($from_day_array[1])+1;
            
            $strGasAccount=mysql_fetch_object($strGasAccountArr);
            
            $from_day_array = explode("/", $strGasAccount->date);
            $to_day_array = explode("/", $strGasAccount->to);
            $gas_days += intval($to_day_array[1])-intval($from_day_array[1])+1;
            
            $utitlity_combined_consumption_total += ($strElectricAccount->consumption + $strGasAccount->consumption);
            $utitlity_combined_cost_total += ($strElectricAccount->cost + $strGasAccount->cost);
        ?>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=$strElectricAccount->date?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format(($strElectricAccount->consumption + $strGasAccount->consumption)/293.071107, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format(($strElectricAccount->cost + $strGasAccount->cost), 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format(($strElectricAccount->cost + $strGasAccount->cost)/(($strElectricAccount->consumption + $strGasAccount->consumption)/293.071107), 2);?></td>
        </tr>
        <?php } ?>
        
    </table>
</div>

<script>
$('#utitlity_combined_consumption_total').html("<?=number_format($utitlity_combined_consumption_total/293.071107,0)?>");
$('#utitlity_combined_cost_total').html("<?=number_format($utitlity_combined_cost_total,0)?>");
$('#utitlity_combined_days_electric').html("<?=$electric_days?>");
$('#utitlity_combined_days_gas').html("<?=$gas_days?>");
</script>

<?php } else if(mysql_num_rows($strElectricAccountArr)>0) { ?>

<div style="font-size:11px; line-height:20px; margin-top: -20px">
    <div>COMBINED ENERGY: <span id="utitlity_combined_consumption_total">0</span> MMBTU</div>
    <div>COMBINED COSTS: $<span id="utitlity_combined_cost_total">0</span></div>
    <div>ELECTRIC ANALYSIS DAYS: <span id="utitlity_combined_days">0</span></div>
    <div>NAT. GAS ANALYSIS DAYS: 0</div>    
</div> 

<div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MMBTU</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MMBTU</td>
        </tr>

        <?php while($strElectricAccount=mysql_fetch_object($strElectricAccountArr)) { 
            $from_day_array = explode("/", $strElectricAccount->date);
            $to_day_array = explode("/", $strElectricAccount->to);
            $days += intval($to_day_array[1])-intval($from_day_array[1])+1;
            
            $utitlity_combined_consumption_total += $strElectricAccount->consumption;
            $utitlity_combined_cost_total += $strElectricAccount->cost;
        ?>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=$strElectricAccount->date?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($strElectricAccount->consumption/293.071107, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($strElectricAccount->cost, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($strElectricAccount->cost/($strElectricAccount->consumption/293.071107), 2);?></td>
        </tr>
        <?php } ?>
        
    </table>
</div>

<script>
$('#utitlity_combined_consumption_total').html("<?=number_format($utitlity_combined_consumption_total/293.071107,0)?>");
$('#utitlity_combined_cost_total').html("<?=number_format($utitlity_combined_cost_total,0)?>");
$('#utitlity_combined_days').html("<?=$days?>");
</script>

<?php } else if(mysql_num_rows($strGasAccountArr)>0) { ?>

<div style="font-size:11px; line-height:20px; margin-top: -20px">
    <div>COMBINED ENERGY: <span id="utitlity_combined_consumption_total">0</span> MMBTU</div>
    <div>COMBINED COSTS: $<span id="utitlity_combined_cost_total">0</span></div>
    <div>ELECTRIC ANALYSIS DAYS: 0</div>
    <div>NAT. GAS ANALYSIS DAYS: <span id="utitlity_combined_days">0</span></div>    
</div> 

<div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MMBTU</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MMBTU</td>
        </tr>

        <?php while($strGasAccount=mysql_fetch_object($strGasAccountArr)) { 
            $from_day_array = explode("/", $strGasAccount->date);
            $to_day_array = explode("/", $strGasAccount->to);
            $days += intval($to_day_array[1])-intval($from_day_array[1])+1;
            
            $utitlity_combined_consumption_total += $strGasAccount->consumption;
            $utitlity_combined_cost_total += $strGasAccount->cost;
        ?>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=$strGasAccount->date?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($strGasAccount->consumption/293.071107, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($strGasAccount->cost, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($strGasAccount->cost/($strGasAccount->consumption/293.071107), 2);?></td>
        </tr>
        <?php } ?>
        
    </table>
</div>

<script>
$('#utitlity_combined_consumption_total').html("<?=number_format($utitlity_combined_consumption_total/293.071107,0)?>");
$('#utitlity_combined_cost_total').html("<?=number_format($utitlity_combined_cost_total,0)?>");
$('#utitlity_combined_days').html("<?=$days?>");
</script>

<?php } else {?>

<div style="font-size:11px; line-height:20px; margin-top: -20px">
    <div>COMBINED ENERGY: 0 MMBTU</div>
    <div>COMBINED COSTS: $0</div>
    <div>ELECTRIC ANALYSIS DAYS: 0</div>
    <div>NAT. GAS ANALYSIS DAYS: 0</div>
</div> 
<div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MMBTU</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MMBTU</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">-</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$0.00</td>
        </tr>

    </table>
</div>

<?php } ?>