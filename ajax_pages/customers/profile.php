<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

if(isset($_POST) && !empty($_POST)){
    $strSQL = "update t_login set email_address = '".$_POST['login_email']."'";
    if($_POST['password'] != ''){
        $strSQL.=", password = '".md5($_POST['password'])."'";
    }
    $strSQL.= " where user_id='".$_POST['id']."' and user_type=1";
    $DB->Returns($strSQL);
    $strSQL = "update t_client set address_line_1 = '".$_POST['address_line_1']."', address_line_2 = '".$_POST['address_line_2']."', city = '".$_POST['city']."', state = '".$_POST['state']."', zip = '".$_POST['zip']."', phone = '".$_POST['phone']."', contact_name = '".$_POST['txtAdminName']."' where client_id='".$_POST['id']."'";
    $DB->Returns($strSQL);
    echo "success";
    exit;
}

$strClientID = Globals::Get('id');

$strSQL = "Select C.*, L.email_address as login_email, L.password from t_client C left join t_login L on C.client_id = L.user_id where client_id=$strClientID and user_type=1";
$strRsClientDetailsArr = $DB->Returns($strSQL);
$strRsClientDetails = mysql_fetch_object($strRsClientDetailsArr);

?>
<script>
    function updateAdmin(){
        var txtAdminName = $('#txtAdminName').val();
        var address_line_1 = $('#address_line_1').val();
        var address_line_2 = $('#address_line_2').val();
        var city = $('#city').val();
        var state = $('#state').val();
        var zip = $('#zip').val();
        var phone = $('#phone').val();
        var login_email = $('#login_email').val();
        var password = $('#password').val();
        var con_password = $('#con_password').val();
        
        if(login_email == ""){
            alert('please enter a valid email');
            return false;
        }
        
        if(password != con_password){
            alert('password and confirm password should match');
            return false;
        }
        
        $.post("<?php echo URL ?>ajax_pages/customers/profile.php",
        {
            id: <?=$strClientID?>,
            txtAdminName: txtAdminName,
            address_line_1: address_line_1,
            address_line_2: address_line_2,
            city: city,
            state: state,
            zip: zip,
            phone: phone,
            login_email: login_email,
            password: password
        },
        function (data, status) {
            alert('updated successfully');
            location.reload();
            //$('#Profile_Container').html(data);
        });
        
        return false;
    }
</script>
<div style="height:5px;">&nbsp;</div>
<div style="text-transform:uppercase; padding:3px; font-weight:bold;">
	<div style="float:left; margin-left:20px;">Edit Profile</div>    
    <div class="clear"></div>
</div>
<form name="form1" method="post" action="" onsubmit="return updateAdmin();">
	<table width="470" border="0" cellspacing="1" cellpadding="3" style="margin-left:20px;">
      <tr>
          <td width="6%"><input type="text" name="txtProfileName" id="txtAdminName" value="<?=$strRsClientDetails->contact_name?>" class="textbox" placeholder="Name" /></td>
        <td width="52%"><input type="text" name="txtProfileName2" id="txtProfileName2" class="textbox" value="Administrator" readonly="readonly"  /></td>
      </tr>
      
      <tr>
        <td colspan="2"><input type="text" name="txtProfileName4" id="address_line_1" class="textbox" value="<?=$strRsClientDetails->address_line_1?>" style="width:432px;" placeholder="Address Line 1" /></td>
      </tr>
      <tr>
        <td colspan="2"><input type="text" name="txtProfileName5" id="address_line_2" class="textbox" value="<?=$strRsClientDetails->address_line_2?>" style="width:432px;" placeholder="Address Line 2" /></td>
      </tr>
      <tr>
        <td><input type="text" name="txtProfileName6" id="city" class="textbox" value="<?=$strRsClientDetails->city?>"  placeholder="City" /></td>
        <td><input type="text" name="txtProfileName7" id="state" class="textbox" value="<?=$strRsClientDetails->state?>"  placeholder="State" /></td>
      </tr>
      <tr>
        <td><input type="text" name="txtProfileName8" id="zip" class="textbox" value="<?=$strRsClientDetails->zip?>"  placeholder="Zip" /></td>
        <td><input type="text" name="txtProfileName9" id="phone" class="textbox" value="<?=$strRsClientDetails->phone?>"  placeholder="Telephone" /></td>
      </tr>
      
      <tr>
        <td><input type="text" name="txtProfileName10" id="login_email" class="textbox" value="<?=$strRsClientDetails->login_email?>"  placeholder="Login Email" /></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="text" name="password" id="password" class="textbox" value=""  placeholder="Password" /></td>
        <td><input type="text" name="con_password" id="con_password" class="textbox" value=""  placeholder="Repeat Password" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="text-align:right;"><input type="submit" name="button" id="button" value="Submit Changes" style="margin-right:20px;" /></td>
      </tr>
    </table>
</form>


