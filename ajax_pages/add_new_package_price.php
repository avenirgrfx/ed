<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;
$user_id=$_SESSION['user_login']->login_id;

$edit_id=0;
if($_GET['mode']=='del')
{
	$id=$_GET['id'];
	$strSQL="Delete from t_package_price_settings where package_price_id=$id";
	$DB->Execute($strSQL);
}
elseif($_GET['mode']=='edit')
{
	$edit_id=$_GET['id'];
}

if($_POST)
{
	
	$Manufacturing_Cost_Simple=$_POST['Manufacturing_Cost_Simple'];
	$Manufacturing_Cost_Complex=$_POST['Manufacturing_Cost_Complex'];
	$Manufacturing_Cost_Specialized=$_POST['Manufacturing_Cost_Specialized'];
	$Subscription_Fee_Simple=$_POST['Subscription_Fee_Simple'];
	$Subscription_Fee_Complex=$_POST['Subscription_Fee_Complex'];
	$Subscription_Fee_Specialized=$_POST['Subscription_Fee_Specialized'];
	$Sales_Price_Simple=$_POST['Sales_Price_Simple'];
	$Sales_Price_Complex=$_POST['Sales_Price_Complex'];
	$Sales_Price_Specialized=$_POST['Sales_Price_Specialized'];
	$Sales_Price_Margin=$_POST['Sales_Price_Margin'];
	
	/*$strSQL="Delete from t_package_price_settings";
	$DB->Execute($strSQL);*/
	
	$strSQL="Insert into t_package_price_settings(
	Package_Price_Name,
	Manufacturing_Cost_Simple,
	Manufacturing_Cost_Complex,
	Manufacturing_Cost_Specialized,
	Subscription_Fee_Simple,
	Subscription_Fee_Complex,
	Subscription_Fee_Specialized,
	Sales_Price_Simple,
	Sales_Price_Complex,
	Sales_Price_Specialized,
	Sales_Price_Margin,
	doc,
	created_by)
	
	Values(
	'$Package_Price_Name',
	$Manufacturing_Cost_Simple,
	$Manufacturing_Cost_Complex,
	$Manufacturing_Cost_Specialized,
	$Subscription_Fee_Simple,
	$Subscription_Fee_Complex,
	$Subscription_Fee_Specialized,
	$Sales_Price_Simple,
	$Sales_Price_Complex,
	$Sales_Price_Specialized,
	$Sales_Price_Margin,
	now(),
	$user_id)";
	$DB->Execute($strSQL);
	print "Updated";
	exit();
}


?>

<style type="text/css">
table  tr td
{
	border:1px solid #CCCCCC;
	padding:3px;
	color:#666666;
}

</style>

<script type="text/javascript">

function DeletePackage(strPackageID)
{
	if(!confirm("Are you sure you want to Delete?"))
		return false;
		
	$('#Category_Container').html("Loading...");
	$.get('<?php echo URL?>ajax_pages/add_new_package_price.php',
	{
		id:strPackageID,
		mode:'del',
	},
		
	function(data){
		$('#Category_Container').html(data);
	});
}

function UsePackage(strPackageID)
{
		
	$('#Category_Container').html("Loading...");
	$.get('<?php echo URL?>ajax_pages/add_new_package_price.php',
	{
		id:strPackageID,
		mode:'edit',
	},
		
	function(data){
		$('#Category_Container').html(data);
	});
}



