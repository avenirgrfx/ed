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

$strSQL="SELECT CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, min(`from`) as date, `to`, sum(consumption) as consumption, sum(cost) as cost FROM t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id WHERE A.utility_account_id = '$account_id' AND utility_account_type = 1 AND year = '$year' group by month order by month asc";
$strElectricAccountArr=$DB->Returns($strSQL);
$utitlity_electric_consumption_total = 0;
$utitlity_electric_cost_total = 0;

if(mysql_num_rows($strElectricAccountArr)>0) {
?>

<div style="font-size:11px; line-height:20px;">
    <div>ELECTRIC KWH: <span id="utitlity_electric_consumption_total">0</span></div>
    <div>ELECTRIC COSTS: $<span id="utitlity_electric_cost_total">0</span></div>
    <div>AVERAGE COST/KWH: $<span id="utitlity_electric_avg_total">0</span></div>
</div>

<div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">KWH</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/KWH</td>
        </tr>

        <?php while($strElectricAccount=mysql_fetch_object($strElectricAccountArr)) { 
            $utitlity_electric_consumption_total += $strElectricAccount->consumption;
            $utitlity_electric_cost_total += $strElectricAccount->cost;
        ?>
        <tr>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=$strElectricAccount->date?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($strElectricAccount->consumption, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($strElectricAccount->cost, 0);?></td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($strElectricAccount->cost/$strElectricAccount->consumption, 2);?></td>
        </tr>
        <?php } ?>
        
    </table>
</div>

<script>
$('#utitlity_electric_consumption_total').html("<?=number_format($utitlity_electric_consumption_total,0)?>");
$('#utitlity_electric_cost_total').html("<?=number_format($utitlity_electric_cost_total,0)?>");
$('#utitlity_electric_avg_total').html("<?=number_format($utitlity_electric_cost_total/$utitlity_electric_consumption_total,2)?>");
</script>

<?php } else {?>
<div style="font-size:11px; line-height:20px;">
    <div>ELECTRIC KWH: 0</div>
    <div>ELECTRIC COSTS: $0</div>
    <div>AVERAGE COST/KWH: $0.00</div>
</div>

<div style="font-size:10px; margin-top:10px; border:1px solid #666666;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
        <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MBTU</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
            <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MBTU</td>
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