<?php
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
?>

<style type="text/css">
#Product_Subscription_Header
{
	height:50px;
	color:#CCCCCC;
}
#Product_Subscription_Filter
{
	padding:5px 10px;
	/*background-color:#DEDEDE;*/
}

#Product_Subscription_List
{
	font-weight:bold;
	text-decoration:underline;
}

#Product_Subscription_List div
{
	padding:5px;
	/*border-left:1px solid #CCCCCC;
	border-top:1px solid #CCCCCC;
	border-bottom:1px solid #CCCCCC;*/
}

.Product_Subscription_ItemList div
{
	padding:5px;
	/*border-left:1px solid #CCCCCC;
	border-bottom:1px solid #CCCCCC;*/
}

#Product_Packages_List table  tr td
{
	border:1px solid #CCCCCC;
	padding:3px;
	color:#666666;
}

.Renew
{
  background: url(<?php echo URL?>images/product_subscription_sprite.gif);
  background-position: -72px -99px;
  width: 36px;
  height: 25px;
  background-repeat: no-repeat;
  margin-left:56px;
}

.AutoRenewOn
{
	background: url(<?php echo URL?>images/product_subscription_sprite.gif);
	background-position: -36px -29px;
	width: 36px;
	height: 25px;
	background-repeat: no-repeat;
	margin-left:56px;
}

.AutoRenewOf
{
	background: url(<?php echo URL?>images/product_subscription_sprite.gif);
	background-position: -144px -99px;
	width: 36px;
	height: 25px;
	background-repeat: no-repeat;
	margin-left:56px;
}

.UpdatePaymentMethod
{
	background: url(<?php echo URL?>images/product_subscription_sprite.gif);
	background-position: -108px -29px;
	width: 36px;
	height: 25px;
	background-repeat: no-repeat;
	margin-left:56px;
}

</style>

<script type="text/javascript">
$(function(){
	
});

function Activate_Deactivate()
{
	
}

</script>


<div style="padding:5px;">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;">
  <tr style="font-weight:bold; text-decoration:underline; background-color:#DEDEDE;">
    <td style="width:30px; text-align:center;"><input type="checkbox" name="checkbox2" id="checkbox2" /></td>
    <td style="width:350px;">Product</td>
    <td style="width:90px;">Number of Subscriptions</td>
    <td style="width:120px;">Version</td>
    <td style=" width:90px;">Active Status</td>
    <td style="width:90px;">Action</td>    
    </tr>


<?php

$strSQL="Select * from t_product_package  order by package_name";
$strRsPackagesArr=$DB->Returns($strSQL);
while($strRsPackages=mysql_fetch_object($strRsPackagesArr))
{
?>
	     <tr>
        <td valign="top" style="text-align:center;"><input type="checkbox" name="checkbox" id="checkbox" /></td>
        <td valign="top" style="width:400px;">
		<strong style="font-size:16px;"><?php echo $strRsPackages->package_name;?></strong> 
        <br /><strong>$<?php echo number_format($strRsPackages->first_year_price,0);?></strong> (First Year). Second year onwards <strong>$<?php echo number_format($strRsPackages->subscription_fee,0);?>/Year</strong><br />
		<?php	
			$strSQL="Select t_system.system_name, t_product_package_details.*  from t_system, t_product_package_details where t_product_package_details.system_id=t_system.system_id and t_product_package_details.product_package_id=".$strRsPackages->product_package_id;
			$strRsPackageDetailsArr=$DB->Returns($strSQL);
			
			print "<div style='font-size:12px; color:#999999;'><strong>Package contains</strong>:<ul>";
			while($strRsPackageDetails=mysql_fetch_object($strRsPackageDetailsArr))
			{
				print "<li>".$strRsPackageDetails->system_name." (".$strRsPackageDetails->number_of_system." units)</li>";
			}
			print '</ul></div>';		
		?>        </td>
        <td valign="top">
        
        <?php 
			$strSQL="Select Count(*) as SubscriptionCount from t_customer_subscription where package_id=".$strRsPackages->product_package_id;
			$strRsPackageSubscriptionListArr=$DB->Returns($strSQL);
			if($strRsPackageSubscriptionList=mysql_fetch_object($strRsPackageSubscriptionListArr))
			{
				print $strRsPackageSubscriptionList->SubscriptionCount;
			}
		?>
        
        </td>
        <td valign="top">
        	<?php
            	$strSQL="select software_version from t_software_version where software_version_id=".$strRsPackages->version;
				$strRsVersionsArr=$DB->Returns($strSQL);
				if($strRsVersions=mysql_fetch_object($strRsVersionsArr))
				{
					print $strRsVersions->software_version;
				}
			?>
        </td>
        <td valign="top" ><a href="javascript:Activate_Deactivate('<?php echo $strRsPackages->product_package_id?>','<?php echo $strRsPackages->delete_flag;?>')"><span id="Active_Deactive_<?php echo $strRsPackages->product_package_id?>"><?php if($strRsPackages->delete_flag==0){ print "Active";} else { print "Inactive";}?></span></a></td>
        
        <td valign="top" ><a href="#">Edit</a> | <a href="#">Delete</a></td>
    </tr>
<?php }?>
</table>

</div>
