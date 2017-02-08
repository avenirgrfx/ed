<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;
$user_id=$_SESSION['user_login']->login_id;
if($_POST)
{
	
	$client_id=$_POST['ClientID'];
	$amount=$_POST['Amount'];
	$note=$_POST['Note'];
	$active_fromArr=explode("/",$_POST['FromDate']);
	$active_from=$active_fromArr[2]."-".$active_fromArr[0]."-".$active_fromArr[1];
		
	$active_toArr=explode("/",$_POST['ToDate']);
	$active_to=$active_toArr[2]."-".$active_toArr[0]."-".$active_toArr[1];
	
	$created_by=$user_id;
	
	$strSQL="Insert into t_package_credit(client_id, amount, note, active_from, active_to, created_by, doc)
	Values($client_id, $amount, '$note', '$active_from', '$active_to', $created_by, now())";
	$DB->Execute($strSQL);
	
	Globals::SendURL(URL."?type=package_credit");
	
	exit();
}


$strSQL="Select * from t_client where delete_flag=0 order by client_name";
$strRsClientsArr=$DB->Returns($strSQL);

?>

<script type="text/javascript">
$(document).ready(function(){
	$( "#txtFromDate").datepicker();
	$( "#txtToDate").datepicker();
	
	$('#btnSubmit').click(function(){
		var ClientID=$('#ddlClientList').val();
		var Amount=$('#txtAmount').val();
		var FromDate=$('#txtFromDate').val();
		var ToDate=$('#txtToDate').val();
		var Note=$('#txtNote').val();
		
		$.post('<?php echo URL?>ajax_pages/add_new_package_credit.php',{ClientID:ClientID, Amount:Amount, FromDate:FromDate, ToDate:ToDate, Note:Note },function(data){
			$('#Show_Package_Credit_List').html(data);
		});
		
	});
	
});

</script>
<div style="font-size:16px; font-weight:bold;">Add Package Credit</div>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td>
    <select name="ddlClientList" id="ddlClientList">
    	<option value="">Select Client</option>
        <?php while($strRsClients=mysql_fetch_object($strRsClientsArr)){?>
        	<option value="<?php echo $strRsClients->client_id;?>"><?php echo $strRsClients->client_name;?></option>
        <?php }?>
    </select>    </td>
    <td><input type="text" name="txtAmount" id="txtAmount" value="" placeholder="Credit Amount" /></td>
    <td><input type="text" name="txtFromDate" id="txtFromDate" value="" placeholder="Valid From" /></td>
    <td><input type="text" name="txtToDate" id="txtToDate" value="" placeholder="Valid To" /></td>
    <td><input type="text" name="txtNote" id="txtNote" value="" placeholder="Note" /></td>
    <td><input type="button" name="btnSubmit" id="btnSubmit" value="Submit"></td>
  </tr>
</table>


<style type="text/css">
#Show_Package_Credit_List table tr td
{
	border:1px solid #CCCCCC;
	font-size:12px;
}
</style>
<br><br>
<div style="font-size:16px; font-weight:bold;">Existing Package Credits</div>

<div id="Show_Package_Credit_List">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
  <tr style="font-weight:bold; background-color:#EFEFEF;">
    <td width="17%">Client Name</td>
    <td width="6%">Amount</td>
    <td width="8%">Valid From</td>
    <td width="12%">Valid To</td>
    <td width="8%">Balance</td>
    <td width="33%">Note</td>
    <td width="16%">Created By</td>
  </tr>


	
<?php
	$strSQL="Select t_package_credit.*, t_client.client_name from t_package_credit,t_client where t_package_credit.client_id=t_client.client_id order by client_name";
	$strRsClientDetailsArr=$DB->Returns($strSQL);
	while($strRsClientDetails=mysql_fetch_object($strRsClientDetailsArr))
	{
	?>
    	<tr>
        <td><?php echo $strRsClientDetails->client_name;?></td>
        <td>$<?php echo number_format($strRsClientDetails->amount,0);?></td>
        <td><?php echo Globals::DateFormat($strRsClientDetails->active_from);?></td>
        <td><?php echo Globals::DateFormat($strRsClientDetails->active_to);?></td>
        <td><a href="#">$<?php echo number_format($strRsClientDetails->amount,0);?></a></td>
        <td><?php echo $strRsClientDetails->note;?></td>
        <td>Admin on <?php echo Globals::DateFormat($strRsClientDetails->doc);?></td>
      </tr>
    <?php
	}
?>
</table>
    
</div>
