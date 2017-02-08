<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath . "classes/all.php");
require_once(AbsPath . "classes/customer.class.php");

$DB=new DB;

$meter_id = $_GET['meter_id'];
if($meter_id=="" or $meter_id==0)
	exit();

$portfolio_meter_id = "";

$strSQL="Select M.*, A.utility_account_type from t_utility_account_meters M inner join t_utility_accounts A on M.utility_account_id = A.utility_account_id where M.utility_meter_number = '$meter_id'";
$strMeterArr=$DB->Returns($strSQL); 
while($strMeter=mysql_fetch_object($strMeterArr)) { 
    $meter_ID = $strMeter->utility_meter_number;
    $portfolio_meter_id = $strMeter->meter_id;
    ?>
    <tr>
        <td>Meter ID:</td>
        <td><?=$strMeter->utility_meter_number;?></td>
    </tr>
    <tr>
        <td>Meter Type:</td>
        <td><?=$strMeter->utility_account_type=="1"?"Electric":($strMeter->utility_account_type=="2"?"Natural Gas":($strMeter->utility_account_type=="3"?"Water":""));?></td>
    </tr>
    <tr>
        <td>Meter Number:</td>
        <td><?=$strMeter->meter_number;?></td>
    </tr>
    <tr>
        <td>Meter Unit of Measure:</td>
        <td>kBtu (Thousand Btu)</td>
    </tr>
    <tr>
        <td>Portfolio Manager Status:</td>
        <td><?=$portfolio_meter_id!=""?"Added":"Not Added"?></td>
    </tr>
    
<?php } 

$strSQL="Select min(STR_TO_DATE(`from`,'%m/%d/%Y')) as start, max(STR_TO_DATE(`to`,'%m/%d/%Y')) as end, sum(consumption) as consumption from t_utility_bills where utility_meter_id = '$meter_id'";

$strBillArr=$DB->Returns($strSQL);
while($strBill=mysql_fetch_object($strBillArr)) { ?>
    <tr>
        <td>First Bill Date:</td>
        <td><?=$strBill->start;?></td>
    </tr>
    <tr>
        <td>In Use:</td>
        <td>Yes</td>
    </tr>
<!--    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>-->
    <tr>
        <td colspan="2" style="text-decoration: underline;">Latest Meter Consumption Data</td>
    </tr>
    <tr>
        <td>Meter Usage:</td>
        <td><?=number_format($strBill->consumption);?> KWH</td>
    </tr>
    <tr>
        <td>Start Date:</td>
        <td><?=$strBill->start;?></td>
    </tr>
    <tr>
        <td>End Date:</td>
        <td><?=$strBill->end;?></td>
    </tr>
<?php } 

if($portfolio_meter_id != "") { ?>
    <tr>
        <td colspan="2">
            <div style="border: 1px solid #999999; border-radius: 5px; float:right; margin-right: 10px; margin-top: -3px;">
                <input type="button" name="button"  value="Add Consumption data to Portfolio Manager" style="border: 0 none; width:100%" onclick="addDataPM('<?=$meter_ID?>')">
            </div>
        </td>
    </tr>
<?php } else { ?>
    <tr>
        <td colspan="2">
            <div style="border: 1px solid #999999; border-radius: 5px; float:right; margin-right: 10px; margin-top: -3px;">
                <input type="button" name="button"  value="Add Meter to Portfolio Manager" style="border: 0 none; width:100%" onclick="addMeterPM('<?=$meter_ID?>')">
            </div>
        </td>
    </tr>
<?php } ?>
