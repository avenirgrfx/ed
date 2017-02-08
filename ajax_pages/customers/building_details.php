<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;

$building_id= $_GET['building_id'];
if($building_id=="" or $building_id==0)
	exit();

$strSQL="Select * from t_building where building_id=$building_id";
$strRsBuildingDetailsArr=$DB->Returns($strSQL);
while($strRsBuildingDetails=mysql_fetch_object($strRsBuildingDetailsArr))
{
	$building_name=$strRsBuildingDetails->building_name;
	$address_line1=$strRsBuildingDetails->address_line1;
	$address_line2=$strRsBuildingDetails->address_line2;
	$city=$strRsBuildingDetails->city;
	$state=$strRsBuildingDetails->state;
	$zip=$strRsBuildingDetails->zip;
	$time_zone=$strRsBuildingDetails->time_zone;
    $daylight_saving = $strRsBuildingDetails->daylight_saving;
	$country=$strRsBuildingDetails->country;
	$square_feet=$strRsBuildingDetails->square_feet;
	$gas_utility=$strRsBuildingDetails->gas_utility;
	$electricity_utility=$strRsBuildingDetails->electricity_utility;
	$water_utility=$strRsBuildingDetails->water_utility;
	$climate_zone=$strRsBuildingDetails->climate_zone;
	$nodemap=$strRsBuildingDetails->nodemap;
	
	$strSQL="Select count(*) as Rooms from t_room where building_id=$building_id";
	$strRsRoomsArr=$DB->Returns($strSQL);
	while($strRsRooms=mysql_fetch_object($strRsRoomsArr))
	{
		$Rooms=$strRsRooms->Rooms;
	}
	
}
?>
<script>
    var building_name = "<?=$building_name;?>";
    var time_zone = "<?=$time_zone;?>";
    <?php 
        $timezoneOffset = Globals::GetTimezoneOffset($time_zone, $daylight_saving);
    ?>
    var time_zone_offset = <?=$timezoneOffset;?>;
    
    Date.prototype.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    Date.prototype.getMonthName = function() {
        return this.monthNames[this.getMonth()];
    };
    
    Date.prototype.toCustomDate = function() {
        var months = this.getMonthName();
        var days = this.getDate();
        var year = this.getFullYear();
        return (months+" "+('0' + days).slice(-2)+", "+year);
    };
    
    Date.prototype.toCustomTime = function() {
        var hours = this.getHours();
        var p = 'AM';
        if (hours > 12) {
            hours -= 12;
            p = 'PM';
        } else if (hours === 0) {
           hours = 12;
        } else if(hours === 12){
            p = 'PM';
        }
        var minutes = this.getMinutes();
        return (('0' + hours).slice(-2)+":"+('0' + minutes).slice(-2)+" "+p);
    };
    
    function digitalClock(){
        var d = new Date();
        var utc = d.getTime() + (d.getTimezoneOffset() * 60000);
        var new_date = new Date(utc + (3600000*time_zone_offset));
        $('#date_with_time_zone').html(building_name+ " " + new_date.toCustomTime('%H:%M %p') + (time_zone&&time_zone!=""?" (" +time_zone+ ")":"") +" | " + new_date.toCustomDate('%B %d, %Y'));
        setTimeout(digitalClock, 60000);
    }
    digitalClock();
    
    <?php 
    $time_zone_code = Globals::GetTimezoneCode($time_zone);
    date_default_timezone_set($time_zone_code);
    ?>
    $(".monthPicker").datepicker("option", "maxDate", new Date("<?=date('m/d/Y')?>"));
    $(".monthPicker").val("<?php echo date('F Y') ?>");
    
    $(".monthPicker").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });
    
    <?php if($nodemap){ ?>
        $("#Graph_Bottom_Options_4 a").attr("href", "<?php echo URL.'uploads/nodemap/'.$nodemap; ?>");
        $("#Graph_Bottom_Options_4 a").attr("target", "_blank");
    <?php }else{ ?>
        $("#Graph_Bottom_Options_4 a").attr("href", "javascript:void();");
        $("#Graph_Bottom_Options_4 a").attr("target", "");
    <?php } ?>
    
//    $('#date_with_time_zone').html("<?php 
//        if($time_zone && $time_zone != ""){
//            date_default_timezone_set($time_zone);
//            //echo $building_name." ".date("g:i a F dS, Y"). "($time_zone)";
//            echo $building_name;
//            echo " ".date("g:i a");
//            echo " ($time_zone)";
//            echo " | ".date("F dS, Y");
//        }else{
//            date_default_timezone_set('EST');
//            //echo $building_name." ".date("g:i a F dS, Y"); 
//            echo $building_name;
//            echo " ".date("g:i a");
//            echo " | ".date("F dS, Y");
//        }
    ?>//"); 
        
   var txt_Energy_FromDate = $("#txt_Energy_Date1").datepicker({
            maxDate: new Date("<?=date('m/d/Y')?>")
        });
        var txt_Energy_ToDate = $("#txt_Energy_Date2").datepicker({
            maxDate: new Date("<?=date('m/d/Y')?>")
        });
        
        txt_Energy_FromDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));
        txt_Energy_ToDate.datepicker("setDate", new Date("<?=date('m/d/Y')?>"));     
        
        
</script>
 
<div style="font-size:14px; margin:10px 0px;"><?php echo $address_line1; ?>, <?php echo $city; ?>, <?php echo $state; ?> <?php echo $zip; ?></div>

<div style="float:left; width:200px;">Building Square Feet:</div>  <div style="float:left;"><?php echo number_format($square_feet, 0); ?> ft2</div>
<div class="clear"></div>

<div style="float:left; width:200px;">Total Rooms:</div>  <div style="float:left;"><?php echo $Rooms; ?></div>
<div class="clear"></div>

<div style="float:left; width:200px;">Electricity Utility:</div>  <div style="float:left;"><?php echo $electricity_utility; ?></div>
<div class="clear"></div>

<div style="float:left; width:200px;">Gas Utility:</div>  <div style="float:left;"><?php echo $gas_utility; ?></div>
<div class="clear"></div>

<div style="float:left; width:200px;">Water Utility:</div>  <div style="float:left;"><?php echo $water_utility; ?></div>
<div class="clear"></div>

<div style="float:left; width:200px;">Climate Zone:</div>  <div style="float:left;"><?php echo $climate_zone; ?></div>
<div class="clear"></div>

<div style="float:left; width:200px;">Time Zone:</div>  <div style="float:left;"><?php echo $time_zone; ?></div>
<div class="clear"></div>