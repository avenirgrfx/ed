<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");

$Client=new Client;



if(Globals::Get('id')<>'' and Globals::Get('id')<>0)
{
	$DB = new DB;
	$ClientArray=$DB->Lists(array('Query'=>'Select * from t_client where client_id='.Globals::Get('id')));
	if(!is_array($ClientArray))
	{
		print 'Invalid ID';
		exit();
	}
	foreach($ClientArray as $Val)
	{
		$client_id=$Val->client_id;
		$client_type=$Val->client_type;
		$software_version_id=$Val->software_version_id;
		$client_name=$Val->client_name;		
		$email_address=$Val->email_address;
		$address_line_1=$Val->address_line_1;
		$address_line_2=$Val->address_line_2;
		$city=$Val->city;
		$state=$Val->state;
		$zip=$Val->zip;
		$country=$Val->country;		
		$phone=$Val->phone;
		$website=$Val->website;
		$contact_name=$Val->contact_name;
		$contact_title=$Val->contact_title;
		$contact_email=$Val->contact_email;
		$manager_name=$Val->manager_name;
		$manager_email=$Val->manager_email;
		$manager_phone=$Val->manager_phone;
		$logo=$Val->logo;
	}
}
else
{
	$client_id=0;
}


?>

<script type="text/javascript">

function ValidCustomer()
{
	var frm=document.frmCustomer;
	
	if(frm.ddlClientType.value==-1)
	{
		if(frm.txtClientType.value=="")
		{
			alert("Please enter Customer Type");
			frm.txtClientType.focus();
			return false;
		}
	}
	
	if(frm.ddlVersion.value=="")
	{
		alert("Please select a Version");
		frm.ddlVersion.focus();
		return false;
	}
	
	if(frm.txtClientName.value=="")
	{
		alert("Please enter Customer Name");
		frm.txtClientName.focus();
		return false;
	}
	
	else if(frm.txtEmailAddress.value=="")
	{
		alert("Please enter Email Address");
		frm.txtEmailAddress.focus();
		return false;
	}
	
	<?php if(Globals::Get('id')==''){?>
	else if(frm.txtPassword.value=="")
	{
		alert("Please enter Password");
		frm.txtPassword.focus();
		return false;
	}
	<?php }?>
	
	else if(frm.txtPassword2.value!=frm.txtPassword.value)
	{
		alert("Confirm Password doesn't match with Password");
		frm.txtPassword2.focus();
		return false;
	}
	
	else if(frm.txtAddress_Line1.value=="")
	{
		alert("Please enter Address Line 1");
		frm.txtAddress_Line1.focus();
		return false;
	}
	
	else if(frm.txtCity.value=="")
	{
		alert("Please enter City");
		frm.txtCity.focus();
		return false;
	}
	
	else if(frm.txtState.value=="")
	{
		alert("Please enter State")
		frm.txtState.focus();
		return false;
	}
	
	else if (frm.txtZip.value=="")
	{
		alert("Please enter Zip");
		frm.txtZip.focus();
		return false;
	}
	
	else if(frm.txtContactName.value=="")
	{
		alert("Please enter Contact Name");
		frm.txtContactName.focus();
		return false;
	}
	
	else if(frm.txtDesignation.value=="")
	{
		alert("Please enter Designation");
		frm.txtDesignation.focus();
		return false;
	}
	
	else if(frm.txtContactEmail.value=="")
	{
		alert("Please enter Contact Email");
		frm.txtContactEmail.focus();
		return false;
	}
	
	else if(frm.txtPhone.value=="")
	{
		alert("Please enter Contact Phone");
		frm.txtPhone.focus();
		return false;
	}
	
	
	return true;
	
}

function CheckAddCustomerType(strID)
{
	if(strID==-1)
	{
		$('#txtClientType').css('display','block');
		$('#Edit_Delete_Industry').css('display','none');
	}
	else
	{		
		var strIDArr=strID.split("~");
		$('#txtClientType').css('display','none');
		$('#Edit_Delete_Industry').css('display','block');
		if(strIDArr[0]==0)
			$('#DeleteIndustryType').css('display','none');
		else
			$('#DeleteIndustryType').css('display','block');
	}
}




