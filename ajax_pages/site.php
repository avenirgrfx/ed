<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/building.class.php");
$Building=new Building;

$DB=new DB;
?>


<?php
if($_POST['txtSiteName'])
{
	$myArray=array('client_id'=>$_POST['client_id'],'site_name'=>$_POST['txtSiteName'],'address_line1'=>$_POST['txtAddress_Line1'],'address_line2'=>$_POST['txtAddress_Line2'],
	'city'=>$_POST['txtCity'],'state'=>$_POST['txtState'],'zip'=>$_POST['txtZip'],'country'=>$_POST['txtCountry'], 'note'=>$_POST['txtNote'], 'created_by'=>$_SESSION['user_login']->user_id, 'modified_by'=>$_SESSION['user_login']->user_id, 'delete_flag'=>0, 'time_zone'=>$_POST['ddlTimeZone']);
	$Building->setVal($myArray);
	
	if($_POST['site_id']=="")
	{
		$Building->InsertSite();
		print '<div style="font-family:Arial, Helvetica, sans-serif; color:#006600; margin:45px 0px 0px 0px; font-size:18px;">
		Successfully Added!</div>';
	}
	else
	{
		$Building->site_id=$_POST['site_id'];
		$Building->UpdateSite();
		print '<div style="float:left; font-family:Arial, Helvetica, sans-serif; color:#006600; margin:45px 0px 0px 0px; font-size:18px;">
		Successfully Updated!</div>';
	}
	?>
    <script type="text/javascript">
		$(document).ready(function(){
			ProjectDetails('<?php echo $_POST['client_id'];?>');
		});
	</script>
    <?php
	exit();
}

if(Globals::Get('site_id')<>'' and Globals::Get('client_id')<>'' and Globals::Get('mode')=='')
{
	$strSQL="Select * from t_sites where site_id=".Globals::Get('site_id')." And client_id=".Globals::Get('client_id');
	$strSiteRsArr=$DB->Lists(array('Query'=>$strSQL));
	if(!is_array($strSiteRsArr))
	{
		print 'Illegal Operation';
		exit();
	}
	
	foreach($strSiteRsArr as $Val)
	{
		$site_id=$Val->site_id;
		$client_id=$Val->client_id;
		$site_name=$Val->site_name;
		$address_line1=$Val->address_line1;
		$address_line2=$Val->address_line2;
		$city=$Val->city;
		$state=$Val->state;
		$zip=$Val->zip;
		$country=$Val->country;
		$note=$Val->note;
		$time_zone=$Val->time_zone;
	}
	
}
elseif(Globals::Get('site_id')<>'' and Globals::Get('client_id')<>'' and Globals::Get('mode')=='delete')
{
	$Building->site_id=Globals::Get('site_id');
	$Building->client_id=Globals::Get('client_id');
	$Building->DeleteSite();
	print "Site Delete";
?>
    <script type="text/javascript">
		$(document).ready(function(){
			ProjectDetails('<?php echo Globals::Get('client_id');?>');
		});
	</script>
<?php
	exit();
}

$strSQL="Select * from t_client where client_id=".Globals::Get('client_id');
$ClientArray=$DB->Lists(array('Query'=>$strSQL));
?>
<br><br>
<h2><?php if($site_id==''){?>Add<?php }else{?>Edit<?php }?> Site for <?php echo $ClientArray[0]->client_name;?></h2>
<script type="text/javascript">

$(document).ready(function(){
	$('#cmdClose').click(function(){
		$('#Building_Container').slideUp();
	});
	
	$('#cmdSubmit').click(function(){	
		$.post("ajax_pages/site.php",
		{
			txtSiteName:$('#txtSiteName').val(),
			txtAddress_Line1:$('#txtAddress_Line1').val(),
			txtAddress_Line2:$('#txtAddress_Line2').val(),
			txtCity:$('#txtCity').val(),
			txtState:$('#txtState').val(),
			txtZip:$('#txtZip').val(),
			ddlTimeZone:$('#ddlTimeZone').val(),
			txtNote:$('#txtNote').val(),
			client_id:$('#client_id').val(),
			site_id:$('#site_id').val()
		},
		function(data,status){						
			$('#Building_Container').html(data);							
		});
	
	});
	
	
});