function CalculatePrice()
{
	var Manfuacturing_Simple=$('#txtSimpleManufacturingCost').val();
	var Manfuacturing_Complex=$('#txtComplexManufacturingCost').val();
	var Manfuacturing_Specialized=$('#txtSpecializedManufacturingCost').val();
	
	var Subscription_Simple=$('#txtSimpleSubscriptionFee').val();
	var Subscription_Complex=$('#txtComplexSubscriptionFee').val();
	var Subscription_Specialized=$('#txtSpecializedSubscriptionFee').val();
	
	var SalesMargin=$('#txtSalesMargin').val();
	
	
	if(Manfuacturing_Simple=="")
		Manfuacturing_Simple=0;
	else
		Manfuacturing_Simple=parseFloat(Manfuacturing_Simple);
	
	if(Manfuacturing_Complex=="")
		Manfuacturing_Complex=0;
	else
		Manfuacturing_Complex=parseFloat(Manfuacturing_Complex);
	
	if(Manfuacturing_Specialized=="")
		Manfuacturing_Specialized=0;
	else
		Manfuacturing_Specialized=parseFloat(Manfuacturing_Specialized);
	
	if(Subscription_Simple=="")
		Subscription_Simple=0;
	else
		Subscription_Simple=parseFloat(Subscription_Simple);
	
	if(Subscription_Complex=="")
		Subscription_Complex=0;
	else
		Subscription_Complex=parseFloat(Subscription_Complex);
	
	if(Subscription_Specialized=="")
		Subscription_Specialized=0;
	else
		Subscription_Specialized=parseFloat(Subscription_Specialized);
	
	if(SalesMargin=="")
		SalesMargin=0;
	else
		SalesMargin=parseFloat(SalesMargin);
	
		
	var SalesPrice_Simple=(Manfuacturing_Simple)+(Manfuacturing_Simple*SalesMargin/100)+Subscription_Simple;
	var SalesPrice_Complex=(Manfuacturing_Complex)+(Manfuacturing_Complex*SalesMargin/100)+Subscription_Complex;
	var SalesPrice_Specialized=(Manfuacturing_Specialized)+(Manfuacturing_Specialized*SalesMargin/100)+Subscription_Specialized;
	
	$('#txtSimpleSalesPrice').val(SalesPrice_Simple);
	$('#txtComplexSalesPrice').val(SalesPrice_Complex);
	$('#txtSpecializedSalesPrice').val(SalesPrice_Specialized);
}


$(function(){
	$('#btnUpdate').click(function(){
		
		var Manfuacturing_Simple=$('#txtSimpleManufacturingCost').val();
		var Manfuacturing_Complex=$('#txtComplexManufacturingCost').val();
		var Manfuacturing_Specialized=$('#txtSpecializedManufacturingCost').val();
		
		var Subscription_Simple=$('#txtSimpleSubscriptionFee').val();
		var Subscription_Complex=$('#txtComplexSubscriptionFee').val();
		var Subscription_Specialized=$('#txtSpecializedSubscriptionFee').val();
		
		var SalesMargin=$('#txtSalesMargin').val();
		
		if(Manfuacturing_Simple=="")
			Manfuacturing_Simple=0;
		else
			Manfuacturing_Simple=parseFloat(Manfuacturing_Simple);
		
		if(Manfuacturing_Complex=="")
			Manfuacturing_Complex=0;
		else
			Manfuacturing_Complex=parseFloat(Manfuacturing_Complex);
		
		if(Manfuacturing_Specialized=="")
			Manfuacturing_Specialized=0;
		else
			Manfuacturing_Specialized=parseFloat(Manfuacturing_Specialized);
		
		if(Subscription_Simple=="")
			Subscription_Simple=0;
		else
			Subscription_Simple=parseFloat(Subscription_Simple);
		
		if(Subscription_Complex=="")
			Subscription_Complex=0;
		else
			Subscription_Complex=parseFloat(Subscription_Complex);
		
		if(Subscription_Specialized=="")
			Subscription_Specialized=0;
		else
			Subscription_Specialized=parseFloat(Subscription_Specialized);
		
		if(SalesMargin=="")
			SalesMargin=0;
		else
			SalesMargin=parseFloat(SalesMargin);
		
		
		var Sales_Price_Simple=$('#txtSimpleSalesPrice').val();
		var Sales_Price_Complex=$('#txtComplexSalesPrice').val();
		var Sales_Price_Specialized=$('#txtSpecializedSalesPrice').val();
		var Package_Price_Name=$('#txtPackagePriceName').val();
			
		$.post('<?php echo URL?>ajax_pages/add_new_package_price.php',
			{
			Package_Price_Name:Package_Price_Name,
			Manufacturing_Cost_Simple:Manfuacturing_Simple,
			Manufacturing_Cost_Complex:Manfuacturing_Complex,
			Manufacturing_Cost_Specialized:Manfuacturing_Specialized,
			Subscription_Fee_Simple:Subscription_Simple,
			Subscription_Fee_Complex:Subscription_Complex,
			Subscription_Fee_Specialized:Subscription_Specialized,
			Sales_Price_Simple:Sales_Price_Simple,
			Sales_Price_Complex:Sales_Price_Complex,
			Sales_Price_Specialized:Sales_Price_Specialized,
			Sales_Price_Margin:SalesMargin
			},
			
			function(data){
				alert(data);
			});
		
	});
});

