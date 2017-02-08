<?php
ob_start();
session_start();

require_once("../configure.php");
require_once(AbsPath . "classes/all.php");
require_once(AbsPath . "classes/customer.class.php");
require_once(AbsPath . "classes/building.class.php");
$Building = new Building;
$DB = new DB;
if (Globals::Get('id') <> '') {
    $client_id = Globals::Get('id');
} else {
    $client_id = Globals::Get('client_id');
}


if (Globals::Get('client_id') <> '' && Globals::Get('building_id') <> '' and Globals::Get('mode') == '') {
    $strSQL = "Select * from t_building where client_id=" . Globals::Get('client_id') . " And building_id=" . Globals::Get('building_id');
    $strBuildingRsArr = $DB->Lists(array('Query' => $strSQL));
    if (!is_array($strBuildingRsArr)) {
        print 'Illegal Operation';
        exit();
    }

    foreach ($strBuildingRsArr as $Val) {
        $building_id = $Val->building_id;
        $site_id = $Val->site_id;
        $building_name = $Val->building_name;
        $location = $Val->location;
        $address_line1 = $Val->address_line1;
        $address_line2 = $Val->address_line2;
        $city = $Val->city;
        $state = $Val->state;
        $zip = $Val->zip;
        $time_zone = $Val->time_zone;
        $country = $Val->country;
        $square_feet = number_format($Val->square_feet);
        $gas_utility = $Val->gas_utility;
        $water_utility = $Val->water_utility;
        $climate_zone = $Val->climate_zone;

        $electricity_utility = $Val->electricity_utility;
        $cost_gas = $Val->cost_gas;
        $cost_electric = $Val->cost_electric;

        $electric_account = $Val->electric_account;
        $gas_account = $Val->gas_account;
        $electric_rate = $Val->electric_rate;
        $gas_rate = $Val->gas_rate;

        $contact_name = $Val->contact_name;
        $contact_email = $Val->contact_email;
        $department = $Val->department;
        $telephone = $Val->telephone;
        $note = $Val->note;
    }
} elseif (Globals::Get('client_id') <> '' && Globals::Get('building_id') <> '' and Globals::Get('mode') == 'delete') {
    $Building->DeleteBuilding(Globals::Get('building_id'), Globals::Get('client_id'));
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            ProjectDetails('<?php echo Globals::Get('client_id'); ?>');
        });
    </script>
    <?php
    exit();
}
?>

