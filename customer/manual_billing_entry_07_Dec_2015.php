<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

if($_GET['mode']=='del' && $_GET['meter_id']<>"")
{
	$strSQL="Delete from t_utility_account_meters where utility_meter_number=".$_GET['meter_id'];
	$DB->Execute($strSQL);
	print $_GET['meter_id'];
	exit();
}

if($_GET['mode']=='edit' && $_GET['account_id']<>'')
{
	$strSQL="Select * from t_utility_accounts where utility_account_id=".$_GET['account_id'];
	$strRsUtilityAccountsArr=$DB->Returns($strSQL);
	while($strRsUtilityAccounts=mysql_fetch_object($strRsUtilityAccountsArr))
	{
		$Output= $strRsUtilityAccounts->utility_account_type."~~~".$strRsUtilityAccounts->utility_name."~~~".$strRsUtilityAccounts->utility_account_number."~~~".$strRsUtilityAccounts->utility_account_id."~~~";
	}
	
	$strSQL="Select * from t_utility_account_meters where utility_account_id=".$_GET['account_id'];
	$strRsUtilityMetersArr=$DB->Returns($strSQL);
	while($strRsUtilityMeters=mysql_fetch_object($strRsUtilityMetersArr))
	{
		$Output.= $strRsUtilityMeters->meter_number."@#@";
	}
	echo $Output;
	
	exit();
}


if($_GET['mode']=='delacc' && $_GET['account_id']<>'')
{
	$utility_account_id=$_GET['account_id'];
	$strSQL="Delete from t_utility_account_meters where utility_account_id=$utility_account_id";
	$DB->Execute($strSQL);
	
	$strSQL="Delete from t_utility_accounts where utility_account_id=$utility_account_id";
	$DB->Execute($strSQL);
	
	echo "Account Deleted";
	
	exit();
}

if($_POST['building_id']<>'')
{
	$BuildingID=$_POST['building_id'];
}
else
{
	$BuildingID=$_GET['building_id'];
}

$strSQL="Select site_id from t_building where building_id=$BuildingID";
$strRsSiteIDArr=$DB->Returns($strSQL);
while($strRsSiteID=mysql_fetch_object($strRsSiteIDArr))
{
	$strSiteID=$strRsSiteID->site_id;
}

if($_POST)
{
	$utility_account_type=$_POST['Electric_Gas_Type'];
	$utility_name=$_POST['UtilityName'];
	$utility_account_number=$_POST['UtilityAccount'];
	$site_id=$strSiteID;
	$building_id=$BuildingID;
	$created_by=$_SESSION['user_login']->login_id;
	$modified_by=$_SESSION['user_login']->login_id;
	
	$MeterArr=$_POST['MeterArr'];
	
	if($_POST['EditAccountID']=="")
	{	
		$strSQL="Insert into t_utility_accounts(utility_account_type, utility_name, utility_account_number, site_id, building_id, doc, dom, created_by, modified_by	, delete_flag)
		Values($utility_account_type, '$utility_name', '$utility_account_number', $site_id, $building_id, now(), now(), $created_by, $modified_by,0)";
		$utility_account_id=$DB->Execute($strSQL);
		
		if(is_array($MeterArr) && count($MeterArr)>0)
		{
			foreach($MeterArr as $meter_number)
			{
				$strSQL="Insert into t_utility_account_meters(utility_account_id,meter_number,doc) Values($utility_account_id,'$meter_number',now())";
				$DB->Execute($strSQL);
			}
		}
		
		print("Account Added!");
	}
	else
	{
		$strSQL="Update t_utility_accounts set utility_account_type=$utility_account_type, utility_name='$utility_name', 
		utility_account_number='$utility_account_number', dom=now(), modified_by=$modified_by where utility_account_id=".$_POST['EditAccountID'];
		$DB->Execute($strSQL);
		$utility_account_id=$_POST['EditAccountID'];
		
		$strSQL="Delete from t_utility_account_meters where utility_account_id=$utility_account_id";
		$DB->Execute($strSQL);
		
		if(is_array($MeterArr) && count($MeterArr)>0)
		{
			foreach($MeterArr as $meter_number)
			{
				$strSQL="Insert into t_utility_account_meters(utility_account_id,meter_number,doc) Values($utility_account_id,'$meter_number',now())";
				$DB->Execute($strSQL);
			}
		}
		
		print("Account Updated!");
	}
	exit();
}

