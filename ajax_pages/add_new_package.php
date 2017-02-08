<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

$strSQL="Select * from t_package_price_settings Order by package_price_id desc LIMIT 1";
$strRsPackagePriceSettingsArr=$DB->Returns($strSQL);
if($strRsPackagePriceSettings=mysql_fetch_object($strRsPackagePriceSettingsArr))
{
	$Manufacturing_Cost_Simple=$strRsPackagePriceSettings->Manufacturing_Cost_Simple;
	$Manufacturing_Cost_Complex=$strRsPackagePriceSettings->Manufacturing_Cost_Complex;
	$Manufacturing_Cost_Specialized=$strRsPackagePriceSettings->Manufacturing_Cost_Specialized;
	$Subscription_Fee_Simple=$strRsPackagePriceSettings->Subscription_Fee_Simple;
	$Subscription_Fee_Complex=$strRsPackagePriceSettings->Subscription_Fee_Complex;
	$Subscription_Fee_Specialized=$strRsPackagePriceSettings->Subscription_Fee_Specialized;
	$Sales_Price_Simple=$strRsPackagePriceSettings->Sales_Price_Simple;
	$Sales_Price_Complex=$strRsPackagePriceSettings->Sales_Price_Complex;
	$Sales_Price_Specialized=$strRsPackagePriceSettings->Sales_Price_Specialized;
	$Sales_Price_Margin=$strRsPackagePriceSettings->Sales_Price_Margin;
}

if($_POST['FinalProductQty'])
{
	$SoftwareVersion=$_POST['SoftwareVersion'];
	$PackageName=$_POST['PackageName'];
	$FirstYearPrice=$_POST['FirstYearPrice'];
	$SubscriptionPrice=$_POST['SubscriptionPrice'];
	$ActiveStatus=$_POST['ActiveStatus'];
	
	
	$strSQL="Insert into t_product_package(version, package_name, package_decription, 
	first_year_price, subscription_fee, doc, edit_flag, delete_flag, dom)
	Values($SoftwareVersion,'$PackageName','',$FirstYearPrice,$SubscriptionPrice,now(),0,$ActiveStatus,now())";
	
	$product_package_id=$DB->Execute($strSQL);
	
	$iCtr=0;
	$FinalProductQtyarr=explode("~@~",$_POST['FinalProductQty']);
	if(is_array($FinalProductQtyarr) && count(FinalProductQtyarr)>0)
	{
		foreach($FinalProductQtyarr as $val)
		{
			$FinalProductQtyarr1=explode("~~",$val);			
			if(is_array($FinalProductQtyarr1) && count($FinalProductQtyarr1)>0 and $iCtr>0)
			{
				$strSQL="Insert into t_product_package_details(product_package_id, system_id, number_of_system)
						Values($product_package_id,".$FinalProductQtyarr1[0].",".$FinalProductQtyarr1[1].")";
				//print $strSQL."<br />";
				$DB->Execute($strSQL);
				
			}
			$iCtr++;
		}
	}
	
	?>
    <script type="text/javascript">
		window.location='<?php echo URL?>?type=package';
	</script>
    
    <?php
	exit();
}


?>

