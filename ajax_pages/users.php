<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;


if($_POST)
{
	//print_r($_POST);
	
	$Login_Email=mysql_escape_string($_POST['Email']);
	$strSQL="Select * from t_login where email_address='".$Login_Email."'";
	$strRsEmailExistsArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsEmailExistsArr)>0)
	{
		print "ERR";
	}
	else
	{		
		$user_full_name=mysql_escape_string($_POST['UserName']);
		$user_email=$Login_Email;		
		$user_position=mysql_escape_string($_POST['Position']);
		$user_department=mysql_escape_string($_POST['Department']);
		$user_contact_number=mysql_escape_string($_POST['ContactNumber']); 
		$user_address=mysql_escape_string($_POST['Address']);
		$user_city=mysql_escape_string($_POST['City']);
		$user_state=mysql_escape_string($_POST['State']);
		$user_zip=mysql_escape_string($_POST['Zip']);
		$created_by=1;
		$modified_by=1;
		
		$password=md5(mysql_escape_string($_POST['Password']));
		$user_access_id=$_POST['UserAccessType'];
		
		$strSQL="Select user_access_type from t_user_access where user_access_id=$user_access_id";
		$strRsUserAccessTypeArr=$DB->Returns($strSQL);
		if($strRsUserAccessType=mysql_fetch_object($strRsUserAccessTypeArr))
		{
			$master_user_type=$strRsUserAccessType->user_access_type;
		}
		
		$strSQL="Insert into t_users (user_full_name, user_email, user_position, user_department, user_contact_number, 
		user_address, user_city, user_state, user_zip, doc, dom, created_by, modified_by, delete_flag)		
		Values('$user_full_name', '$user_email', '$user_position', '$user_department', '$user_contact_number', 
		'$user_address', '$user_city', '$user_state', '$user_zip', now(), now(), $created_by,$modified_by, 0)";		
		$strUser_ID=$DB->Execute($strSQL);		
		
		if($strUser_ID<>"")
		{
			$strSQL="Insert into t_login(user_id, email_address, password, user_type, master_user_type, user_access_id, delete_flag)
			Values($strUser_ID, '$user_email', '$password', 0, $master_user_type, $user_access_id, 0)";
			$DB->Execute($strSQL);
		}
		
		print "User Created!";
		
	}
	exit();
	
}


$strSQL="Select * from  t_user_access Order By user_access_name";
$strRsUserAccessArr=$DB->Returns($strSQL);
?>

<script type="text/javascript">

$('#btnSubmit').click(function(){
	var UserName=$('#txtUserName').val();
	var Position=$('#txtPosition').val();
	var Department=$('#txtDepartment').val();
	var ContactNumber=$('#txtContactNumber').val();
	var Address=$('#txtAddressLine').val();
	var City=$('#txtCity').val();
	var State=$('#txtState').val();
	var Zip=$('#txtZip').val();
	var UserAccessType=$('#ddlUserAccess').val();
	var Email=$('#txtEmail').val();
	var Password=$('#txtPassword').val();
	var ConfirmPassword=$('#txtConfirmPassword').val();
	
	/*if(UserName=="")
	{
		alert("Please enter Full Name");
		$('#txtUserName').focus();
		return;
	}
	if(Position=="")
	{
		alert("Please enter Position");
		$('#txtPosition').focus();
		return;
	}
	if(Department=="")
	{
		alert("Please enter Department");
		$('#txtDepartment').focus();
		return;
	}
		
	if(ContactNumber=="")
	{
		alert("Please enter Contact Number");
		$('#txtContactNumber').focus();
		return;
	}
	
	if(UserAccessType=="")
	{
		alert("Please Select User Template");
		$('#ddlUserAccess').focus();
		return;
	}*/
	
	if(Email=="")
	{
		alert("Please Enter Login Email");
		$('#txtEmail').focus();
		return;
	}
	
	if(Password=="")
	{
		alert("Please Enter Login Password");
		$('#txtPassword').focus();
		return;
	}
	
	if(Password!=ConfirmPassword)
	{
		alert("Login Password and Confirm Password donot match");
		$('#txtConfirmPassword').focus();
		return;
	}
	
	$.post('<?php echo URL?>ajax_pages/users.php',
		{
			UserName:UserName,
			Position:Position,
			Department:Department,
			ContactNumber:ContactNumber,
			Address:Address,
			City:City,
			State:State,
			Zip:Zip,
			UserAccessType:UserAccessType,
			Email:Email,
			Password:Password,
		},
		
		function(data){
			if(data=="ERR")
			{
				alert("Login Email Already Exists in System");
			}
			else
			{
				alert(data);
				
				$('#Category_Container').html('Loading...');
				$.get('<?php echo URL?>ajax_pages/users.php',{},function(data){
					$('#Category_Container').html(data);
				});
			}
	});
	
});

$('#ddlUserTemplate').change(function(){
	$('#MasterLoginList').html('Loading...');
	$.get('<?php echo URL?>ajax_pages/users.php',{UserTemplate:this.value},function(data){				
		$('#Category_Container').html(data);		
	});
	
});

</script>

<style type="text/css">
#MasterLoginList table tr td
{
	border:1px solid #999999;
}
</style>

<div style="font-size:16px; font-weight:bold; border-bottom:1px solid #CCCCCC; margin-bottom:10px;">MASTER User Login</div>