<?php
if ($_POST) {
    $myArr = array(
        'site_id' => $_POST['site_id'],
        'client_id' => $_POST['client_id'],
        'building_name' => $_POST['txtBuildingName'],
        'location' => $_POST['txtLocation'],
        'address_line1' => $_POST['txtAddressLine1'],
        'address_line2' => $_POST['txtAddressLine2'],
        'city' => $_POST['txtCity'],
        'state' => $_POST['txtState'],
        'zip' => $_POST['txtZip'],
        'time_zone' => $_POST['txtTimeZone'],
        'country' => $_POST['txtCountry'],
        'square_feet' => ($_POST['txtMeasurement'] == '' ? '0' : $_POST['txtMeasurement']),
        'gas_utility' => $_POST['txtGasUtility'],
        'electricity_utility' => $_POST['txtElectricUtility'],
        'climate_zone' => $_POST['txtClimateZone'],
        'water_utility' => $_POST['txtWaterUtility'],
        'cost_gas' => $_POST['txtGasCost'],
        'cost_electric' => $_POST['txtElectricCost'],
        'electric_account' => $_POST['txtElectricAccount'],
        'gas_account' => $_POST['txtGasAccount'],
        'electric_rate' => $_POST['txtElectricRateAccount'],
        'gas_rate' => $_POST['txtGasRateAccount'],
        'contact_name' => $_POST['txtContactName'],
        'contact_email' => $_POST['txtContactEmail'],
        'department' => $_POST['txtDepartment'],
        'telephone' => $_POST['txtTelephone'],
        'note' => $_POST['txtNote'],
        'created_by' => $_SESSION['user_login']->login_id,
        'modified_by' => $_SESSION['user_login']->login_id,
        'delete_flag' => '0'
    );


    $Building->setVal($myArr);

    if ($_POST['building_id'] == '') {
        $building_id = $Building->InsertBuilding();
        print '<div style="font-family:Arial, Helvetica, sans-serif; color:#006600; margin:45px 0px 0px 0px; font-size:18px;">
		Successfully Added!</div>';
    } else {
        $building_id = $_POST['building_id'];
        $Building->building_id = $_POST['building_id'];
        $Building->UpdateBuilding();
        print '<div style="font-family:Arial, Helvetica, sans-serif; color:#006600; margin:45px 0px 0px 0px; font-size:18px;">
		Successfully Updated!</div>';
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            ProjectDetails('<?php echo $_POST['client_id']; ?>');
        });
    </script>
    <?php
    exit();
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#cmdClose').click(function () {
            $('#Building_Container').slideUp();
        });

        $('#cmdSubmit').click(function () {

            $.post("ajax_pages/building.php",
                    {
                        site_id: $('#ddlSiteBuilding').val(),
                        txtBuildingName: $('#txtBuildingName').val(),
                        txtLocation: $('#txtLocation').val(),
                        txtAddressLine1: $('#txtAddressLine1').val(),
                        txtMeasurement: ($('#txtMeasurement').val()).replace(",", ""),
                        txtAddressLine2: $('#txtAddressLine2').val(),
                        txtContactName: $('#txtContactName').val(),
                        txtCity: $('#txtCity').val(),
                        txtContactEmail: $('#txtContactEmail').val(),
                        txtState: $('#txtState').val(),
                        txtDepartment: $('#txtDepartment').val(),
                        txtZip: $('#txtZip').val(),
                        txtTimeZone: $('#time_zone').val(),
                        txtTelephone: $('#txtTelephone').val(),
                        txtCountry: $('#txtCountry').val(),
                        txtGasUtility: $('#txtGasUtility').val(),
                        txtElectricUtility: $('#txtElectricUtility').val(),
                        txtWaterUtility: $('#txtWaterUtility').val(),
                        txtClimateZone: $('#txtClimateZone').val(),
                        client_id: $('#client_id').val(),
                        building_id: $('#building_id').val(),
                        txtNote: $('#txtNote').val()
                    },
            function (data, status) {
                $('#Building_Container').html(data);
            });

        });

    });

    function ValidBuilding()
    {
        var frm = document.frmBuilding;
        if (frm.ddlSite.value == "")
        {
            alert("Select a site");
            frm.ddlSite.focus();
            return false;
        }
        else if (frm.txtBuildingName.value == "")
        {
            alert("Enter building name");
            frm.txtBuildingName.focus();
            return false;
        }
        return true;
    }

</script>

<form id="frmBuilding" name="frmBuilding" method="post" action="" onsubmit="return ValidBuilding();">

    <table width="98%" border="0" cellspacing="1" cellpadding="5">
        <tr>
            <td colspan="2"><h2>Building Information</h2></td>
        </tr>
        <tr>
            <td colspan="2"><select name="ddlSiteBuilding" id="ddlSiteBuilding" title="Site"><?php $Building->FetchSites($client_id, $site_id) ?></select></td>
        </tr>
        <tr>
            <td width="22%">
                <input type="text" title="Building Name" name="txtBuildingName" id="txtBuildingName" placeholder="Building Name" class="TextBox" value="<?php echo $building_name; ?>" />    </td>
            <td width="78%"><input type="text" title="Location" name="txtLocation" id="txtLocation" placeholder="Location" class="TextBox" value="<?php echo $location; ?>" /></td>
        </tr>
        <tr>
            <td><input type="text" title="Address Line 1" name="txtAddressLine1" id="txtAddressLine1" placeholder="Address Line1" class="TextBox" value="<?php echo $address_line1; ?>" /></td>
            <td><input type="text" title="Measurement in Sqr. Ft." name="txtMeasurement" id="txtMeasurement" placeholder="Measurement in Sqr. Ft." class="TextBox" value="<?php echo $square_feet; ?>" /></td>
        </tr>
        <tr>
            <td><input type="text" title="Address Line 2" name="txtAddressLine2" id="txtAddressLine2" placeholder="Address Line2" class="TextBox" value="<?php echo $address_line2; ?>" /></td>
            <td>
                <input type="text" title="Contact Name" name="txtContactName" id="txtContactName" placeholder="Contact Name" class="TextBox" value="<?php echo $contact_name; ?>" />    </td>
        </tr>
        <tr>
            <td><input type="text" title="City" name="txtCity" id="txtCity" placeholder="City" class="TextBox" value="<?php echo $city; ?>" /></td>
            <td>
                <input type="text" title="Contact Email" name="txtContactEmail" id="txtContactEmail" placeholder="Contact Email" class="TextBox" value="<?php echo $contact_email; ?>" />    </td>
        </tr>
        <tr>
            <td><input type="text" title="State" name="txtState" id="txtState" placeholder="State" class="TextBox" value="<?php echo $state; ?>" /></td>
            <td>
                <input type="text" title="Department Name" name="txtDepartment" id="txtDepartment" placeholder="Department Name" class="TextBox" value="<?php echo $department; ?>" />    </td>
        </tr>
        <tr>
            <td><input type="text" title="Zip Code" name="txtZip" id="txtZip" placeholder="Zip" class="TextBox" value="<?php echo $zip; ?>" /></td>
            <td>
                <input type="text" title="Telephone" name="txtTelephone" id="txtTelephone" placeholder="Telephone" class="TextBox" value="<?php echo $telephone; ?>" />    </td>
        </tr>

        <tr>
            <td colspan="2">
                <!--without day light saving-->