?>
<script type='text/javascript' src="<?php echo URL?>js/jquery.js"></script>  
<script type="text/javascript">
$(document).ready(function(){
	var MeterNumber=2;
	$('#Add_More_Meters').click(function(){
		MeterNumber++;
		var AddedMeters=$('#More_Meter_Container').html();		
		$('#More_Meter_Container').html(AddedMeters+'<div id="ExtraMeter_Container_'+MeterNumber+'"><div class="clear" style="height:10px;"></div> <div style="float:left; width:150px; font-size:14px;">ELECTRIC METER '+MeterNumber+'</div><div style="float:left;"><input type="text" name="txtMeter'+MeterNumber+'" id="txtMeter'+MeterNumber+'"   style="width:200px;"></div></div>');
		$('#RemoveMeter_Link').css('display','block');
	});
	
	$('#RemoveMeter_Link').click(function(){
		$('#ExtraMeter_Container_'+MeterNumber).remove();
		MeterNumber--;
		if(MeterNumber<=2)
		{
			$('#RemoveMeter_Link').css('display','none');
		}
	});
	
	$('#btnCreateAccount').click(function(){
		var Electric_Gas_Type=$('#ddlElectric_Gas_Type').val();
		var UtilityName=$('#txtUtilityName').val();
		var UtilityAccount=$('#txtUtilityAccount').val();	
		var EditAccountID=$('#EditAccountID').val();	
		var MeterArr=[];
		for(var iCount=1; iCount<=MeterNumber; iCount++)
		{
			MeterArr[iCount-1]=$('#txtMeter'+iCount).val();
		}
		
		console.log(Electric_Gas_Type+'~'+UtilityName+'~'+UtilityAccount+'~~'+MeterArr);
		
		$.post
		('<?php echo URL?>customer/manual_billing_entry.php',
			{building_id:<?php echo $BuildingID;?>, Electric_Gas_Type:Electric_Gas_Type, UtilityName:UtilityName, UtilityAccount: UtilityAccount, EditAccountID: EditAccountID, MeterArr:MeterArr},
			function(data)
			{
				alert(data);
				window.location.reload();
			}
		);
		
	});
	
	$('#btnCancel').click(function(){
		$('#txtUtilityName').val('');
		$('#txtUtilityAccount').val('');
		$('#EditAccountID').val('');
		$('#txtMeter1').val('');
		$('#txtMeter2').val('');
		$('#btnCreateAccount').css('width','120px');
		$('#btnCreateAccount').html('Create Account');
		$('#btnCancel').css('display','none');
		$('#More_Meter_Container').html('');
	});
	
});

function DeleteMeter(iMeterID)
{
	if(!confirm("Are you sure you want to Delete this meter"))
		return false;
	
	$.get('<?php echo URL?>customer/manual_billing_entry.php?mode=del&meter_id='+iMeterID,{},function(data){
		$('#utility_meter_'+data).html('');
		$('#utility_meter_'+data).css('display','none');
	});
}

function DeleteAccount(iAccountID)
{
	if(!confirm("This action will delete the account and all meters. Are you sure you want to Delete this Account"))
		return false;
	
	$.get('<?php echo URL?>customer/manual_billing_entry.php?mode=delacc&account_id='+iAccountID,{},function(data){
		alert(data)
		window.location.reload();
	});
}

function EditAccount(iAccountID)
{
	$.get('<?php echo URL?>customer/manual_billing_entry.php?mode=edit&building_id=<?php echo $BuildingID;?>&account_id='+iAccountID,{},function(data){
		var ReturnDataArr=data.split("~~~");
		//alert(ReturnDataArr[1]);
		$('#txtUtilityName').val(ReturnDataArr[1]);
		$('#txtUtilityName').focus();
		$('#txtUtilityAccount').val(ReturnDataArr[2]);
		$('#EditAccountID').val(ReturnDataArr[3]);
		
		$('#More_Meter_Container').html('');
		
		var MetersString=ReturnDataArr[4];
		var MeterArr=MetersString.split("@#@");
		
		for(var iCtr=1; iCtr<MeterArr.length; iCtr++)
		{
			if(iCtr>2)
			{
				$('#Add_More_Meters').trigger('click');
			}
		}
		
		for(var iCtr=1; iCtr<MeterArr.length; iCtr++)
		{
			$('#txtMeter'+iCtr).val(MeterArr[iCtr-1]);
		}
		
		
		$('#btnCreateAccount').css('width','125px');
		$('#btnCreateAccount').html('Update Account');
		$('#btnCancel').css('display','block');
		
	});
	
	
}

</script>

