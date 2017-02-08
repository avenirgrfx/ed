<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

$building_id = $_GET['building_id'];
$account_id = $_GET['account_id'];
$year = $_GET['year'];

$strSQL="SELECT CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, min(`from`) as date, `to`, sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.utility_account_id = '$account_id' AND utility_account_type = 2 AND year = '$year' group by month order by month asc";
$strGasAccountArr=$DB->Returns($strSQL);

$utitlity_gas_consumption_total = 0;
$utitlity_gas_cost_total = 0;

if(mysql_num_rows($strGasAccountArr)>0) {
?>

<div style="font-size:11px; line-height:20px;">
    <div>NAT. GAS THERMS: <span id="utitlity_gas_consumption_total">0</span></div>
    <div>NAT. GAS COSTS: $<span id="utitlity_gas_cost_total">0</span></div>
    <div>AVERAGE COST/THERM: $<span id="utitlity_gas_avg_total">0.00</span></div>
</div>

<div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">THERMS</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/THERM</td>
        </tr>
        <?php while($strGasAccount=mysql_fetch_object($strGasAccountArr)) {  
            $utitlity_gas_consumption_total += $strGasAccount->consumption;
            $utitlity_gas_cost_total += $strGasAccount->cost;
        ?>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=$strGasAccount->date?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($strGasAccount->consumption/50, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($strGasAccount->cost, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($strGasAccount->cost/($strElectricAccount->consumption/50), 2);?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<script>
$('#utitlity_gas_consumption_total').html("<?=number_format($utitlity_gas_consumption_total/50,0)?>");
$('#utitlity_gas_cost_total').html("<?=number_format($utitlity_gas_cost_total,0)?>");
$('#utitlity_gas_avg_total').html("<?=number_format($utitlity_gas_cost_total/($utitlity_gas_consumption_total/50),2)?>");
</script>

<?php } else {?>
<div style="font-size:11px; line-height:20px;">
    <div>NAT. GAS THERMS: 0</div>
    <div>NAT. GAS COSTS: $0</div>
    <div>AVERAGE COST/THERM: $0.00</div>
</div>

<div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">THERMS</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/THERM</td>
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