<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td><strong>Personal Information</strong></td>
    <td><input type="text" name="txtUserName" id="txtUserName" placeholder="User Full Name (First & Last)" /></td>
    <td><input type="text" name="txtPosition" id="txtPosition" placeholder="Position" /></td>
    <td><input type="text" name="txtDepartment" id="txtDepartment" placeholder="Department" /></td>
    <td><input type="text" name="txtContactNumber" id="txtContactNumber" placeholder="Contact Number" /></td>
  </tr>
  <tr>
    <td><strong>Address (<i style="font-weight:normal;">Optional</i>)</strong></td>
    <td><input type="text" name="txtAddressLine" id="txtAddressLine" placeholder="Address" /></td>
    <td><input type="text" name="txtCity" id="txtCity" placeholder="City" /></td>
    <td><input type="text" name="txtState" id="txtState" placeholder="State" /></td>
    <td><input type="text" name="txtZip" id="txtZip" placeholder="Zip" /></td>
  </tr>
  <tr>
    <td><strong>Login Information</strong></td>
    <td><select name="ddlUserAccess" id="ddlUserAccess">
      <option value="">Select User Template</option>
      <?php while($strRsUserAccess=mysql_fetch_object($strRsUserAccessArr)){?>
      <option value="<?php echo $strRsUserAccess->user_access_id;?>"><?php echo $strRsUserAccess->user_access_name;?></option>
      <?php }?>
    </select></td>
    <td><input type="text" name="txtEmail" id="txtEmail" placeholder="Login Email" /></td>
    <td><input type="text" name="txtPassword" id="txtPassword" placeholder="Password" /></td>
    <td><input type="text" name="txtConfirmPassword" id="txtConfirmPassword" placeholder="Retype Password" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="button" name="btnSubmit" id="btnSubmit" value="Submit"></td>
  </tr>
</table>
<br>

<div style="font-size:16px; font-weight:bold; border-bottom:1px solid #CCCCCC; margin-bottom:10px;">MASTER User Login</div>
<div id="MasterLoginList" style="margin-bottom:20px;">
<?php	
	$UserTemplate=$_GET['UserTemplate'];
	if($UserTemplate>0)
	{
		$strQueryAppend=" And t_user_access.user_access_id=$UserTemplate ";
	}
	else
	{
		$UserTemplate="0";
		$strQueryAppend="";
	}
	
	
	$strSQL="Select t_users.*, t_login.master_user_type,  t_login.user_access_id, t_user_access.user_access_name
		from t_users,  t_login, t_user_access
		where t_users.user_email=t_login.email_address and t_user_access.user_access_id=t_login.user_access_id
		$strQueryAppend
		order by user_full_name";
	
	$strRsUsersListsArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsUsersListsArr)>0)
	{
?>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr style="background-color:#EFEFEF;">
    <td width="9%"><strong>Name</strong></td>
    <td width="16%"><strong>Email</strong></td>
    <td width="12%"><strong>User Types</strong>
     <!-- <select name="ddlUserType" id="ddlUserType">
        <option value="">All User Types</option>
        <option value="1">Administrator</option>
        <option value="2">Engineer</option>
        <option value="3">User</option>
      </select>-->    </td>
    <td width="18%">
    
        <select name="ddlUserTemplate" id="ddlUserTemplate">
          <option value="0">All User Templates</option>
          <?php 
		  	$strSQL="Select Distinct(t_user_access.user_access_name), t_user_access.user_access_id from t_user_access, t_login where t_user_access.user_access_id=t_login.user_access_id order by user_access_name";
			$strRsUserAccessArr=$DB->Returns($strSQL);
			while($strRsUserAccess=mysql_fetch_object($strRsUserAccessArr))
			{
				if($UserTemplate==$strRsUserAccess->user_access_id)
					$strSelect=" Selected='Selected' ";
				else
					$strSelect="";
				print '<option value="'.$strRsUserAccess->user_access_id.'" '.$strSelect.' >'.$strRsUserAccess->user_access_name.'</option>';
			}
		  ?>                  
        </select>
   
      </td>
    <td width="23%"><strong>Position</strong></td>
    <td width="22%"><strong>Operation</strong></td>
  </tr>
  <?php 
  	while($strRsUsersLists=mysql_fetch_object($strRsUsersListsArr))
	{
		$strSQL="Select user_access_type from t_user_access where user_access_id=".$strRsUsersLists->user_access_id;
		$strRsUserAccessTypeArr=$DB->Returns($strSQL);
		if($strRsUserAccessType=mysql_fetch_object($strRsUserAccessTypeArr))
		{
			$master_user_type=$strRsUserAccessType->user_access_type;
			if($master_user_type==1)
				$master_user_type="Administrator";
			elseif($master_user_type==2)
				$master_user_type="Engineer";
			elseif($master_user_type==3)
				$master_user_type="User";
		}
	?>
  <tr>
    <td><?php echo $strRsUsersLists->user_full_name;?></td>
    <td><?php echo $strRsUsersLists->user_email;?></td>
    <td><?php echo $master_user_type;?></td>
    <td><?php echo $strRsUsersLists->user_access_name;?></td>
    <td><?php echo $strRsUsersLists->user_position." (".$strRsUsersLists->user_department.")";?></td>
    <td><a href="#">Edit</a> | <a href="#">Delete</a> | <a href="#">Suspend</a></td>
  </tr>
  <?php }?>
</table>

<?php
	}
?>
</div>


