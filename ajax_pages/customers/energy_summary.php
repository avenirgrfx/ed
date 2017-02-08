<?php
ob_start();
session_start();
require_once("../../configure.php");
require_once(AbsPath."classes/all.php");
$type= $_GET['type'];
$DB=new DB;
?>

<script type="text/javascript">
$(document).ready(function(){
	 $('#CircualChart_1').circliful();
	 $('#CircualChart_2').circliful();
});

$(function(){
	 $( "#txt_Energy_FromDate" ).datepicker();
	 $( "#txt_Energy_ToDate" ).datepicker();
});

</script>

 

<?php 
if($type==1)
{	
	$building_id=$_GET['building_id'];
	
?>

<div style="font-size:15px;">
    
     <!--<div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px;">ENERGY SUMMARY - ENERGY SYSTEMS</div>-->

<div style="height:150px; overflow-y:scroll;" class="myscroll">     
	
    <?php
    	$strSQL="Select system_name, system_id, display_type from t_system where exclude_in_calculation=0 and system_id in (Select Distinct (parent_parent_parent_id) from t_system_node where delete_flag=0 and building_id=$building_id)";
		$strRsSystemsArr=$DB->Returns($strSQL);
		while($strRsSystems=mysql_fetch_object($strRsSystemsArr))
		{
			print '<div style="text-align:left; font-weight:bold; width:65%;">'.$strRsSystems->system_name.'</div>';
			$strSQL="Select system_name, system_id from t_system where system_id in ( Select Distinct (parent_parent_id) from t_system_node where delete_flag=0 and building_id=$building_id and parent_parent_parent_id=".$strRsSystems->system_id.")";
			$strRsSystemNodeNamesArr=$DB->Returns($strSQL);
			while($strRsSystemNodeNames=mysql_fetch_object($strRsSystemNodeNamesArr))
			{				
			?>            	
                <div style="float:left; text-align:right; width:65%;"><?php echo $strRsSystemNodeNames->system_name;?>-Consumption</div>
     			<div style="float:right; text-align:center; margin-left:5px; width:25%;" class="<?php if($strRsSystems->display_type==1){?>light_blue_box_for_value<?php } else {?> gray_box_for_value<?php }?>">181,865 BTU</div>                                 
     			<div class="clear" style="margin-bottom:5px;"></div>
     
            <?php				
			}
		}
	?>
    
     <!--<div style="float:left; text-align:right; width:65%;">HVAC SYSTEMS - ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">181,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
    
     <div style="float:left; text-align:right; width:65%;">PUMP SYSTEMS - ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
     <div style="float:left; text-align:right; width:65%;">LIGHTING - ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
     <div style="float:left; text-align:right; width:65%;">HVAC - GAS Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="gray_box_for_value">181,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
     <div style="float:left; text-align:right; width:65%;">WATER HEATING - GAS Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="gray_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:15px;"></div>-->
</div>

<hr style="margin:5px 0px;" />
     
     <div style="float:left; text-align:right; width:65%;">TOTAL ELECTRIC</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
     <div style="float:left; text-align:right; width:65%;">TOTAL NATURAL GAS</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="gray_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:15px;"></div>
     
     <div style="float:left; text-align:right; width:65%;">TOTAL ENERGY SYSTEMS</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="normal_blue_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
</div>

<hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:5px;">

<div style="padding:10px; border:1px solid #CCCCCC;">
	
	<div style="margin-bottom:10px; text-align:center; text-decoration:underline; color:#666666; font-weight:bold; font-size:16px;">ENERGY SUMMARY - ENERGY SYSTEMS</div>
    
    <div style="float:left; width:50%; text-align:center;">
    	<input type="text" name="txt_Energy_FromDate" id="txt_Energy_FromDate" placeholder="Pick Date1" value="" style="width:120px; font-size:12px; height:12px;" >
		<br>
		 
        <div id="CircualChart_1" style="margin:0px auto;" data-dimension="130" data-text="13,243" data-info="MBTU" data-width="10" data-fontsize="24" data-percent="75" data-fgcolor="#61a9dc" data-bgcolor="#eee" data-fill="#ddd"></div>

        
    </div>
    
    <div style="float:left; width:50%; text-align:center;">
    	<input type="text" name="txt_Energy_ToDate" id="txt_Energy_ToDate" placeholder="Pick Date2" value="" style="width:120px; font-size:12px; height:12px;">
		<br>
		
       <div id="CircualChart_2" style="margin:0px auto;" data-dimension="130" data-text="12,321" data-info="MBTU" data-width="10" data-fontsize="24" data-percent="55" data-fgcolor="#61a9dc" data-bgcolor="#eee" data-fill="#ddd"></div>

    </div>
    <div class="clear"></div>
</div>

<?php }elseif($type==2){?>
	
    
<div style="font-size:15px;">
    
     <div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px;">ENERGY SUMMARY - CHILLED WATER SYSTEM</div>

<div style="height:150px; overflow-y:scroll;" class="myscroll">     
     <div style="float:left; text-align:right; width:65%;">Chiller 1 (ACCH-1) ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">181,865 kWh</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>     
    
     <div style="float:left; text-align:right; width:65%;">Chiller 2 (ACCH-2) ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">371,865 kWh</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
     <div style="float:left; text-align:right; width:65%;">Chiller 3 (ACCH-3) ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
     <div style="float:left; text-align:right; width:65%;">Chiller 1 (ACCH-1) ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">181,865 kWh</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>     
    
     <div style="float:left; text-align:right; width:65%;">Chiller 2 (ACCH-2) ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">371,865 kWh</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
     <div style="float:left; text-align:right; width:65%;">Chiller 3 (ACCH-3) ELECTRIC Consumption</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
    
</div>

<hr style="margin:5px 0px;" />
     
     <div style="float:left; text-align:right; width:65%;">TOTAL</div>
     <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="normal_blue_box_for_value">371,865 BTU</div>                                 
     <div class="clear" style="margin-bottom:5px;"></div>
     
</div>

<hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:5px;">

<div style="padding:10px; border:1px solid #CCCCCC;">
	
	<div style="margin-bottom:10px; text-align:center; text-decoration:underline; color:#666666; font-weight:bold; font-size:16px;">Chilled Water Systems Consumption Comparison</div>
    
    <div style="float:left; width:50%; text-align:center;">
    	<input type="text" name="txt_Energy_FromDate" id="txt_Energy_FromDate" placeholder="Pick Date1" value="" style="width:120px; font-size:12px; height:12px;" >
		<br><br />
		 <div id="CircualChart_3" style="margin:0px auto;" data-dimension="160" data-text="13,243" data-info="kWh" data-width="10" data-fontsize="24" data-percent="75" data-fgcolor="#61a9dc" data-bgcolor="#eee" data-fill="#ddd"></div>

        
    </div>
    
    <div style="float:left; width:50%; text-align:center;">
    	<input type="text" name="txt_Energy_ToDate" id="txt_Energy_ToDate" placeholder="Pick Date2" value="" style="width:120px; font-size:12px; height:12px;">
		<br><br />
		 <div id="CircualChart_4" style="margin:0px auto;" data-dimension="160" data-text="13,243" data-info="kWh" data-width="10" data-fontsize="24" data-percent="75" data-fgcolor="#61a9dc" data-bgcolor="#eee" data-fill="#ddd"></div>

    </div>
    <div class="clear"></div>
</div>
    
<?php }?>