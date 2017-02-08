<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;
$client_id=$_SESSION['client_details']->client_id;


/*

	Insert into t_login
	user_id = {
				client_id from t_client table
				
				# Insert customer_user_access_id in t_client table
			}
	
	email_address
	password
	user_type=1
	master_user_type=0
	user_access_id = Customer Specific User Access ID selected from Dropdown
	delete_flag=0

*/


if($_POST)
{
	
	$Login_Email=mysql_escape_string($_POST['Email']);
	$strSQL="Select * from t_login where email_address='".$Login_Email."'";
	$strRsEmailExistsArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsEmailExistsArr)>0)
	{
		print "ERR";
	}
	else
	{
		$user_type=	mysql_escape_string($_POST['UserType']);	
		$user_full_name=mysql_escape_string($_POST['UserName']);
		$user_email=$Login_Email;		
		$user_position=mysql_escape_string($_POST['Position']);
		$user_department=mysql_escape_string($_POST['Department']);
		$user_contact_number=mysql_escape_string($_POST['ContactNumber']);
		$created_by=$_SESSION['user_login']->login_id;
		$modified_by=$_SESSION['user_login']->login_id;
		
		$password=md5(mysql_escape_string($_POST['Password']));
		$user_access_id=$_POST['UserAccessType'];
		
		
            $strSQL="Insert into t_client(account_type, contact_name, contact_title, contact_email, phone, 
		doc, dom, created_by, modified_by, delete_flag,customer_user_access_id) 
		Values($user_type, '$user_full_name', '$user_position', '$user_email', $user_contact_number,
		now(), now(), $created_by, $modified_by, 0, $user_access_id)";		
		$strUser_ID=$DB->Execute($strSQL);		
		
		if($strUser_ID<>"")
		{
			$strSQL="Insert into t_login(user_id, email_address, password, user_type, master_user_type, user_access_id, delete_flag)
			Values($strUser_ID, '$user_email', '$password', 1, 0, $user_access_id, 0)";
			$DB->Execute($strSQL);
		}
		
		print "User Created!";
		
	}
	exit();
	
}

$strSQL="Select * from t_customer_user_access where client_id=$client_id order by user_access_name";
$strRsUserAccessArr=$DB->Returns($strSQL);

?>

<script type="text/javascript">

$('#btnSubmitUsers').click(function(){
	var UserType=$('#ddlUserType').val();
	var UserName=$('#txtUserName').val();
	var Position=$('#txtPosition').val();
	var Department=$('#txtDepartment').val();
	var ContactNumber=$('#txtContactNumber').val();
	var UserAccessType=$('#ddlUserAccess').val();
	var Email=$('#txtEmail').val();
	var Password=$('#txtPassword').val();
	var ConfirmPassword=$('#txtConfirmPassword').val();
	
	if(UserName=="")
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
		
	if(UserAccessType=="")
	{
		alert("Please Select User Template");
		$('#ddlUserAccess').focus();
		return;
	}
	
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
	
	$.post('<?php echo URL?>ajax_pages/customers/users.php',
		{
			UserType:UserType,
			UserName:UserName,
			Position:Position,
			Department:Department,
			ContactNumber:ContactNumber,
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
				$.get('<?php echo URL?>ajax_pages/customers/users_list.php',{},function(data){
					$('#Users_Container').html(data);
				});
			
				$('#User_Access_Container').html('Loading...');
				$.get('<?php echo URL?>ajax_pages/customers/users.php',{},function(data){
					$('#User_Access_Container').html(data);
				});
			}
	});
	
});


</script>

<style type="text/css">
#MasterLoginList table tr td
{
	border:1px solid #999999;
}
</style>

<div style="font-size:16px; font-weight:bold; border-bottom:1px solid #CCCCCC; margin-bottom:10px; margin-left:10px;"> New User Login</div>

<table width="560" border="0" cellspacing="1" cellpadding="3" style="margin-left:20px;">
  <tr>
    <td><strong>Personal Information</strong></td>
    <td><label>
      <select name="ddlUserType" id="ddlUserType">
        <option value="1" selected="selected">Internal</option>
        <option value="2">Associate</option>
        <option value="3">Contractor</option>
        <option value="4">Vendor</option>
        <option value="5">Other</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td width="50%"><input type="text" name="txtUserName" id="txtUserName" placeholder="User Full Name (First & Last)" /></td>
    <td width="50%"><input type="text" name="txtPosition" id="txtPosition" placeholder="Position" /></td>
  </tr>
  
  <tr>
    <td><input type="text" name="txtDepartment" id="txtDepartment" placeholder="Department" /></td>
    <td><input type="text" name="txtContactNumber" id="txtContactNumber" placeholder="Contact Number" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Login Information</strong></td>
  </tr>
  <tr>
    <td><select name="ddlUserAccess" id="ddlUserAccess">
      <option value="">Select User Template</option>
      <?php while($strRsUserAccess=mysql_fetch_object($strRsUserAccessArr)){?>
      <option value="<?php echo $strRsUserAccess->user_access_id;?>"><?php echo $strRsUserAccess->user_access_name;?></option>
      <?php }?>
    </select></td>
    <td><input type="text" name="txtEmail" id="txtEmail" placeholder="Login Email" /></td>
  </tr>
  <tr>
    <td><input type="text" name="txtPassword" id="txtPassword" placeholder="Password" /></td>
    <td><input type="text" name="txtConfirmPassword" id="txtConfirmPassword" placeholder="Retype Password" /></td>
  </tr>
  <tr>
    <td><input type="button" name="btnSubmitUsers" id="btnSubmitUsers" value="Submit" /></td>
    <td>&nbsp;</td>
  </tr>
</table>