</script>

<?php
	
	if($edit_id==0)
		$strSQL="Select * from t_package_price_settings order by package_price_id desc LIMIT 1";
	else
		$strSQL="Select * from t_package_price_settings where package_price_id=$edit_id order by package_price_id desc LIMIT 1";
		
	$strRsPackagePriceArr=$DB->Returns($strSQL);
	if($strRsPackagePrice=mysql_fetch_array($strRsPackagePriceArr))
	{
		$Package_Price_Name=$strRsPackagePrice['Package_Price_Name'];
		$Manufacturing_Cost_Simple=$strRsPackagePrice['Manufacturing_Cost_Simple'];
		$Manufacturing_Cost_Complex=$strRsPackagePrice['Manufacturing_Cost_Complex'];
		$Manufacturing_Cost_Specialized=$strRsPackagePrice['Manufacturing_Cost_Specialized'];
		$Subscription_Fee_Simple=$strRsPackagePrice['Subscription_Fee_Simple'];
		$Subscription_Fee_Complex=$strRsPackagePrice['Subscription_Fee_Complex'];
		$Subscription_Fee_Specialized=$strRsPackagePrice['Subscription_Fee_Specialized'];
		$Sales_Price_Simple=$strRsPackagePrice['Sales_Price_Simple'];
		$Sales_Price_Complex=$strRsPackagePrice['Sales_Price_Complex'];
		$Sales_Price_Specialized=$strRsPackagePrice['Sales_Price_Specialized'];
		$Sales_Price_Margin=$strRsPackagePrice['Sales_Price_Margin'];
		$doc=$strRsPackagePrice['doc'];
		
	}
?>

<div style="font-size:16px; margin:5px 0px; font-weight:bold;">Package Price Manager (Last Updated on <?php echo Globals::DateFormat($doc);?> by Admin)</div>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td><input name="txtPackagePriceName" type="text" id="txtPackagePriceName" value="<?php echo $Package_Price_Name;?>" /></td>
    <td><strong>Simple</strong></td>
    <td><strong>Complex</strong></td>
    <td><strong>Specialized</strong></td>
  </tr>
  <tr>
    <td><strong>Manufacturing Cost/Unit</strong></td>
    <td><input name="txtSimpleManufacturingCost" type="text" id="txtSimpleManufacturingCost" onblur="CalculatePrice()" value="<?php echo $Manufacturing_Cost_Simple;?>"></td>
    <td><input name="txtComplexManufacturingCost" type="text" id="txtComplexManufacturingCost"  onblur="CalculatePrice()" value="<?php echo $Manufacturing_Cost_Complex;?>"></td>
    <td><input name="txtSpecializedManufacturingCost" type="text" id="txtSpecializedManufacturingCost" onblur="CalculatePrice()" value="<?php echo $Manufacturing_Cost_Specialized;?>"></td>
  </tr>
  <tr>
    <td><strong>Subsciption Fee/Unit</strong></td>
    <td><input name="txtSimpleSubscriptionFee" type="text" id="txtSimpleSubscriptionFee" onblur="CalculatePrice()" value="<?php echo $Subscription_Fee_Simple;?>"></td>
    <td><input name="txtComplexSubscriptionFee" type="text" id="txtComplexSubscriptionFee" onblur="CalculatePrice()" value="<?php echo $Subscription_Fee_Complex;?>"></td>
    <td><input name="txtSpecializedSubscriptionFee" type="text" id="txtSpecializedSubscriptionFee" onblur="CalculatePrice()" value="<?php echo $Subscription_Fee_Specialized;?>"></td>
  </tr>
  <tr>
    <td><strong>SalesMargin</strong>      <input name="txtSalesMargin" type="text" id="txtSalesMargin" onblur="CalculatePrice()" value="<?php echo $Sales_Price_Margin;?>" style="width:70px;">
