<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

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

if($_POST['mode']=='csv')
{
    if($_FILES['csv_file'] && $_FILES['csv_file']['type'] == 'text/csv'){
		$csvfileTemp = $_FILES['csv_file']['tmp_name'];
		$uploaded_file_name = $_FILES['csv_file']['name'];
		$csvfile = "../uploads/billing/".$_FILES['csv_file']['name'];
		if(move_uploaded_file($csvfileTemp, $csvfile)){
			if(file_exists($csvfile)) {
	
				$file = fopen($csvfile,"r");
				while($data = fgetcsv($file)){
					$content[] = $data;
				}
				fclose($file);
				//print_r($content);
                                
				if (strpos($content[0][0],'Electric') !== false) {
					$utility_account_type = 1;
				}else{
					$utility_account_type = 2;
				}
				
				$utility_account_number = $content[2][1];
				$site_id = $strSiteID;
				$building_id = $BuildingID;
				$meter_number = $content[4][1];
				$meter_exist = 0;
				$utility_account_id = "";
				
				$utility_years = "";
                $utility_bill_total = "";
                $utility_bills = "";

                for($i = 0; $i <= 500; $i=$i+16){

                    if(isset($content[$i+7][1])){

                        $year = $content[$i+7][1];
                        $year_array[] = $year;
                        
                        $utility_years[] = $year;
                        $utility_bills->{$year} = array();
                        $total_consumption = 0;
                        $total_cost = 0;

                        for($j=0; $j<12; $j++){

                            $from = $content[$i+$j+10][0];
                            $to = $content[$i+$j+10][1];
                            $consumption = $content[$i+$j+10][2];
                            $demand = $content[$i+$j+10][3];
                            $cost = $content[$i+$j+10][4];

                            $utility_bills->{$year}[$j]->from = $from; 
                            $utility_bills->{$year}[$j]->to = $to; 
                            $utility_bills->{$year}[$j]->consumption = $consumption; 
                            $utility_bills->{$year}[$j]->demand = $demand; 
                            $utility_bills->{$year}[$j]->cost = $cost; 
                            $utility_bills->{$year}[$j]->avg = $cost/$consumption;

                            $total_consumption += $consumption;
                            $total_cost += $cost;

                        }

                        $utility_bill_total->{$year} = "";
                        $utility_bill_total->{$year}->kwh = $total_consumption;
                        $utility_bill_total->{$year}->cost = $total_cost;
                        $utility_bill_total->{$year}->avg = $total_cost/$total_consumption;

                    } else {
                        break;
                    }

                }
                
                /******* Checking if Meter already exist ********/
				$strSQL = "Select * from t_utility_bills B inner join t_utility_account_meters M on B.utility_meter_id = M.utility_meter_number inner join t_utility_accounts A on A.utility_account_id = M.utility_account_id where A.utility_account_number = '".$utility_account_number."' AND A.utility_account_type = '".$utility_account_type."' AND M.meter_number = '".$meter_number."' AND year in (".implode(',', $year_array).")";
				$strRsUtilityMetersArr = $DB->Returns($strSQL);
				if(mysql_num_rows($strRsUtilityMetersArr) > 0){
					while($strRsUtilityMeters=mysql_fetch_object($strRsUtilityMetersArr))
        			{
						$meter_exist = 1;
					}
				}
				/******* Checking Meter Done ********/
                
            }
		} else {
			echo "error in uploading...";
		} 
	}else{
		echo "Please upload a csv file.";	
	}
}