$(document).ready(function(){
	CheckAddCustomerType($('#ddlClientType').val());
	
	
	$('#DeleteIndustryType').click(function(){
		
		if(!confirm("Are you sure you want to Delete?"))
		{
			return false;
		}
		else
		{
			var id=$('#ddlClientType').val();
			$.get("<?php echo URL?>ajax_pages/delete_industry_type.php",
			{
				id:id				
			},
			function(data,status){
				if(data=="1")				
					$("#ddlClientType option[value='"+id+"']").remove();
				else
					alert(data);
			}
		)};	
	});
	
	$('#EditIndustryType').click(function(){
		var id=$('#ddlClientType').val();	
		
		$('#txtClientTypeEdit').css('display','block');
		var TextVal=$('#ddlClientType option:selected').text();
		$('#txtClientTypeEdit').val(TextVal);
		$('#EditIndustryType_Save').css('display','block');
		
	});
	
	
	$('#EditIndustryType_Save').click(function(){
		var id=$('#ddlClientType').val();		
		var TextVal=$('#txtClientTypeEdit').val();
				
		$.post("<?php echo URL?>ajax_pages/delete_industry_type.php",
		{
				id:id,
				type:'edit',
				updatedText:TextVal		
		},
			function(data,status){				
				$('#showNewProject').trigger('click');
			}
		)		
	});
	
	
	
})

</script>

<style type="text/css">
input[type="text"]
{
	width:170px;
}
select
{
	width:185px;
}
</style>

<div style="width:42%; float:left; border:1px solid #CCCCCC;">
  <h2>Open Existing Clients</h2>
    <?php
		$iCtr=0;
		$strClientListArr=$Client->AllCustomers();	
	?>
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr style="font-weight:bold;">
    <td width="36%">Project Name</td>
    <td width="22%">Industry</td>
    <td width="22%">Version</td>
    <td width="20%">Created On</td>
  </tr>
  <?php 
  	$strTableClass="OddRow";
  	if(is_array($strClientListArr) && count($strClientListArr)>0)
	{
		foreach($strClientListArr as $strClientList)
		{
			$iCtr++;
			if($iCtr % 2==0)
				$strTableClass="OddRow";
			else
				$strTableClass="EvenRow";
  ?>
  
  <tr style="font-size:12px;" class="<?php echo $strTableClass; ?>" >
    <td><a href="javascript:ProjectDetails('<?php echo $strClientList['client_id']?>');"><?php echo $strClientList['client_name'];?></a></td>
    <td><?php echo $strClientList['client_type_name'];?></td>
    <td><?php echo $strClientList['software_version_name'];?></td>
    <td><?php echo Globals::DateFormat($strClientList['doc']);?> &nbsp;<a href="javascript:EditProject('<?php echo $strClientList['client_id']?>')"><img src="<?php echo URL?>images/edit-button.png" border="0" /></a></td>
  </tr>
  <?php
  		}
  	} 
  ?>
</table>

</div>