%</td>
    <td><input type="text" name="txtSimpleSalesPrice" id="txtSimpleSalesPrice" readonly="readonly" value="<?php echo $Sales_Price_Simple;?>"></td>
    <td><input type="text" name="txtComplexSalesPrice" id="txtComplexSalesPrice" readonly="readonly" value="<?php echo $Sales_Price_Complex;?>"></td>
    <td><input type="text" name="txtSpecializedSalesPrice" id="txtSpecializedSalesPrice" readonly="readonly" value="<?php echo $Sales_Price_Specialized;?>"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="button" name="btnUpdate" id="btnUpdate" value="Make it Live" /></td>
  </tr>
</table>

<br /><br />
<?php
	$strSQL="Select * from t_package_price_settings order by package_price_id DESC";
	$strRsPricePackageArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsPricePackageArr)>0)
	{
?>

<div style="font-size:16px; font-weight:bold;">Archive Package List</div>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
  
  <tr class="EvenRow">
    <td width="30%"><strong>Name</strong></td>
    <td width="20%"><strong>Simple</strong></td>
    <td width="20%"><strong>Complex</strong></td>
    <td width="20%"><strong>Specialized</strong></td>
    <td width="10%"><strong>Action</strong></td>
  </tr>
  
  <?php 
  $iCtr=0;
  while($strRsPricePackage=mysql_fetch_object($strRsPricePackageArr))
  {
  		$iCtr++;
		if($iCtr % 2==1)
			$strClass="OddRow";
		else
			$strClass="";
  ?>
  <tr class="<?php echo $strClass;?>">
    <td valign="top">
	<strong><?php echo $strRsPricePackage->Package_Price_Name;?></strong><br />
    <span style="font-size:12px; font-style:italic;">Created by: Admin <br />on <?php echo Globals::DateFormat($strRsPricePackage->doc);?></span>
	</td>
    <td valign="top">
    	<div style="width:150px; float:left;">Manufacturing Cost:</div> <div style="float:left;">$<?php echo $strRsPricePackage->Manufacturing_Cost_Simple;?></div>
        <div class="clear"></div>
        <div style="width:150px; float:left;">Subscription Fee:</div> <div style="float:left;">$<?php echo $strRsPricePackage->Subscription_Fee_Simple;?></div>
        <div class="clear"></div>
        <div style="width:150px; float:left; font-weight:bold;">Sales Price:</div> <div style="float:left; font-weight:bold;">$<?php echo $strRsPricePackage->Sales_Price_Simple;?></div>
        <div class="clear"></div>
    </td>
    <td valign="top">
    	<div style="width:150px; float:left;">Manufacturing Cost:</div> <div style="float:left;">$<?php echo $strRsPricePackage->Manufacturing_Cost_Complex;?></div>
        <div class="clear"></div>
        <div style="width:150px; float:left;">Subscription Fee:</div> <div style="float:left;">$<?php echo $strRsPricePackage->Subscription_Fee_Complex;?></div>
        <div class="clear"></div>
        <div style="width:150px; float:left; font-weight:bold;">Sales Price:</div> <div style="float:left; font-weight:bold;">$<?php echo $strRsPricePackage->Sales_Price_Complex;?></div>
        <div class="clear"></div>
    </td>
    <td valign="top">
    	<div style="width:150px; float:left;">Manufacturing Cost:</div> <div style="float:left;">$<?php echo $strRsPricePackage->Manufacturing_Cost_Specialized;?></div>
        <div class="clear"></div>
        <div style="width:150px; float:left;">Subscription Fee:</div> <div style="float:left;">$<?php echo $strRsPricePackage->Subscription_Fee_Specialized;?></div>
        <div class="clear"></div>
        <div style="width:150px; float:left; font-weight:bold;">Sales Price:</div> <div style="float:left; font-weight:bold;">$<?php echo $strRsPricePackage->Sales_Price_Specialized;?></div>
        <div class="clear"></div>
    </td>
    <td valign="top" style="font-size:12px;"><a href="javascript:UsePackage('<?php echo $strRsPricePackage->package_price_id;?>')">Use Template</a> <br /> <a href="javascript:DeletePackage('<?php echo $strRsPricePackage->package_price_id;?>')">Delete</a></td>
  </tr>
  <?php }?>
  
</table>

<?php }?>

