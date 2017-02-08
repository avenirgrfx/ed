<?php
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
$login_id=$_SESSION['user_login']->login_id;

if($_GET['PaymentPreference']<>"")
{
	$strPaymentPreference=$_GET['PaymentPreference'];
}
else
{
	$strPaymentPreference=1;
}

if($_GET['VersionID']<>"")
{
	$Version_ID=$_GET['VersionID'];
	$strSQLFilter=" and version=$Version_ID ";
}
else
{
	$strSQLFilter=" ";
}

?>

<style type="text/css">
table  tr td
{
	border:1px solid #CCCCCC;
	padding:3px;
	color:#666666;
}

.subscribed_text_color
{
	color:#333333;
}

.not_subscribed_text_color
{
	color:#CCCCCC;
}


</style>



<div style="padding:5px;">



<div id="Package_Listing_For_Customer">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;">
  <tr style="font-weight:bold; text-decoration:underline; background-color:#DEDEDE;">
    <td style="width:30px; text-align:center;"><input type="checkbox" name="checkbox2" id="checkbox2" /></td>
    <td style="width:340px;">Product</td>
    <td style="width:90px;">Next Billing</td>
    <td style="width:120px;">Payment Method</td>
   <td style=" width:90px;">Auto Renew</td>
    <td style="width:90px;">Last Charged</td>    
     <td style="width:140px;">Pay with Incentives</td>
  </tr>


<?php

$strSQL="Select * from t_product_package where delete_flag=0 $strSQLFilter order by package_name";
$strRsPackagesArr=$DB->Returns($strSQL);
while($strRsPackages=mysql_fetch_object($strRsPackagesArr))
{
	$first_year_price=$strRsPackages->first_year_price;
	$subscription_fee=$strRsPackages->subscription_fee;
	$Year="Year";
	
	if($strPaymentPreference==2)
	{
		$first_year_price=$first_year_price-$subscription_fee;
		$subscription_fee=$subscription_fee/4;
		$first_year_price=$first_year_price+$subscription_fee;
		$Year="Quarter";
	}
	elseif($strPaymentPreference==3)
	{
		$first_year_price=$first_year_price-$subscription_fee;
		$subscription_fee=$subscription_fee/12;
		$first_year_price=$first_year_price+$subscription_fee;
		$Year="Month";
	}
	
	
	$strSQL="Select * from t_customer_subscription where package_id=".$strRsPackages->product_package_id." and customer_id=$login_id order by customer_subscription_id DESC Limit 1";
	$strRsPackageSubscriptionArr=$DB->Returns($strSQL);
	if($strRsPackageSubscription=mysql_fetch_object(strRsPackageSubscriptionArr))
	{
		$strSubscribe=true;
	}
	else
	{
		$strSubscribe=false;
	}
	
?>
	     <tr>
        <td valign="top" style="text-align:center;"><input type="checkbox" name="checkbox" id="checkbox" /></td>
        <td valign="top" style="width:400px;">
		<strong style="font-size:16px;">
		
		<?php echo $strRsPackages->package_name;?></strong>
        <?php if($strSubscribe==true){?>
        	<span style="width:50px; background-color:#009900; color:#FFFFFF; border:1px solid #CCCCCC; border-radius:3px; padding: 0px 5px; margin-left:10px;">Subscribed</span>
        <?php }?>
        <br /><strong>$<?php echo number_format($first_year_price,2);?></strong> (First <?php echo $Year?>). Second <?php echo $Year?> onwards <strong>$<?php echo number_format($subscription_fee,2);?>/<?php echo $Year?></strong><br />
		<?php	
			$strSQL="Select t_system.system_name, t_product_package_details.*  from t_system, t_product_package_details where t_product_package_details.system_id=t_system.system_id and t_product_package_details.product_package_id=".$strRsPackages->product_package_id;
			$strRsPackageDetailsArr=$DB->Returns($strSQL);
			
			print "<div style='font-size:12px; color:#999999;'><strong>Package contains</strong>:<ul>";
			while($strRsPackageDetails=mysql_fetch_object($strRsPackageDetailsArr))
			{
				print "<li>".$strRsPackageDetails->system_name." (".$strRsPackageDetails->number_of_system." units)</li>";
			}
			print '</ul></div>';		
		?>
        </td>
        <td valign="top">
        <div style="width:100px; text-align:center;">
                <div class="Renew" style="margin-left:30px; <?php if($strSubscribe==false){?>opacity: 0.5;<?php }?>">&nbsp;</div>
                <div class="<?php if($strSubscribe==false){?>not_<?php }?>subscribed_text_color">Renew Now</div>
            </div>
               </td>
        <td valign="top">
        <div style="width:130px; text-align:center;">    	
                <div class="UpdatePaymentMethod" style="margin-left:45px; <?php if($strSubscribe==false){?>opacity: 0.5;<?php }?>">&nbsp;</div>
                <div class="<?php if($strSubscribe==false){?>not_<?php }?>subscribed_text_color">Payment Method</div>
            </div>
        </td>
        <td valign="top">
         <div style="width:130px; text-align:center;">    	
            <div class="AutoRenewOn" style="margin-left:45px; <?php if($strSubscribe==false){?>opacity: 0.5;<?php }?>">&nbsp;</div>
            <div class="<?php if($strSubscribe==false){?>not_<?php }?>subscribed_text_color">Auto-Renew ON</div>
        </div>
        <div style="width:130px; text-align:center;">
            <div class="AutoRenewOf" style="margin-left:45px; <?php if($strSubscribe==false){?>opacity: 0.5;<?php }?>">&nbsp;</div>
            <div class="<?php if($strSubscribe==false){?>not_<?php }?>subscribed_text_color">Auto-Renew OFF</div>
        </div>
        </td>
        
        <td valign="top" >Last Charged</td>
        <td valign="top">
        	<div style="float:left; margin-top:-3px;"><input type="checkbox" name="checkbox3" id="checkbox3" /></div>
            <div style="float:left; ">Defer Utility Incentives</div>
            <div class="clear"></div>
        </td>
        
    </tr>
<?php }?>
</table>

</div>

</div>
