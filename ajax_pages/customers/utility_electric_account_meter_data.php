<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

if(isset($_POST) && !empty($_POST)){
    $meter_id = $_POST['meter_id'];
    $year = $_POST['year'];

    $id = $_POST['id'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    $consumption = $_POST['consumption'];
    $cost = $_POST['cost'];
    $cost = str_replace("$", "", $cost);
    $cost = str_replace(" ", "", $cost);
    $cost = str_replace(",", "", $cost);
    
    if($id != ''){
        $strSQL="update t_utility_bills set `from` = '$from', `to` = '$to', consumption = '$consumption', cost = '$cost' where utility_bill_id = '$id'";
    }else{
        $strSQL="insert into t_utility_bills (`utility_meter_id`, `year`, `from`, `to`, `consumption`, `cost`, `doc`) values ('$meter_id', '$year', '$from', '$to', '$consumption', '$cost', now())";
    }
    $strMeterArr=$DB->Returns($strSQL);
    echo "done";
    exit;
}

$meter_id = $_GET['meter_id'];
$year = $_GET['year'];

$strSQL="Select * from t_utility_account_meters where utility_meter_number = '$meter_id'";
$strMeterArr=$DB->Returns($strSQL);

$strSQL="SELECT utility_bill_id, CAST(SUBSTRING_INDEX(`from`,'/',1) as UNSIGNED) as month, `from`, `to`, consumption, cost FROM t_utility_bills WHERE utility_meter_id = '$meter_id' AND year = '$year' order by month asc";
$strElectricAccountArr=$DB->Returns($strSQL);

$utitlity_electric_consumption_total = 0;
$utitlity_electric_cost_total = 0;

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0"  >
    <tr style="background-color:#000000; font-weight:bold; color:#FFFFFF;">
        <td colspan="2" align="center" valign="middle" style="border:1px solid #CCCCCC;">Dates</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">Consumption</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">Billed Cost</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">Cost/kWh</td>
        <td rowspan="2" align="center" valign="middle" style="background-color:#FFFFFF; border:1px solid #FFFFFF;;">&nbsp;</td>
        <td rowspan="2" align="center" valign="middle" style="background-color:#FFFFFF; border:1px solid #FFFFFF;;">&nbsp;</td>
    </tr>
    <tr style="background-color:#EFEFEF; font-weight:bold;">
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">From</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">To</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">(kWh)</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">($)</td>
        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">($/kWh)</td>
    </tr>
    
    <?php if($meter_id != ''){
        for($i=1; $i<=12; $i++) { 
            if($strElectricAccount=mysql_fetch_object($strElectricAccountArr)){
                $utitlity_electric_consumption_total += $strElectricAccount->consumption;
                $utitlity_electric_cost_total += $strElectricAccount->cost; ?>
                <tr>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="from_<?=$strElectricAccount->utility_bill_id;?>"><?=$strElectricAccount->from?></td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="to_<?=$strElectricAccount->utility_bill_id;?>"><?=$strElectricAccount->to?></td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="consumption_<?=$strElectricAccount->utility_bill_id;?>"><?=number_format($strElectricAccount->consumption, 0);?></td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="cost_<?=$strElectricAccount->utility_bill_id;?>">$<?=number_format($strElectricAccount->cost, 0);?></td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="avg_<?=$strElectricAccount->utility_bill_id;?>">$<?=number_format($strElectricAccount->cost/$strElectricAccount->consumption, 2);?></td>
                    <td align="center" valign="middle"><div id="edit_<?=$strElectricAccount->utility_bill_id;?>"><div style="float:left; cursor:pointer; width:42px; margin:0 0 0 5px; font-size:12px; padding:0px 3px; background-color:#CCCCCC; border-radius:3px;" onclick="editBillData('<?=$strElectricAccount->utility_bill_id;?>');">Edit</div></div> <div id="update_<?=$strElectricAccount->utility_bill_id;?>" style="display: none;"><div style="float:left; cursor:pointer; width:42px; margin:0 0 0 5px; font-size:12px; padding:0px 3px; background-color:#CCCCCC; border-radius:3px;" onclick="updateBillData('<?=$strElectricAccount->utility_bill_id;?>');">Update</div><a href="javascript:void(0);" onclick="cancelBillData('<?=$strElectricAccount->utility_bill_id;?>');">X</a></div></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="from_n<?=$i;d?>">--</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="to_n<?=$i;?>">--</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="consumption_n<?=$i;?>">0</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="cost_n<?=$i;?>">$0</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;" id="avg_n<?=$i;?>">$0.00</td>
                    <td align="center" valign="middle"><div id="edit_n<?=$i;?>"><div style="float:left; cursor:pointer; width:42px; margin:0 0 0 5px; font-size:12px; padding:0px 3px; background-color:#CCCCCC; border-radius:3px;" onclick="editNewBillData('n<?=$i;?>');">Edit</div></div> <div id="update_n<?=$i;?>" style="display: none;"><div style="float:left; cursor:pointer; width:42px; margin:0 0 0 5px; font-size:12px; padding:0px 3px; background-color:#CCCCCC; border-radius:3px;" onclick="addBillData('n<?=$i;?>');">Update</div><a href="javascript:void(0);" onclick="cancelNewBillData('n<?=$i;?>');">X</a></div></td>
                </tr>
    <?php   }
        } ?>
        <tr style="font-weight:bold;">
            <td colspan="2" align="center" valign="middle">&nbsp;</td>
            <td align="center" valign="middle" style="border:2px solid #000000;"><?=number_format($utitlity_electric_consumption_total,0)?></td>
            <td align="center" valign="middle" style="border:2px solid #000000;">$<?=number_format($utitlity_electric_cost_total,0)?></td>
            <td align="center" valign="middle" style="border:2px solid #000000;">$<?=number_format($utitlity_electric_cost_total/$utitlity_electric_consumption_total,2)?></td>
            <td align="center" valign="middle" >&nbsp;</td>
        </tr> 
    <?php } else { ?>
        <tr>
            <td colspan="5" align="center" valign="middle" style="border:1px solid #CCCCCC;">No meter found</td>
        </tr>
    <?php } ?>                        
</table>
<script>
    $('#utility_meter_number').html("");
</script>
<?php
while($strMeter=mysql_fetch_object($strMeterArr)) {
?>    
    <script>
        $('#utility_meter_number').html("<?=$strMeter->meter_number?>");
    </script>
<?php
}
?>