else if($_POST['mode']=='save')
{
    $content = $_POST['content'];
    $content = json_decode($content);
    
    if (strpos($content[0][0],'Electric') !== false) {
        $utility_account_type = 1;
    }else{
        $utility_account_type = 2;
    }
    $utility_name = $content[1][1];
    $utility_account_number = $content[2][1];
    $site_id = $strSiteID;
    $building_id = $BuildingID;
    $rate_code = $content[3][1];
    $meter_number = $content[4][1];
    $created_by=$_SESSION['user_login']->login_id;
    $modified_by=$_SESSION['user_login']->login_id;

    /******* Checking if account already exist and create new if not exist ********/
    $strSQL = "Select * from t_utility_accounts where utility_account_number = '".$utility_account_number."' AND utility_account_type = '".$utility_account_type."'";
    $strRsUtilityAccountsArr = $DB->Returns($strSQL);
    if(mysql_num_rows($strRsUtilityAccountsArr) > 0){
        while($strRsUtilityAccounts=mysql_fetch_object($strRsUtilityAccountsArr))
        {
            $utility_account_id = $strRsUtilityAccounts->utility_account_id;
        }
    }else{
        $strSQL="Insert into t_utility_accounts(utility_account_type, utility_name, utility_account_number, site_id, building_id, doc, dom, created_by, modified_by	, delete_flag) Values ($utility_account_type, '$utility_name', '$utility_account_number', $site_id, $building_id, now(), now(), $created_by, $modified_by,0)";
        $utility_account_id=$DB->Execute($strSQL);
    }
    /******* Account Done ********/
    //echo $utility_account_id;

    /******* Checking if Meter already exist and create new if not exist ********/
    $strSQL = "Select * from t_utility_account_meters where utility_account_id = '".$utility_account_id."' AND meter_number = '".$meter_number."'";
    $strRsUtilityMetersArr = $DB->Returns($strSQL);
    if(mysql_num_rows($strRsUtilityMetersArr) > 0){
        while($strRsUtilityMeters=mysql_fetch_object($strRsUtilityMetersArr))
        {
            $utility_meter_number = $strRsUtilityMeters->utility_meter_number;
        }
    }else{
        $strSQL="Insert into t_utility_account_meters(utility_account_id,meter_number,doc) Values($utility_account_id,'$meter_number',now())";
        $utility_meter_number=$DB->Execute($strSQL);
    }
    /******* Meter Done ********/
    //echo $utility_meter_number;
    
    /******* Saving Billing Data *******/
    $strSQL="Insert into t_utility_bills(`utility_meter_id`, `year`, `from`, `to`, `consumption`, `demand`, `cost`, `doc`) Values"; 

    for($i = 0; $i <= 500; $i=$i+16){

        if(isset($content[$i+7][1])){

            $year = $content[$i+7][1];
            $year_array[] = $year;
            for($j=0; $j<12; $j++){

                $from = $content[$i+$j+10][0];
                $to = $content[$i+$j+10][1];
                $consumption = $content[$i+$j+10][2];
                $demand = $content[$i+$j+10][3];
                $cost = $content[$i+$j+10][4];

                $strSQL.="('$utility_meter_number', '$year', '$from', '$to', '$consumption', '$demand', '$cost', now()),";
            }
        } else {
            break;
        }

    }
    $strSQL = rtrim($strSQL, ",");
    
    /******* Deleting old data if any *******/
    $strSQL2="delete from t_utility_bills where utility_meter_id = '$utility_meter_number' AND year in (".implode(',', $year_array).")"; 
    $DB->Execute($strSQL2);
    /************** Deleted ***************/
    
    //echo $strSQL;
    $DB->Execute($strSQL);
    /******* Billing Data Saved *******/
    echo "data successfully saved";
    exit;
}
?>
<script type='text/javascript' src="<?php echo URL?>js/jquery.js"></script>  
<script type="text/javascript">
$(document).ready(function(){
	$('#btnUploadCsv').click(function(){
		if($('#csv_file')[0].files[0]){
			var formData = new FormData();
			formData.append('mode', 'csv');
			formData.append('building_id', "<?php echo $BuildingID;?>");
			formData.append('csv_file', $('#csv_file')[0].files[0]);
			$.ajax({
				url: "<?php echo URL?>customer/csv_billing_entry.php",
				type: "POST",
				data: formData,
				processData: false,
				contentType: false,
				success: function (data) {
					$('body').html(data);
					//window.location.reload();
				}
			});
		}
	});
    
    $('#btnSaveCsvData').click(function(){
		var formData = new FormData();
        formData.append('mode', 'save');
        formData.append('building_id', "<?php echo $BuildingID;?>");
        formData.append('content', '<?=json_encode($content)?>');
        $.ajax({
            url: "<?php echo URL?>customer/csv_billing_entry.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                alert(data);
                parent.location.reload();
            }
        });
	});
    
    $('#cancelCsvData').click(function(){
		window.parent.$('.modalCloseImg').trigger('click');
	});
	
	$('#btnCancel').click(function(){
		$('#txtUtilityName').val('');
		$('#txtUtilityAccount').val('');
		$('#EditAccountID').val('');
		$('#txtMeter1').val('');
		$('#txtMeter2').val('');
		$('#btnUploadCsv').css('width','120px');
		$('#btnUploadCsv').html('Create Account');
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
		
		
		$('#btnUploadCsv').css('width','125px');
		$('#btnUploadCsv').html('Update Account');
		$('#btnCancel').css('display','block');
		
	});
	
	
}

</script>