<link rel="stylesheet" type="text/css" href="<?php echo URL?>css/master.css">
<div style="background-color:#FFFFFF; border-radius:10px; overflow-y:scroll; height:350px;" class="myscroll">
	
    <div style="padding:10px; float:left; width:420px;">
        
    	<div style="font-size:16px; text-decoration:underline; font-weight:bold;  margin-bottom:10px;">CREATE NEW ACCOUNT</div>
        
    	<div style="float:left; width:150px; font-size:16px;">ADD NEW ACCOUNT</div>
        <div style="float:left;">
        	<select name="ddlElectric_Gas_Type" id="ddlElectric_Gas_Type" style="font-size:16px; width:200px; font-weight:bold; color:#666666; font-family: UsEnergyEngineers;">
            	<option value="1">ELECTRICITY</option>
                <option value="2">NATURAL GAS</option>
            </select>
         </div>        
        <div class="clear" style="height:10px;"></div>
        
        <div style="float:left; width:150px; font-size:16px;">
        	UTILITY NAME
        </div>
        <div style="float:left;">
        	<input type="text" name="txtUtilityName" id="txtUtilityName"  value="" style="width:200px;">
        </div>        
        <div class="clear" style="height:10px;"></div>
        
        <div style="float:left; width:150px; font-size:16px;">
        	ACCOUNT #
        </div>
        <div style="float:left;">
        	<input type="text" name="txtUtilityAccount" id="txtUtilityAccount"  value="" style="width:200px;">
        </div>        
        <div class="clear" style="height:10px;"></div>
        
        <div style="float:left; width:150px; font-size:14px;">
        	ELECTRIC METER 1
        </div>
        <div style="float:left;">
        	<input type="text" name="txtMeter1" id="txtMeter1"  value="" style="width:200px;">
        </div>
        
        <div class="clear" style="height:10px;"></div>
        <div style="float:left; width:150px; font-size:14px;">
        	ELECTRIC METER 2
        </div>
        <div style="float:left;">
        	<input type="text" name="txtMeter2" id="txtMeter2"  value="" style="width:200px;">
        </div>
        
        <input type="hidden" name="EditAccountID" id="EditAccountID" value="" />
        
        <div id="More_Meter_Container"></div>
        
        <div style="float:left; margin-left:2px; display:none; cursor:pointer;" id="RemoveMeter_Link"><strong>X</strong></div>
        
        
        <div style="float:right; text-align:center; cursor:pointer; width:50px; background-color:#CCCCCC; border-radius:3px; font-weight:bold;" id="Add_More_Meters">Add</div>
        <div class="clear" style="height:10px;"></div>
        
        <div style="background-color:#CCCCCC; padding:3px 6px; text-transform:uppercase; border-radius:5px; cursor:pointer; border:1px solid #666666; width:120px; font-weight:bold; text-align:center; float:right; margin-right:55px;" id="btnCreateAccount">Create Account</div>
        <div style="background-color:#CCCCCC; padding:3px 6px; text-transform:uppercase; border-radius:5px; cursor:pointer; border:1px solid #666666; width:57px; font-weight:bold; text-align:center; float:right; margin-right:5px; display:none;" id="btnCancel">Cancel</div>
        <div class="clear"></div>       
        
    </div>
    
     
    <div style="float:left; margin-left:10px; border-left:1px solid #999999; padding-left:10px; padding-top:10px;">
        <div style="font-size:16px; text-decoration:underline; font-weight:bold; margin-bottom:10px;">EXISTING ELECTRIC ACCOUNTS</div>
        
        <?php
        $strSQL="Select * from t_utility_accounts where building_id=$BuildingID";
        $strRsUtilityAccountsArr=$DB->Returns($strSQL);
        $iAccountCtr=1;
        while($strRsUtilityAccounts=mysql_fetch_object($strRsUtilityAccountsArr))
        {
            $strSQL="Select * from t_utility_account_meters where utility_account_id=".$strRsUtilityAccounts->utility_account_id;
            $strRsUtilityMetersArr=$DB->Returns($strSQL);
            $iMterCtr=1;
        ?>            
            <div>
                <strong>ACCOUNT <?php echo $iAccountCtr;?></strong> <a href="javascript:EditAccount('<?php echo $strRsUtilityAccounts->utility_account_id?>')">Edit</a> | <a href="javascript:DeleteAccount('<?php echo $strRsUtilityAccounts->utility_account_id?>')">Delete</a><br />
                UTILITY - <?php echo $strRsUtilityAccounts->utility_name;?><br />
                ACCOUNT# - <?php echo $strRsUtilityAccounts->utility_account_number;?><br />
                <?php while($strRsUtilityMeters=mysql_fetch_object($strRsUtilityMetersArr)){?>
                    <div id="utility_meter_<?php echo $strRsUtilityMeters->utility_meter_number?>">ELECTRIC METER <?php echo $iMterCtr?> - <?php echo $strRsUtilityMeters->meter_number;?> <a href="javascript:DeleteMeter('<?php echo $strRsUtilityMeters->utility_meter_number?>')">Delete</a></div>
                <?php $iMterCtr++; }?>
            </div>

            <div class="clear" style="margin: 10px 0px; border-bottom: 1px dotted #CCC;"></div>

        <?php 
            $iAccountCtr++;
        }?>
	</div>
    
    <div class="clear"></div>
    
</div>