<script  type="text/javascript">
$(function(){

	$('#btnAddProuct').click(function(){
		var System_Name= $('#ddlSystem :selected').text();
		
		var System_ID= $('#ddlSystem').val();
		var System_IDArr=System_ID.split("~");
		System_ID=System_IDArr[0];
		
		var SystemComplexity=System_IDArr[1];		
		var System_Qty= $('#txtProductQty').val();
		
		var SalesPrice=0;
		if(SystemComplexity==1)
		{
			SalesPrice=<?php echo $Sales_Price_Simple;?>;
		}
		else if(SystemComplexity==2)
		{
			SalesPrice=<?php echo $Sales_Price_Complex;?>;
		}
		else if(SystemComplexity==3)
		{
			SalesPrice=<?php echo $Sales_Price_Specialized;?>;
		}
		
		var FirstYearPrice=$('#txtFirstYearPrice').val();
		
		if(FirstYearPrice=="")
			FirstYearPrice=0;
		
		FirstYearPrice=parseFloat(FirstYearPrice);
		
		var SalesPriceFirstYear=FirstYearPrice+(System_Qty*SalesPrice);
		$('#txtFirstYearPrice').val(SalesPriceFirstYear);
		$('#txtFirstYearPrice_Display').val("$"+SalesPriceFirstYear+" for First Year");
		
		
		var SubscribePrice=0;
		if(SystemComplexity==1)
		{
			SubscribePrice=<?php echo $Subscription_Fee_Simple;?>;
		}
		else if(SystemComplexity==2)
		{
			SubscribePrice=<?php echo $Subscription_Fee_Complex;?>;
		}
		else if(SystemComplexity==3)
		{
			SubscribePrice=<?php echo $Subscription_Fee_Specialized;?>;
		}
		
		var SubscriptionFee=$('#txtSubscriptionPrice').val();
		
		if(SubscriptionFee=="")
			SubscriptionFee=0;
		
		SubscriptionFee=parseFloat(SubscriptionFee);
		
		var SubscriptionFeeNextYear=SubscriptionFee+(System_Qty*SubscribePrice);
		$('#txtSubscriptionPrice').val(SubscriptionFeeNextYear);
		$('#txtSubscriptionPrice_Display').val("$"+SubscriptionFeeNextYear+" from Next Year");
		
		
		/* Display Purpose */
		var Exists=$('#Temp_Subscription_Products').html();		
		var Temp=Exists+System_Name+" - "+ System_Qty+" Units<br>";
		$('#Temp_Subscription_Products').html(Temp);
		
		/* For Updating into Database*/
		var finalValExists=$('#finalVal').val();
		var finalValTemp=finalValExists+"~@~"+System_ID+"~~"+System_Qty;
		$('#finalVal').val(finalValTemp);		
		
		$('#ddlSystem').val('');
		$('#txtProductQty').val('');
		
	});
	
	
	$('#btnSavePackage').click(function(){	
		
		var SoftwareVersion=$('#ddlVersion').val();
		var PackageName=$('#txtPackageName').val();
		var FirstYearPrice=$('#txtFirstYearPrice').val();
		var SubscriptionPrice=$('#txtSubscriptionPrice').val();
		var FinalProductQty=$('#finalVal').val();
		var ActiveStatus=$('#ddlActiveStatus').val();
		
		if(SoftwareVersion=='')
		{
			alert("Please select Version");
			$('#ddlVersion').focus();
			return;
		}		
		else if(PackageName=='')
		{
			alert("Please Type the Package Name");
			$('#txtPackageName').focus();
			return;
		}		
		else if(FirstYearPrice=='')
		{
			alert("Please Type the First Year Price");
			$('#txtFirstYearPrice').focus();
			return;
		}		
		else if(SubscriptionPrice=='')
		{
			alert("Please Type the Yearly Subscription Fee");
			$('#txtSubscriptionPrice').focus();
			return;
		}
		else if(FinalProductQty=='')
		{
			alert("Please Include System in this Package");
			$('#ddlSystem').focus();
			return;
		}
		
		
		
		$.post("<?php echo URL?>ajax_pages/add_new_package.php",
			{
				SoftwareVersion:SoftwareVersion,
				PackageName:PackageName,
				FirstYearPrice:FirstYearPrice,
				SubscriptionPrice:SubscriptionPrice,
				ActiveStatus:ActiveStatus,
				FinalProductQty:FinalProductQty,
			},
			function(data,status){
				$('#Temp_Subscription_Products').html(data);
			}
		);	
		
	
	});
	
	
	$.get("<?php echo URL?>ajax_pages/product_package.php",{id:0},function(data){
		$('#Product_Packages_List').html(data);
	});
	

});
</script>