<link rel="stylesheet" type="text/css" href="<?php echo URL?>css/master.css">
<div style="background-color:#FFFFFF; border-radius:10px; height:350px;" class="myscroll">
	
    <div style="padding:10px; float:left; width:200px; height: 330px; border-right: 1px solid #999999; text-align: center;">
        
    	<div style="font-size:16px; text-decoration:underline; font-weight:bold;  margin-bottom:10px;">CREATE NEW ACCOUNT</div>
        <br>
        <br>
    	<div style="font-size:16px;">Upload CSV File</div>
        <div class="clear" style="height:10px;"></div>
        
        <div style="background-color:#CCCCCC; padding:3px 6px; text-transform:uppercase; border-radius:5px; cursor:pointer; border:1px solid #666666; width:60px; font-weight:bold; text-align:center; float:left; margin-left: 18px; position: relative; overflow: hidden;"><input type="file" id="csv_file" style="opacity: 0; position: absolute; cursor: pointer;" onchange="$('#fileName').html(this.value);">BROWSE</div>
        <div style="background-color:#CCCCCC; padding:3px 6px; text-transform:uppercase; border-radius:5px; cursor:pointer; border:1px solid #666666; width:60px; font-weight:bold; text-align:center; float:left; margin-left:10px;" id="btnUploadCsv">UPLOAD</div>
        
        <div class="clear"></div>       
        <br>
        <div style="font-size:14px; word-break: break-all;" id="fileName"><?=$uploaded_file_name;?></div>
    </div>
    
    <?php if(isset($utility_years)){ ?>
    <div style="float:left; margin-left:10px; padding-left:10px; padding-top:10px;">
        <div style="width:200px; float: left; width:300px;">
            <div style="font-size:16px; text-decoration:underline; font-weight:bold;  margin-bottom:10px;">ELECTRICITY</div>
            <!--<div style="float:left; width:300px;">
                
                <select name="accountName" id="accountName" style="font-size:16px; width:200px; font-weight:bold; color:#666666; float:left; font-family: UsEnergyEngineers;">
                    <option>ACCOUNT 1</option>
                    <option>ACCOUNT 2</option>
                    <option>ACCOUNT 3</option>
                </select>
                <div style='background: none repeat scroll 0 0 #cccccc; border-radius: 50%; float: left; font-weight: bold; height: 25px; margin-left: 15px; text-align: center; width: 25px;'>3</div>
            </div>
            <div class="clear"></div>  
            <div style="float:left; width:300px;">
                
                <select name="accountName" id="accountName" style="font-size:16px; width:200px; font-weight:bold; color:#666666; float:left; font-family: UsEnergyEngineers;">
                    <option>METER 1</option>
                    <option>METER 2</option>
                </select>
                <div style='background: none repeat scroll 0 0 #cccccc; border-radius: 50%; float: left; font-weight: bold; height: 25px; margin-left: 15px; text-align: center; width: 25px;'>2</div>
            </div>
            <div class="clear"></div> --> 
            <div style="float:left; width:300px;">
                
                <select name="meterMonth" id="meterMonth" onchange="monthChanged();" style="font-size:16px; width:200px; font-weight:bold; color:#666666; float:left; font-family: UsEnergyEngineers;">
                    <?php foreach($utility_years as $year){ ?>
                    <option value="<?=$year;?>"><?=$year;?></option>
                    <?php } ?>
                </select>
                <div style='background: none repeat scroll 0 0 #cccccc; border-radius: 50%; float: left; font-weight: bold; height: 25px; margin-left: 15px; text-align: center; width: 25px;'><?=sizeof($utility_years);?></div>
            </div>
            <div class="clear"></div>  
            
            <div style="float:left; width:300px; margin-top: 10px;">
            	<div>ACCOUNT NO.: <?=$utility_account_number;?></div>
                <div>METER NO.: <?=$meter_number;?></div>
                
                <?php foreach($utility_bill_total as $year => $year_total){ ?>
                    <div class="year <?=$year?>" style="display: none;">
                        <div>ELECTRIC KWH: <?= number_format($year_total->kwh,0);?></div>
                        <div>ELECTRIC COST: $<?= number_format($year_total->cost,0);?></div>
                        <div>ELECTRIC COST/KWH: $<?= number_format($year_total->avg,2);?></div>
                    </div>
                <?php } ?>
                
                <div style="margin-top: 15px;">
                    <span>CSV imported successfully</span>
                    <br>
                    <?php if($meter_exist == 1) {?>
                    <span>This Meter's data already exists</span>
                    <br>
                    <span>Do you want to update this data</span>
                    <?php } else {?>
                    <span>Do you want to add this data</span>
                    <?php } ?>
                </div>
                <div style="background-color:#CCCCCC; padding:3px 6px; text-transform:uppercase; border-radius:5px; cursor:pointer; border:1px solid #666666; width:60px; font-weight:bold; text-align:center; float:left; margin-left:10px;" id="btnSaveCsvData">YES</div>
                <div style="background-color:#CCCCCC; padding:3px 6px; text-transform:uppercase; border-radius:5px; cursor:pointer; border:1px solid #666666; width:60px; font-weight:bold; text-align:center; float:left; margin-left:10px;" id="cancelCsvData">NO</div>
            </div>
        </div>
        <div style="font-size:10px; margin-left:10px; margin-top:10px; width:300px; float:left; border:1px solid #666666;">
            <?php foreach($utility_bills as $year => $billData){ ?>
            <table class="year <?=$year;?>" width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC;" >
                <tr style="background-color:#EFEFEF; font-weight:bold; font-size:9px;">
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">DATE</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">MBTU</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">COST</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$/MBTU</td>
                </tr>
                <?php foreach($billData as $billDataValue){ ?>
                <tr>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=$billDataValue->to;?></td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;"><?=number_format($billDataValue->consumption, 0);?></td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($billDataValue->cost, 0);?></td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">$<?=number_format($billDataValue->avg, 2);?></td>
                </tr>
                <?php } ?>       
            </table>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <div class="clear"></div>
    
</div>
<script>
    function monthChanged(){
        var year = $('#meterMonth').val();
        $('.year').hide();
        $('.'+year).show();
    }
    monthChanged();
</script>