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
    //print_r($_POST);exit;
    $CreateProperty='
        <property>
            <name>'.$_POST['txtPropertyName'].'</name>
            <constructionStatus>'.$_POST['constructionStatus'].'</constructionStatus>
            <primaryFunction>'.$_POST['primary_business'].'</primaryFunction>
            <grossFloorArea temporary="true" units="Square Feet">
                <value>'.$_POST['txtArea'].'</value>
            </grossFloorArea>
            <yearBuilt>'.$_POST['year_built'].'</yearBuilt>
            <address postalCode="'.$_POST['txtZip'].'" address1="'.$_POST['txtAddress_Line1'].'" city="'.$_POST['txtCity'].'" state="'.$_POST['txtState'].'" country="US"/>
            <numberOfBuildings>'.$_POST['no_of_buildings'].'</numberOfBuildings>
            <isFederalProperty>'.$_POST['federal'].'</isFederalProperty>
            <occupancyPercentage>'.$_POST['occupancy'].'</occupancyPercentage>
        </property>';
    //echo $CreateProperty;exit;
    $response = Globals::CallAPI("POST",'https://'.$adminUsername.':'.$adminPassword.'@portfoliomanager.energystar.gov/wstest/account/'.$_POST['customer_id'].'/property', $CreateProperty);
    //echo $customer_id;
    //$customer_id = "";
    //echo $response;
    //echo "success";exit;
    if(simplexml_load_string($response)){
        $response = new SimpleXMLElement($response);
        if($response['status'][0]=="Ok"){
            $property_id = $response->id[0];
            //echo $property_id;
            $DB = new DB;
            $strSQL = "update t_building set portfolio_status = 1, property_id = '$property_id', primary_function = '".$_POST['primary_business']."', construction_status = '".$_POST['constructionStatus']."', year_built = '".$_POST['year_built']."', no_of_buildings = '".$_POST['no_of_buildings']."', federal = '".$_POST['federal']."', occupancy = '".$_POST['occupancy']."', dom = now()  where building_id = ".$_POST['txtBildingId'];
            $DB->Returns($strSQL);
            echo "success";
        }else{
            echo "Please update building info.";
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
    
if (Globals::Get('building_id') <> '' and Globals::Get('building_id') <> 0) {
    $DB = new DB;
    $BuildingArray = $DB->Lists(array('Query' => 'Select B.*, C.customer_id from t_building B left join t_portfolio_client C on B.client_id = C.client_id  where building_id=' . Globals::Get('building_id')));
    if (!is_array($BuildingArray)) {
        print 'Invalid ID';
        exit();
    }
    foreach ($BuildingArray as $Val) {
        $building_id = $Val->building_id;
        $client_id = $Val->client_id;
        $building_name = $Val->building_name;
        $location = $Val->location;
        $address_line1 = $Val->address_line1;
        $address_line2 = $Val->address_line2;
        $city = $Val->city;
        $state = $Val->state;
        $zip = $Val->zip;
        $country = $Val->country;
        $time_zone = $Val->time_zone;
        $square_feet = $Val->square_feet;
        $contact_name = $Val->contact_name;
        $contact_email = $Val->contact_email;
        $department = $Val->department;
        $telephone = $Val->telephone;
        $portfolio_status = $Val->portfolio_status;
        $property_id = $Val->property_id;
        $customer_id = $Val->customer_id;
    }
} else {
    $client_id = 0;
}
?>

<script type="text/javascript">
function ValidProperty()
{
	var frm=document.frmCustomer;
	
//    if(frm.txtOrgName.value=="")
//	{
//		alert("Please enter Organization");
//		frm.txtOrgName.focus();
//		return false;
//	}
//    
//	else if(frm.txtAddress_Line1.value=="")
//	{
//		alert("Please enter Address Line 1");
//		frm.txtAddress_Line1.focus();
//		return false;
//	}
//    
//	else if(frm.txtCity.value=="")
//	{
//		alert("Please enter City");
//		frm.txtCity.focus();
//		return false;
//	}
//    
//	else if(frm.txtState.value=="")
//	{
//		alert("Please enter State");
//		frm.txtState.focus();
//		return false;
//	}
//    
//	else if(frm.txtZip.value=="")
//	{
//		alert("Please enter Zip");
//		frm.txtZip.focus();
//		return false;
//	}
//    
//	else if(frm.txtAnswer1.value=="")
//	{
//		alert("Please enter Answer");
//		frm.txtAnswer1.focus();
//		return false;
//	}
//	
//	else if(frm.txtAnswer2.value=="")
//	{
//		alert("Please enter Answer");
//		frm.txtAnswer2.focus();
//		return false;
//	}
//	
//	else if(frm.txtPassword.value=="")
//	{
//		alert("Please enter Password");
//		frm.txtPassword.focus();
//		return false;
//	}
//	
//	else if(frm.txtPassword2.value!=frm.txtPassword.value)
//	{
//		alert("Confirm Password doesn't match with Password");
//		frm.txtPassword2.focus();
//		return false;
//	}
//    
//    else if(frm.txtuserrName.value=="")
//	{
//		alert("Please enter Username");
//		frm.txtuserrName.focus();
//		return false;
//	}
//    
//    else if(frm.txtFirstName.value=="")
//	{
//		alert("Please enter First Name");
//		frm.txtFirstName.focus();
//		return false;
//	}
//    
//    else if(frm.txtLastName.value=="")
//	{
//		alert("Please enter Last Name");
//		frm.txtLastName.focus();
//		return false;
//	}
//    
//    else if(frm.txtEmail.value=="")
//	{
//		alert("Please enter Email");
//		frm.txtEmail.focus();
//		return false;
//	}
//    
//    else if(frm.txtPhone.value=="")
//	{
//		alert("Please enter Phone");
//		frm.txtPhone.focus();
//		return false;
//	}
	
    $('#portfolio_container').html('Loading...');
    $.post("ajax_pages/portfolio_manager/property_portfolio_data.php",
        {
            txtClientId: frm.txtClientId.value,
            txtBildingId: frm.txtBildingId.value,
            customer_id: frm.customer_id.value,
            
            txtPropertyName: frm.txtPropertyName.value,
            txtArea: frm.txtArea.value,
            txtAddress_Line1: frm.txtAddress_Line1.value,
            txtAddress_Line2: frm.txtAddress_Line2.value,
            txtCity: frm.txtCity.value,
            txtState: frm.txtState.value,
            txtZip: frm.txtZip.value,
            
            primary_business: frm.primary_business.value,
            constructionStatus: frm.constructionStatus.value,
            year_built: frm.year_built.value,
            no_of_buildings: frm.no_of_buildings.value,
            federal: frm.federal.value,
            occupancy: frm.occupancy.value,
            
        },
    function(data, status){	
        if(data == 'success'){
            alert('property added to Portfolio Manager.');
            window.location.reload();
        }else{
            $('#portfolio_container').html(data);
        }
    });
	return false;
	
}
</script>

<div style="width:65%; float:left; margin-left:1%; font-size:13px; border:1px solid #999999; background-color:#EFEFEF;">
    <form action="" method="post" enctype="multipart/form-data" name="frmCustomer" id="frmCustomer" onsubmit="return ValidProperty()">
        <input type="hidden" name="txtClientId" value="<?=$client_id?>">
        <input type="hidden" name="txtBildingId" value="<?=$building_id?>">
        <input type="hidden" name="customer_id" value="<?=$customer_id?>">
        <table width="98%" border="0" cellspacing="0" cellpadding="3">
            <tr>
                <td colspan="4"><h2>PORTFOLIO MANAGER PROPERTY</h2></td>
            </tr>
            <tr>
                <td width="20%">Property Name</td>
                <td width="30%"><input type="text" name="txtPropertyName" id="txtPropertyName" class="textbox" value="<?php echo $building_name ?>" autocomplete="off" readonly/></td>
                <td width="20%">Primary Function</td>
                <td width="30%"><select name="primary_business">
                        <option>Adult Education</option>
                        <option>Ambulatory Surgical Center</option>
                        <option>Aquarium, Automobile Dealership</option>
                        <option>Bank Branch</option>
                        <option>Bar/Nightclub</option>
                        <option>Barracks, Bowling Alley</option>
                        <option>Casino, College/University</option>
                        <option>Convenience Store with Gas Station</option>
                        <option>Convenience Store without Gas Station</option>
                        <option>Convention Center</option>
                        <option>Courthouse, Data Center</option>
                        <option>Distribution Center</option>
                        <option>Drinking Water Treatment & Distribution</option>
                        <option>Enclosed Mall</option>
                        <option>Energy/Power Station</option>
                        <option>Fast Food Restaurant</option>
                        <option>Financial Office, Fire Station</option>
                        <option>Fitness Center/Health Club/Gym</option>
                        <option>Food Sales</option>
                        <option>Food Service</option>
                        <option>Hospital (General Medical & Surgical)</option>
                        <option>Hotel, Ice/Curling Rink</option>
                        <option>Indoor Arena, K-12 School</option>
                        <option>Laboratory</option>
                        <option>Library</option>
                        <option>Lifestyle Center</option>
                        <option>Mailing Center/Post Office</option>
                        <option>Manufacturing/Industrial Plant</option>
                        <option>Medical Office</option>
                        <option>Mixed Use Property</option>
                        <option>Movie Theater</option>
                        <option>Multifamily Housing</option>
                        <option>Museum</option>
                        <option>Non-Refrigerated Warehouse</option>
                        <option>Office</option>
                        <option>Other - Education</option>
                        <option>Other - Entertainment/Public Assembly</option>
                        <option>Other - Lodging/Residential</option>
                        <option>Other - Mall</option>
                        <option>Other - Public Services</option>
                        <option>Other - Recreation</option>
                        <option>Other - Restaurant/Bar</option>
                        <option>Other - Services</option>
                        <option>Other - Stadium</option>
                        <option>Other - Technology/Science</option>
                        <option>Other - Utility</option>
                        <option>Other</option>
                        <option>Other/Specialty Hospital</option>
                        <option>Outpatient Rehabilitation/Physical Therapy</option>
                        <option>Parking</option>
                        <option>Performing Arts</option>
                        <option>Personal Services (Health/Beauty, Dry Cleaning, etc)</option>
                        <option>Police Station</option>
                        <option>Pre-school/Daycare</option>
                        <option>Prison/Incarceration</option>
                        <option>Race Track</option>
                        <option>Refrigerated Warehouse</option>
                        <option>Repair Services (Vehicle, Shoe, Locksmith, etc)</option>
                        <option>Residence Hall/Dormitory</option>
                        <option>Residential Care Facility</option>
                        <option>Restaurant</option>
                        <option>Retail Store</option>
                        <option>Roller Rink</option>
                        <option>Self-Storage Facility</option>
                        <option>Senior Care Community</option>
                        <option>Single Family Home</option>
                        <option>Social/Meeting Hall</option>
                        <option>Stadium (Closed)</option>
                        <option>Stadium (Open)</option>
                        <option>Strip Mall</option>
                        <option>Supermarket/Grocery Store</option>
                        <option>Swimming Pool</option>
                        <option>Transportation Terminal/Station</option>
                        <option>Urgent Care/Clinic/Other Outpatient</option>
                        <option>Veterinary Office</option>
                        <option>Vocational School</option>
                        <option>Wastewater Treatment Plant</option>
                        <option>Wholesale Club/Supercenter</option>
                        <option>Worship Facility</option>
                        <option>Zoo</option>
                    </select></td>
            </tr>
            <tr>
                <td>Floor Area (sq.ft.)</td>
                <td><input name="txtArea" type="text" class="textbox" id="txtArea" value="<?php echo $square_feet ?>" autocomplete="off" readonly/></td>
                <td>Construction Status</td>
                <td><select name="constructionStatus"><option>Existing</option><option>Project</option><option>Test</option></select></td>
            </tr>
            <tr>
                <td>Address Line 1</td>
                <td><input name="txtAddress_Line1" type="text" class="textbox" id="txtAddress_Line1" value="<?php echo $address_line1 ?>" autocomplete="off" readonly/></td>
                <td>Year built</td>
                <td><select name="year_built">
                        <?php for($i=date('Y'); $i>1950; $i--){?>
                            <option><?=$i?></option>
                        <?php }?>
                    </select></td>
            </tr>
            <tr>
                <td>Address Line 2</td>
                <td><input name="txtAddress_Line2" type="text" class="textbox" id="txtAddress_Line2" value="<?php echo $address_line2 ?>" autocomplete="off" readonly/></td>
                <td>No. of Buildings</td>
                <td><select name="no_of_buildings">
                        <?php for($i=1; $i<=10; $i++){?>
                            <option><?=$i?></option>
                        <?php }?>
                    </select></td>
            </tr>
            <tr>
                <td>City</td>
                <td><input name="txtCity" type="text" class="textbox" id="txtCity" value="<?php echo $city ?>" autocomplete="off" readonly/></td>
                <td>Federal Property</td>
                <td><select name="federal"><option>false</option><!--<option>true</option>--></select></td>
            </tr>
            <tr>
                <td>State</td>
                <td><input name="txtState" type="text" class="textbox" id="txtState" value="<?php echo $state ?>" autocomplete="off" readonly/></td>
                <td>Percentage Occupied (%)</td>
                <td>
                    <select name="occupancy">
                        <?php for($i=100; $i>=0; $i=$i-5){?>
                            <option><?=$i?></option>
                        <?php }?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Zip</td>
                <td><input name="txtZip" type="text" class="textbox" id="txtZip" value="<?php echo $zip ?>" autocomplete="off" readonly/></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <div style="border: 1px solid #999999; border-radius: 5px; margin: 0 10px 0 540px; padding: 10px 5px; width: 200px;">
            <input type="submit" name="submit"  value="SUBMIT TO ENERGY STAR" style=" background: none repeat scroll 0 0 #efefef; border: 0 none; color: #003399; font-weight: bold;">
        </div>
    </form>
</div>

<div class="clear" style="clear:both;"></div>
<?php } ?>