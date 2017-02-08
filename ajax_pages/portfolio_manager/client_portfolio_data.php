<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath . "classes/all.php");
require_once(AbsPath . "classes/customer.class.php");

$Client = new Client;

$adminUsername = Globals::GetPortfolioUsername();
$adminPassword = Globals::GetPortfolioPassword();

if(isset($_POST) && !empty($_POST)){
    $CreateCustomer = '';
    $CreateCustomer.='
        <account>
        <username>'.$_POST['txtuserrName'].'1</username>
        <password>'.$_POST['txtPassword'].'</password>
        <webserviceUser>true</webserviceUser>
        <searchable>true</searchable>

        <contact>
            <address country="US" postalCode="'.$_POST['txtZip'].'" state="'.$_POST['txtState'].'" city="'.$_POST['txtCity'].'" address1="'.$_POST['txtAddress_Line1'].'"/>
            <firstName>'.$_POST['txtFirstName'].'</firstName>
            <email>'.$_POST['txtEmail'].'</email>
            <lastName>'.$_POST['txtLastName'].'</lastName>
            <jobTitle>Building Administrator</jobTitle>
            <phone>'.$_POST['txtPhone'].'</phone>
        </contact>

        <organization name="'.$_POST['txtOrgName'].'">
            <primaryBusiness>'.$_POST['primary_business'].'</primaryBusiness>';
           // <otherBusinessDescription>other</otherBusinessDescription>
        $CreateCustomer.= '<energyStarPartner>'.$_POST['energystar_partner'].'</energyStarPartner>';
        if($_POST['energystar_partner'] == 'true'){
            $CreateCustomer.= '<energyStarPartnerType>'.$_POST['partner_type'].'</energyStarPartnerType>';
        }
        $CreateCustomer.= '</organization>

        <securityAnswers>
            <securityAnswer>
                <question id="'.$_POST['question1'].'"/>
                <answer>'.$_POST['txtAnswer1'].'</answer>
            </securityAnswer>
            <securityAnswer>
                <question id="'.$_POST['question2'].'"/>
                <answer>'.$_POST['txtAnswer2'].'</answer>
                </securityAnswer>
        </securityAnswers>

        </account>';
    //print_r($CreateCustomer);exit;
    
    $response = Globals::CallAPI("POST",'https://'.$adminUsername.':'.$adminPassword.'@portfoliomanager.energystar.gov/wstest/customer', $CreateCustomer);
    //echo $customer_id;
    //$customer_id = "";
    //echo $response;
    //echo "success";exit;
    if(simplexml_load_string($response)){
        $response = new SimpleXMLElement($response);
        if($response['status'][0]=="Ok"){
            $customer_id = $response->id[0];
            $DB = new DB;
            $strSQL = "insert into t_portfolio_client values ('".$_POST['txtClientId']."', '".$_POST['primary_business']."', '".$_POST['energystar_partner']."', '".$_POST['partner_type']."', '".$_POST['question1']."', '".$_POST['question2']."', '".$_POST['txtAnswer1']."', '".$_POST['txtAnswer2']."', '".$_POST['txtuserrName']."1', '".$_POST['txtPassword']."', '$customer_id');";
            $DB->Returns($strSQL);
            
            $strSQL = "update t_client set portfolio_status = 1 where client_id = ".$_POST['txtClientId'];
            $DB->Returns($strSQL);
            echo "success";
        }else{
            echo "Please update client info.";
            echo "<br><br>";
            foreach($response->errors[0]->error as $error){
                echo ($error['errorDescription']);
                echo "</br>";
            };
        }
    }else{
        echo "Error in request";
    }
    
}else{
    
if (Globals::Get('client_id') <> '' and Globals::Get('client_id') <> 0) {
    $DB = new DB;
    $ClientArray = $DB->Lists(array('Query' => 'Select * from t_client where client_id=' . Globals::Get('client_id')));
    if (!is_array($ClientArray)) {
        print 'Invalid ID';
        exit();
    }
    foreach ($ClientArray as $Val) {
        $client_id = $Val->client_id;
        $client_type = $Val->client_type;
        $software_version_id = $Val->software_version_id;
        $client_name = $Val->client_name;
        $email_address = $Val->email_address;
        $address_line_1 = $Val->address_line_1;
        $address_line_2 = $Val->address_line_2;
        $city = $Val->city;
        $state = $Val->state;
        $zip = $Val->zip;
        $country = $Val->country;
        $phone = $Val->phone;
        $website = $Val->website;
        $contact_name = $Val->contact_name;
        $contact_title = $Val->contact_title;
        $contactPhone = $Val->contact_email;
        $manager_name = $Val->manager_name;
//        $manager_name_array = explode(" ", $manager_name);
//        $txtFirstName = isset($manager_name_array[0])?$manager_name_array[0]:"";
//        $txtLastName = isset($manager_name_array[1])?$manager_name_array[1]:"";
//        $txtEmail = $Val->manager_email;
//        $txtPhone = $Val->manager_phone;
        $manager_name_array = explode(" ", $contact_name);
        $txtFirstName = isset($manager_name_array[0])?$manager_name_array[0]:"";
        $txtLastName = isset($manager_name_array[1])?$manager_name_array[1]:"";
        $txtEmail = $Val->contact_email;
        $txtPhone = $Val->phone;
        $logo = $Val->logo;
        $txtuserrName = strtolower(str_replace(" ", "", $client_name));
        $txtPassword = substr( str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?" ), 0, 8 );
    }
} else {
    $client_id = 0;
}
?>

<script type="text/javascript">
function ValidCustomer()
{
	var frm=document.frmCustomer;
	
    if(frm.txtOrgName.value=="")
	{
		alert("Please enter Organization");
		frm.txtOrgName.focus();
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
		alert("Please enter State");
		frm.txtState.focus();
		return false;
	}
    
	else if(frm.txtZip.value=="")
	{
		alert("Please enter Zip");
		frm.txtZip.focus();
		return false;
	}
    
	else if(frm.txtAnswer1.value=="")
	{
		alert("Please enter Answer");
		frm.txtAnswer1.focus();
		return false;
	}
	
	else if(frm.txtAnswer2.value=="")
	{
		alert("Please enter Answer");
		frm.txtAnswer2.focus();
		return false;
	}
	
	else if(frm.txtPassword.value=="")
	{
		alert("Please enter Password");
		frm.txtPassword.focus();
		return false;
	}
	
	else if(frm.txtPassword2.value!=frm.txtPassword.value)
	{
		alert("Confirm Password doesn't match with Password");
		frm.txtPassword2.focus();
		return false;
	}
    
    else if(frm.txtuserrName.value=="")
	{
		alert("Please enter Username");
		frm.txtuserrName.focus();
		return false;
	}
    
    else if(frm.txtFirstName.value=="")
	{
		alert("Please enter First Name");
		frm.txtFirstName.focus();
		return false;
	}
    
    else if(frm.txtLastName.value=="")
	{
		alert("Please enter Last Name");
		frm.txtLastName.focus();
		return false;
	}
    
    else if(frm.txtEmail.value=="")
	{
		alert("Please enter Email");
		frm.txtEmail.focus();
		return false;
	}
    
    else if(frm.txtPhone.value=="")
	{
		alert("Please enter Phone");
		frm.txtPhone.focus();
		return false;
	}
	
    $('#portfolio_container').html('Loading...');
    $.post("ajax_pages/portfolio_manager/client_portfolio_data.php",
        {
            txtClientId: frm.txtClientId.value,
            txtOrgName: frm.txtOrgName.value,
            primary_business: frm.primary_business.value,
            txtAddress_Line1: frm.txtAddress_Line1.value,
            energystar_partner: frm.energystar_partner.value,
            txtAddress_Line2: frm.txtAddress_Line2.value,
            partner_type: frm.partner_type.value,
            txtCity: frm.txtCity.value,
            txtState: frm.txtState.value,
            question1: frm.question1.value,
            question2: frm.question2.value,
            txtAnswer1: frm.txtAnswer1.value,
            txtAnswer2: frm.txtAnswer2.value,
            txtZip: frm.txtZip.value,
            txtuserrName: frm.txtuserrName.value,
            txtFirstName: frm.txtFirstName.value,
            txtLastName: frm.txtLastName.value,
            txtEmail: frm.txtEmail.value,
            txtPhone: frm.txtPhone.value,
            txtPassword: frm.txtPassword.value
        },
    function(data,status){	
        if(data == 'success'){
            alert('client added to Portfolio Manager.');
            window.location.reload();
        }else{
            $('#portfolio_container').html(data);
        }
    });
	return false;
	
}
</script>

<div style="width:65%; float:left; margin-left:1%; font-size:13px; border:1px solid #999999; background-color:#EFEFEF;">
    <form action="" method="post" enctype="multipart/form-data" name="frmCustomer" id="frmCustomer" onsubmit="return ValidCustomer()">
        <input type="hidden" name="txtClientId" value="<?=$client_id?>">
        <table width="98%" border="0" cellspacing="0" cellpadding="3">
            <tr>
                <td colspan="4"><h2>PORTFOLIO MANAGER ACCOUNT</h2></td>
            </tr>
            <tr>
                <td width="18%">Organization</td>
                <td width="30%"><input type="text" name="txtOrgName" id="txtOrgName" class="textbox" value="<?php echo $client_name ?>" autocomplete="off" readonly/></td>
                <td width="30%">Primary Business</td>
                <td width="20%"><select name="primary_business">
                        <option>Architecture/Design Firm</option>
                        <option>Banking/Financial</option>
                        <option>Commercial Real Estate</option>
                        <option>Congregation/Faith-Based Organization</option>
                        <option>Data Center</option>
                        <option>Drinking Water Treatment/Distribution</option>
                        <option>Education</option>
                        <option>Energy Efficiency Program</option>
                        <option>Entertainment/Recreation</option>
                        <option>Food Service</option>
                        <option>Government: Local (U.S.)</option>
                        <option>Government: Outside U.S.</option>
                        <option>Government: State (U.S.)</option>
                        <option>Government: Federal (U.S.)</option>
                        <option>Healthcare</option>
                        <option>Hospitality</option>
                        <option>Legal Services</option>
                        <option>Manufacturing/Industrial</option>
                        <option>Media</option>
                        <option>Multifamily Housing</option>
                        <option>Retail</option>
                        <option>Senior Care</option>
                        <option>Service and Product Provider/Consultant</option>
                        <option>Transportation</option>
                        <option>Utility</option>
                        <option>Wastewater Treatment</option>
<!--                        <option>Other</option>-->
                    </select></td>
            </tr>
            <tr>
                <td>Address Line 1</td>
                <td><input name="txtAddress_Line1" type="text" class="textbox" id="txtAddress_Line1" value="<?php echo $address_line_1 ?>" autocomplete="off" readonly/></td>
                <td>Energy Star Partner</td>
                <td><select name="energystar_partner"><option>false</option><option>true</option></select></td>
            </tr>
            <tr>
                <td>Address Line 2</td>
                <td><input name="txtAddress_Line2" type="text" class="textbox" id="txtAddress_Line2" value="<?php echo $address_line_2 ?>" autocomplete="off" readonly/></td>
                <td>Partner Type</td>
                <td><select name="partner_type">
                        <option>Associations</option>
                        <option>Organizations that Own/Manage/Lease Buildings and Plants</option>
                        <option>Service and Product Providers</option>
                        <option>Small Businesses</option>
                        <option>Utilities and Energy Efficiency Program Sponsors</option>
                        <option>Other</option>
                    </select></td>
            </tr>
            <tr>
                <td>City</td>
                <td><input name="txtCity" type="text" class="textbox" id="txtCity" value="<?php echo $city ?>" autocomplete="off" readonly/></td>
                <td><b>Security Questions</b></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>State</td>
                <td><input name="txtState" type="text" class="textbox" id="txtState" value="<?php echo $state ?>" autocomplete="off" readonly/></td>
                <td><span>Question </span>
                <select name="question1" style="width:65%;">
                    <option value="-1">In what city did you meet your spouse/significant other?</option>
                    <option value="-2">What street/road did you live on in third grade?</option>
                    <option value="-3">What is the middle name of your youngest child?</option>
                    <option value="-4">What is your oldest cousin's first and last name?</option>
                    <option value="-5">In what city does your nearest sibling live?</option>
                    <option value="-6">In what city/town was your first job?</option>
                    <option value="-7">What was your favorite place to visit as a child?</option>
                    <option value="-8">What was your high school mascot?</option>
                    <option value="-9">What is the name of the high school you attended?</option>
                    <option value="-10">What is your preferred musical genre?</option>
                    <option value="-11">What is your birth city?</option>
                    <option value="-12">What is your favorite sports team?</option>
                    <option value="-13">What is your favorite restaurant?</option>
                    <option value="-14">What is the name of your pet?</option>
                    <option value="-15">What is your favorite hobby?</option>
                    <option value="-16">What is your favorite musical group?</option>
                    <option value="-17">What was the make of your first car?</option>
                    <option value="-18">What is your favorite movie?</option>
                </select>
                </td>
                <td style="text-align:right;"><span>Answer </span><input name="txtAnswer1" style="width:70%;" type="text" class="textbox" id="txtAnswer1" value="<?php echo $answer1 ?>" autocomplete="off" /></td>
            </tr>
            <tr>
                <td>Zip</td>
                <td><input name="txtZip" type="text" class="textbox" id="txtZip" value="<?php echo $zip ?>" autocomplete="off" readonly/></td>
                <td><span>Question </span>
                <select name="question2" style="width:65%;">
                    <option value="-1">In what city did you meet your spouse/significant other?</option>
                    <option value="-2">What street/road did you live on in third grade?</option>
                    <option value="-3">What is the middle name of your youngest child?</option>
                    <option value="-4">What is your oldest cousin's first and last name?</option>
                    <option value="-5">In what city does your nearest sibling live?</option>
                    <option value="-6">In what city/town was your first job?</option>
                    <option value="-7">What was your favorite place to visit as a child?</option>
                    <option value="-8">What was your high school mascot?</option>
                    <option value="-9">What is the name of the high school you attended?</option>
                    <option value="-10">What is your preferred musical genre?</option>
                    <option value="-11">What is your birth city?</option>
                    <option value="-12">What is your favorite sports team?</option>
                    <option value="-13">What is your favorite restaurant?</option>
                    <option value="-14">What is the name of your pet?</option>
                    <option value="-15">What is your favorite hobby?</option>
                    <option value="-16">What is your favorite musical group?</option>
                    <option value="-17">What was the make of your first car?</option>
                    <option value="-18">What is your favorite movie?</option>
                </select>
                </td>
                <td style="text-align:right;"><span>Answer </span><input name="txtAnswer2" style="width:70%;" type="text" class="textbox" id="txtAnswer2" value="<?php echo $answer2 ?>" autocomplete="off" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Portfolio Manager Login Information</strong></td>
                <td colspan="2"><strong>Portfolio Manager Contact Information</strong></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><input name="txtuserrName" type="text" class="textbox" id="txtuserrName" value="<?php echo $txtuserrName ?>" autocomplete="off"/></td>
                <td>First Name</td>
                <td><input type="text" name="txtFirstName" id="txtFirstName" class="textbox" value="<?php echo $txtFirstName ?>" autocomplete="off" readonly/></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="text" name="txtPassword" id="txtPassword" class="textbox" value="<?php echo $txtPassword ?>" autocomplete="off" /></td>
                <td>Last Name</td>
                <td><input type="text" name="txtLastName" id="txtLastName" class="textbox" value="<?php echo $txtLastName ?>" autocomplete="off" readonly/></td>
            </tr>
            <tr>
                <td>Re-type Password</td>
                <td><input type="text" name="txtPassword2" id="txtPassword2" class="textbox" value="<?php echo $txtPassword ?>" autocomplete="off" /></td>
                <td>Email</td>
                <td><input type="text" name="txtEmail" id="txtEmail" class="textbox" value="<?php echo $txtEmail ?>" autocomplete="off" readonly/></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Phone</td>
                <td><input type="text" name="txtPhone" id="txtPhone" class="textbox" value="<?php echo $txtPhone ?>" autocomplete="off" readonly/></td>
            </tr>

<!--            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><input type="hidden" name="txtCountry" id="txtCountry" value="USA" />

                    <input name="client_id" type="hidden" id="client_id" value="<?php echo $client_id; ?>" />
                    <input type="hidden" name="type" id="type" value="Customer">        <input type="submit" name="button" id="button" value="Submit" /></td>
            </tr>-->
        </table>
        <div style="border: 1px solid #999999; border-radius: 5px; margin: 0 10px 0 540px; padding: 10px 5px; width: 200px;">
            <input type="submit" name="submit"  value="SUBMIT TO ENERGY STAR" style=" background: none repeat scroll 0 0 #efefef; border: 0 none; color: #003399; font-weight: bold;">
        </div>
    </form>
</div>

<div class="clear" style="clear:both;"></div>
<?php } ?>