<div style="width:56%; float:left; margin-left:1%; font-size:13px; border:1px solid #999999; background-color:#EFEFEF;">
  <form action="" method="post" enctype="multipart/form-data" name="frmCustomer" id="frmCustomer" onsubmit="return ValidCustomer()">
    <table width="98%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td colspan="4">      
        <h2><?php if(Globals::Get('id')=='' or Globals::Get('id')==0){ print "Add New Client"; } else { print "Update Client"; } ?></h2>      </td>
      </tr>
      <tr>
        <td colspan="2"><strong>Basic Information</strong></td>
        <td colspan="2"><strong>Contact  Information</strong></td>
      </tr>
      <tr>
        <td>Logo (Only .jpg)</td>
        <td><?php if($logo<>''){?>
          <?php echo Globals::Resize(URL.'uploads/customer/'.rawurlencode($logo),100, 75)?>
          <?php }?>
          <br />
          <input type="file" name="file1" id="file1" /></td>
        <td>Contact Name</td>
        <td><input type="text" name="txtContactName" id="txtContactName" class="textbox" value="<?php echo $contact_name?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td valign="top">Industry Type</td>
        <td valign="top">
        <select name="ddlClientType" id="ddlClientType" onchange="CheckAddCustomerType(this.value)" style="float:left;">
          <?php $Client->FetchCustomerType($client_type);?>
        </select>
        <div id="Edit_Delete_Industry" style="float:left; margin-left:2px; margin-top:3px;">
        	<img src="<?php echo URL?>images/edit-button.png" border="0" alt="Edit Industry Type" title="Edit Industry Type" style="float:left; cursor:pointer;" id="EditIndustryType" />
        	<img src="<?php echo URL?>images/delete-blue.png" title="Delete Industry Type" alt="Delete Industry Type" border="0" id="DeleteIndustryType" style="float:left; cursor:pointer;" />
        	<div class="clear"></div>
        </div>
        <div class="clear"></div>
        <input type="text" name="txtClientType" id="txtClientType" class="textbox" value="" style="display:none;" placeholder="New Industry Type" />
        <input type="text" name="txtClientTypeEdit" id="txtClientTypeEdit" class="textbox" value="" style="display:none; float:left;" placeholder="Edit Industry Type" />       
        <img id="EditIndustryType_Save" src="<?php echo URL?>images/save-button.png" style="float:left; display:none; margin-left:3px; margin-top:6px; cursor:pointer;" />
        <div class="clear"></div>        </td>
        <td valign="top">Designation</td>
        <td valign="top"><input type="text" name="txtDesignation" id="txtDesignation" class="textbox" value="<?php echo $contact_title?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>Version</td>
        <td>
        <select name="ddlVersion" id="ddlVersion">
          <?php $Client->ListSoftwareVersion($software_version_id);?>
        </select>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="21%">Customer Name</td>
        <td width="36%"><input type="text" name="txtClientName" id="txtClientName" class="textbox" value="<?php echo $client_name?>" autocomplete="off" /></td>
        <td width="19%">Contact Email</td>
        <td width="24%"><input type="text" name="txtContactEmail" id="txtContactEmail" class="textbox" value="<?php echo $contact_email?>" autocomplete="off" /></td>
      </tr>
        
      <tr>
        <td>Address Line 1</td>
        <td><input name="txtAddress_Line1" type="text" class="textbox" id="txtAddress_Line1" value="<?php echo $address_line_1?>" autocomplete="off" /></td>
        <td>Phone</td>
        <td><input type="text" name="txtPhone" id="txtPhone" class="textbox" value="<?php echo $phone?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>Address Line 2</td>
        <td><input name="txtAddress_Line2" type="text" class="textbox" id="txtAddress_Line2" value="<?php echo $address_line_2?>" autocomplete="off" /></td>
        <td>Website</td>
        <td><input type="text" name="txtWebsite" id="txtWebsite" class="textbox" value="<?php echo $website?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>City</td>
        <td><input name="txtCity" type="text" class="textbox" id="txtCity" value="<?php echo $city?>" autocomplete="off" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>State</td>
        <td><input name="txtState" type="text" class="textbox" id="txtState" value="<?php echo $state?>" autocomplete="off" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Zip</td>
        <td><input name="txtZip" type="text" class="textbox" id="txtZip" value="<?php echo $zip?>" autocomplete="off" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><strong>Login Information</strong></td>
        <td colspan="2"><strong>Manager Information</strong></td>
      </tr>
      <tr>
        <td>Email Address</td>
        <td><input name="txtEmailAddress" type="text" class="textbox" id="txtEmailAddress" value="<?php echo $email_address?>" autocomplete="off" /></td>
        <td>Manager Name</td>
        <td><input type="text" name="txtManagerName" id="txtManagerName" class="textbox" value="<?php echo $manager_name?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>Password</td>
        <td><input type="password" name="txtPassword" id="txtPassword" class="textbox" autocomplete="off" /></td>
        <td>Manager Email</td>
        <td><input type="text" name="txtManagerEmail" id="txtManagerEmail" class="textbox" value="<?php echo $manager_email?>" autocomplete="off" /></td>
      </tr>
      <tr>
        <td>Re-type Password</td>
        <td><input type="password" name="txtPassword2" id="txtPassword2" class="textbox" autocomplete="off" /></td>
        <td>Manager Phone</td>
        <td><input type="text" name="txtManagerPhone" id="txtManagerPhone" class="textbox" value="<?php echo $manager_phone?>" autocomplete="off" /></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="hidden" name="txtCountry" id="txtCountry" value="USA" />
          
          <input name="client_id" type="hidden" id="client_id" value="<?php echo $client_id;?>" />
          <input type="hidden" name="type" id="type" value="Customer">        <input type="submit" name="button" id="button" value="Submit" /></td>
      </tr>
    </table>
  </form>
</div>



<div class="clear" style="clear:both;"></div>