<!--                <select name="time_zone" id="time_zone" title="Time Zone">
                    <option value="">Select Time Zone</option>
                    <option value="AST_-4" <?=$time_zone=="AST_-4"?"selected":""?>>AST (Atlantic Time Zone)</option>
                    <option value="EST_-5" <?=$time_zone=="EST_-5"?"selected":""?>>EST (Eastern Time Zone)</option>
                    <option value="CST_-6" <?=$time_zone=="CST_-6"?"selected":""?>>CST (Central Time Zone)</option>
                    <option value="MST_-7" <?=$time_zone=="MST_-7"?"selected":""?>>MST (Mountain Time Zone)</option>
                    <option value="PST_-8" <?=$time_zone=="PST_-8"?"selected":""?>>PST (Pacific Time Zone)</option>
                    <option value="AKST_-9" <?=$time_zone=="AKST_-9"?"selected":""?>>AKST (Alaska Time Zone)</option>
                    <option value="HAST_-10" <?=$time_zone=="HAST_-10"?"selected":""?>>HAST (Hawaii–Aleutian Time Zone)</option>
                    <option value="SST_-11" <?=$time_zone=="SST_-11"?"selected":""?>>SST (Samoa Time Zone (UTC−11))</option>
                    <option value="ChST_10" <?=$time_zone=="ChST_10"?"selected":""?>>ChST (Chamorro Time Zone (UTC+10))</option>
                </select>-->
                <!--with day light saving-->
                <select name="time_zone" id="time_zone" title="Time Zone">
                    <option value="">Select Time Zone</option>
                    <option value="AST" <?=$time_zone=="AST"?"selected":""?>>AST (Atlantic Time Zone)</option>
                    <option value="EST" <?=$time_zone=="EST"?"selected":""?>>EST (Eastern Time Zone)</option>
                    <option value="CST" <?=$time_zone=="CST"?"selected":""?>>CST (Central Time Zone)</option>
                    <option value="MST" <?=$time_zone=="MST"?"selected":""?>>MST (Mountain Time Zone)</option>
                    <option value="PST" <?=$time_zone=="PST"?"selected":""?>>PST (Pacific Time Zone)</option>
                    <option value="AKST" <?=$time_zone=="AKST"?"selected":""?>>AKST (Alaska Time Zone)</option>
                    <option value="HAST" <?=$time_zone=="HAST"?"selected":""?>>HAST (Hawaii–Aleutian Time Zone)</option>
                    <option value="SST" <?=$time_zone=="SST"?"selected":""?>>SST (Samoa Time Zone (UTC−11))</option>
                    <option value="ChST" <?=$time_zone=="ChST"?"selected":""?>>ChST (Chamorro Time Zone (UTC+10))</option>
                </select>
            </td>
        </tr>
        <tr>
            <td valign="top"><input type="text" title="Zip Code" name="txtGasUtility" id="txtGasUtility" placeholder="Gas Utility" class="TextBox" value="<?php echo $gas_utility; ?>" /></td>
            <td valign="top"><input type="text" title="Zip Code" name="txtElectricUtility" id="txtElectricUtility" placeholder="Electricity Utility" class="TextBox" value="<?php echo $electricity_utility; ?>" /></td>
        </tr>
        <tr>
            <td valign="top"><input type="text" title="Zip Code" name="txtWaterUtility" id="txtWaterUtility" placeholder="Water Utility" class="TextBox" value="<?php echo $water_utility; ?>" /></td>
            <td valign="top">
                <select name="txtClimateZone" id="txtClimateZone">
                    <option value="" selected="selected">ASHRAE Climate Zone</option>
                    <optgroup label="Climate Zone Number 1">
                        <option value="Zone 1A" <?php if ($climate_zone == 'Zone 1A') {
    echo 'selected="selected"';
} ?> >Zone 1A</option>
                        <option value="Zone 1B" <?php if ($climate_zone == 'Zone 1B') {
    echo 'selected="selected"';
} ?> >Zone 1B</option>
                    </optgroup>
                    <optgroup label="Climate Zone Number 2">
                        <option value="Zone 2A" <?php if ($climate_zone == 'Zone 2A') {
    echo 'selected="selected"';
} ?> >Zone 2A</option>
                        <option value="Zone 2B" <?php if ($climate_zone == 'Zone 2B') {
    echo 'selected="selected"';
} ?> >Zone 2B</option>
                    </optgroup>

                    <optgroup label="Climate Zone Number 3">
                        <option value="Zone 3A" <?php if ($climate_zone == 'Zone 3A') {
    echo 'selected="selected"';
} ?> >Zone 3A</option>
                        <option value="Zone 3B" <?php if ($climate_zone == 'Zone 3B') {
    echo 'selected="selected"';
} ?> >Zone 3B</option>
                        <option value="Zone 3C" <?php if ($climate_zone == 'Zone 3C') {
    echo 'selected="selected"';
} ?> >Zone 3C</option>
                    </optgroup>

                    <optgroup label="Climate Zone Number 4">
                        <option value="Zone 4A" <?php if ($climate_zone == 'Zone 4A') {
    echo 'selected="selected"';
} ?> >Zone 4A</option>
                        <option value="Zone 4B" <?php if ($climate_zone == 'Zone 4B') {
    echo 'selected="selected"';
} ?> >Zone 4B</option>
                        <option value="Zone 4C" <?php if ($climate_zone == 'Zone 4C') {
    echo 'selected="selected"';
} ?> >Zone 4C</option>
                    </optgroup>

                    <optgroup label="Climate Zone Number 5">
                        <option value="Zone 5A" <?php if ($climate_zone == 'Zone 5A') {
    echo 'selected="selected"';
} ?> >Zone 5A</option>
                        <option value="Zone 5B" <?php if ($climate_zone == 'Zone 5B') {
    echo 'selected="selected"';
} ?> >Zone 5B</option>
                        <option value="Zone 5C" <?php if ($climate_zone == 'Zone 5C') {
    echo 'selected="selected"';
} ?> >Zone 5C</option>
                    </optgroup>

                    <optgroup label="Climate Zone Number 6">
                        <option value="Zone 6A" <?php if ($climate_zone == 'Zone 6A') {
    echo 'selected="selected"';
} ?> >Zone 6A</option>
                        <option value="Zone 6B" <?php if ($climate_zone == 'Zone 6B') {
    echo 'selected="selected"';
} ?> >Zone 6B</option>            
                    </optgroup>

                    <optgroup label="Climate Zone Number 7">
                        <option value="Zone 7A" <?php if ($climate_zone == 'Zone 7A') {
    echo 'selected="selected"';
} ?> >Zone 7A</option>
                        <option value="Zone 7B" <?php if ($climate_zone == 'Zone 7B') {
    echo 'selected="selected"';
} ?> >Zone 7B</option>            
                    </optgroup>

                    <optgroup label="Climate Zone Number 8">
                        <option value="Zone 8A" <?php if ($climate_zone == 'Zone 8A') {
    echo 'selected="selected"';
} ?> >Zone 8A</option>
                        <option value="Zone 8B" <?php if ($climate_zone == 'Zone 8B') {
    echo 'selected="selected"';
} ?> >Zone 8B</option>            
                    </optgroup>
                </select>
                <a href="http://en.openei.org/wiki/ASHRAE_Climate_Zones" target="_blank">Check Climate Zone</a></td>
        </tr>
        <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top"><input type="button" name="cmdSubmit" id="cmdSubmit" value="Save Building Data" class="Button" style="font-weight:bold; padding:3px;" />
                <input type="button" name="cmdClose" id="cmdClose" value="Close" style="font-weight:bold; padding:3px;" />
                <input type="hidden" name="txtCountry" id="txtCountry" value="USA" />
                <input name="client_id" type="hidden" id="client_id" value="<?php echo $client_id; ?>" />
                <input name="building_id" type="hidden" id="building_id" value="<?php echo $building_id; ?>" />
                <input name="txtNote" id="txtNote" value="<?php echo $note; ?>" type="hidden" />
            </td>
        </tr>
    </table>
</form>
