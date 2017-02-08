<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
    $strSQL="delete from t_login where user_id='".$_POST['user_id']."'";
    $DB->Returns($strSQL);
    
    $strSQL="delete from t_client where client_id='".$_POST['user_id']."'";
    $DB->Returns($strSQL);
}
?>

<style type="text/css">
#MasterLoginList table tr td
{
	border:1px solid #999999;
}
</style>
<script>
    function Delete_User(user_id)
    {
        $.post("<?php echo URL ?>ajax_pages/customers/users_list.php",
        {
            user_id: user_id,
        },
        function (data, status) {
            $('#Users_Container').html(data);
        }); 
    }
</script>

<div style="font-size:16px; font-weight:bold; border-bottom:1px solid #CCCCCC; margin-bottom:10px;"> User Login</div>
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
	
	
	$strSQL="Select t_client.*, t_login.master_user_type,  t_login.user_access_id, t_customer_user_access.user_access_name
		from t_client,  t_login, t_customer_user_access
		where t_client.contact_email=t_login.email_address and t_customer_user_access.user_access_id=t_client.customer_user_access_id
		And t_customer_user_access.client_id=".$_SESSION['client_id']." $strQueryAppend";
	
	$strRsUsersListsArr=$DB->Returns($strSQL);
	if(mysql_num_rows($strRsUsersListsArr)>0)
	{
?>
<table width="470" border="0" cellspacing="1" cellpadding="3" style="margin-left:10px;">
  <tr style="background-color:#EFEFEF;">
    <td width="48%"><strong>Active User</strong></td>
    <td width="12%"><strong>User Type</strong>    </td>
    <td width="24%"><strong>Template</strong></td>
    <td width="16%"><strong>Operation</strong></td>
  </tr>
  <?php 
  	while($strRsUsersLists=mysql_fetch_object($strRsUsersListsArr))
	{		
		if($strRsUsersLists->account_type==1)
			$account_type="Internal";
		elseif($strRsUsersLists->account_type==2)
			$account_type="Associate";
		elseif($strRsUsersLists->account_type==3)
			$account_type="Contractor";
		elseif($strRsUsersLists->account_type==4)
			$account_type="Vendor";
		elseif($strRsUsersLists->account_type==5)
			$account_type="Other";
		
	?>
  <tr>
    <td><strong><?php echo $strRsUsersLists->contact_name;?></strong> (<?php echo $strRsUsersLists->contact_title;?>)<br /><?php echo $strRsUsersLists->contact_email;?></td>
    <td><?php echo $account_type;?></td>
    <td><?php echo $strRsUsersLists->user_access_name;?></td>
    <td><a href="#">Edit</a> | <a href="javascript:Delete_User('<?php echo $strRsUsersLists->client_id?>')">Delete</a> | <a href="#">Suspend</a></td>
  </tr>
  <?php }?>
</table>

<?php
	}
?>
</div>