function ValidSite()
{
	var frm=document.frmSites;
	if(frm.txtSiteName.value=="")
	{
		alert("Enter Site Name");
		frm.txtSiteName.focus();
		return false;
	}
	else if(frm.txtAddress_Line1.value=="")
	{
		alert("Enter Address Line 1");
		frm.txtAddress_Line1.focus();
		return false;
	}
	/*else if(frm.txtAddress_Line2.value=="")
	{
		alert("Enter Address Line 2");
		frm.txtAddress_Line2.focus();
		return false;
	}*/
	else if(frm.txtCity.value=="")
	{
		alert("Enter City Name");
		frm.txtCity.focus();
		return false;
	}
	else if(frm.txtState.value=="")
	{
		alert("Enter State");
		frm.txtState.focus();
		return false;
	}
	else if(frm.txtZip.value=="")
	{
		alert("Enter Zip Code");
		frm.txtZip.focus();
		return false;
	}
	else if(frm.txtCountry.value=="")
	{
		alert("Enter Country");
		frm.txtCountry.focus();
		return false;
	}
	return true;
}
</script>

<form action="" method="post" enctype="multipart/form-data" name="frmSites" id="frmSites" onsubmit="return ValidSite();">

<table width="430" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td><span class="TextBox_Div"><strong>All fields are mandatory</strong></span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="50%"><span class="TextBox_Div">
      <input type="text" name="txtSiteName" id="txtSiteName" placeholder="Site Name" class="TextBox" value="<?php echo $site_name;?>" />
    </span></td>
    <td width="50%"><span class="TextBox_Div">
      <input type="text" name="txtAddress_Line1" id="txtAddress_Line1" placeholder="Address Line 1" class="TextBox" value="<?php echo $address_line1;?>" />
    </span></td>
  </tr>
  <tr>
    <td><span class="TextBox_Div">
      <input type="text" name="txtAddress_Line2" id="txtAddress_Line2" placeholder="Address Line 2" class="TextBox" value="<?php echo $address_line2;?>" />
    </span></td>
    <td><span class="TextBox_Div">
      <input type="text" name="txtCity" id="txtCity" placeholder="City" class="TextBox" value="<?php echo $city;?>" />
    </span></td>
  </tr>
  <tr>
    <td><span class="TextBox_Div">
      <input type="text" name="txtState" id="txtState" placeholder="State" class="TextBox" value="<?php echo $state;?>" />
    </span></td>
    <td><span class="TextBox_Div">
      <input type="text" name="txtZip" id="txtZip" placeholder="Zip" class="TextBox" value="<?php echo $zip;?>" />
    </span></td>
  </tr>
  
  <tr>
    <td colspan="2">
    <select name="ddlTimeZone" id="ddlTimeZone">
    	<?php Globals::TimeZoneList($time_zone);?>
    </select>
    </td>
    </tr>
  <tr>
    <td><span class="TextBox_Div">
      <textarea name="txtNote" class="TextBox" id="txtNote" placeholder="Note"><?php echo $note;?></textarea>
    </span></td>
    <td><input type="button" name="cmdSubmit" id="cmdSubmit" value="Submit" class="Button" />
      <input type="button" name="cmdClose" id="cmdClose" value="Close" />
      <input type="hidden" name="client_id" id="client_id" value="<?php echo Globals::Get('client_id');?>" />
      <input type="hidden" value="USA" name="txtCountry" id="txtCountry" />
      <input name="site_id" type="hidden" id="site_id" value="<?php echo $site_id;?>" /></td>
  </tr>
</table>
</form>