<strong style="font-size:16px;">Package Manager - Add New Package</strong><br />

   <table width="99%" border="0" align="center" cellpadding="3" cellspacing="0" style="border:1px solid #CCCCCC;">
    
     <tr>
       <td width="23%" align="center" style="padding-top:10px;">
       <select name="ddlVersion" id="ddlVersion">
            <option value="">Select a Version</option>
            <?php				
				$strSQL="Select * from t_software_version order by software_version ASC";
				$strRsSoftwareVersionsArr=$DB->Returns($strSQL);
				while($strRsSoftwareVersions=mysql_fetch_object($strRsSoftwareVersionsArr))
				{
			?>
            	<option value="<?php echo $strRsSoftwareVersions->software_version_id; ?>" <?php if($strRsSoftwareVersions->software_version_id==$software_version_id){?>selected="selected"<?php }?> ><?php echo $strRsSoftwareVersions->software_version; ?></option>
            <?php }?>
       </select></td>
       <td width="19%" style="padding-top:10px;">
       	<input type="text" name="txtFirstYearPrice_Display" id="txtFirstYearPrice_Display" placeholder="Price for First Year ($)" style="font-size:16px; font-weight:bold;" readonly="readonly" />
        <input type="hidden" name="txtFirstYearPrice" id="txtFirstYearPrice" value="0" />
       
       </td>
       <td width="58%" style="padding-top:10px;"><table width="100%" border="0" cellspacing="1" cellpadding="0">
           <tr>
             <td width="30%" align="center"><strong>Package Includes:</strong></td>
             <td width="40%">
             <select name="ddlSystem" id="ddlSystem">
                 <option value="">Select a System</option>
                 <?php				
				$strSQL="Select * from t_system where level=4 and has_node=1 order by system_name ASC";
				$strRsSystemArr=$DB->Returns($strSQL);
				while($strRsSystem=mysql_fetch_object($strRsSystemArr))
				{
			?>
                 <option value="<?php echo $strRsSystem->system_id; ?>~<?php echo $strRsSystem->complexity; ?>"><?php echo $strRsSystem->system_name; ?></option>
                 <?php }?>
             </select></td>
             <td width="15%"><input type="text" name="txtProductQty" id="txtProductQty" placeholder="Unit Qty." style="width:70px;" /></td>
             <td width="15%"><input type="button" name="btnAddProuct" id="btnAddProuct" value="Add" />
                 <input type="hidden" name="finalVal" id="finalVal" value="" /></td>
           </tr>
         </table></td>
     </tr>
     <tr>
       <td align="center" valign="top"><input type="text" name="txtPackageName" id="txtPackageName" placeholder="Package Name" /></td>
       <td valign="top">
       	<input type="text" name="txtSubscriptionPrice_Display" id="txtSubscriptionPrice_Display" placeholder="Subscription Fee/Year ($)" style="font-size:16px; font-weight:bold;" readonly="readonly" />
        <input type="hidden" name="txtSubscriptionPrice" id="txtSubscriptionPrice" value="0" />
      </td>
       <td rowspan="3" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="0">
           <tr>
             <td width="30%">&nbsp;</td>
             <td width="70%"><div id="Temp_Subscription_Products" style="background-color:#DDDDDD; padding:3px; font-weight:bold;"></div></td>
           </tr>
         </table></td>
     </tr>
     <tr>
       <td align="center" valign="top"><select name="ddlActiveStatus" id="ddlActiveStatus">
         <option value="0">Active</option>
         <option value="1">Inactive</option>
       </select></td>
       <td valign="top"><input type="button" name="btnSavePackage" id="btnSavePackage" value="Save Package" /></td>
     </tr>
     
     
     <tr>
       <td valign="top">&nbsp;</td>
       <td valign="top">&nbsp;</td>
     </tr>
</table>
  <br /> 
  <strong style="font-size:16px;">Package Manager - Existing Packages</strong><br />
   <div id="Product_Packages_List"></div>

 