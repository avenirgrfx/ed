<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/building.class.php');
require_once(AbsPath.'classes/gallery.class.php');
require_once(AbsPath."classes/customer.class.php");
require_once(AbsPath."classes/widget_category.class.php");

$DB=new DB;
$Category=new Category;
$System=new System;
$Building=new Building;
$Gallery=new Gallery;
$Client = new Client;
$WidgetCategory=new WidgetCategory;


if($_SESSION['user_login']->login_id=="")
{
	Globals::SendURL(URL.'login.php');
}


if(Globals::Get('login_id')<>"")
{
	$_SESSION['client_details']->client_id=Globals::Get('login_id');
}

$_SESSION['client_id']=$_SESSION['client_details']->client_id;

$strClientID=$_SESSION['client_id'];

if($_POST['type']=='System')
{
	$System->parent_id=$_POST['ddlSystem'];
	$System->system_name=$_POST['txtSystemName'];
	$System->has_node= ($_POST['chkHasWidget']=="" ? 0 : 1);
	if($_POST['System_ID']=='')
	{
		$System->Insert();
	}
	else
	{
		$System->system_id=$_POST['System_ID'];
		$System->Update();
	}
	Globals::SendURL(URL."engineers/?type=system");
	
}



$strQuery="Select * from t_sites where client_id=".$strClientID;

if(is_array($_SESSION['Allowed_Sites_Operations']) && count($_SESSION['Allowed_Sites_Operations'])>0)
{
	if($_SESSION['Allowed_Sites_Operations'][0]<>0)
	{
		$strQuery.=" and site_id in (".implode(',',$_SESSION['Allowed_Sites_Operations']).")";
	}
}

$rsSiteArr=$DB->Returns($strQuery);
$strSiteCount=mysql_num_rows($rsSiteArr);

$strSQL="Select t_client.*, t_client_type.client_type from t_client, t_client_type where t_client.client_type=t_client_type.client_type_id and client_id=$strClientID";
//print $strSQL;

$strRsClientDetailsArr=$DB->Returns($strSQL);
while($strRsClientDetails=mysql_fetch_object($strRsClientDetailsArr))
{
	$client_name=$strRsClientDetails->client_name;
	$client_type=$strRsClientDetails->client_type;
	$client_logo=$strRsClientDetails->logo;
	
	$strSQL="Select software_version from t_software_version where software_version_id=".$strRsClientDetails->software_version_id;
	$strRsSoftwareVersionDetailsArr=$DB->Returns($strSQL);
	if($strRsSoftwareVersionDetails=mysql_fetch_object($strRsSoftwareVersionDetailsArr))
	{
		$software_version= $strRsSoftwareVersionDetails->software_version;
	}
}

?>
<!DOCTYPE html>
<html lang="en" ng-app="kitchensink">
  <head>
    <meta charset="utf-8">

    <title>energyDAS Customer</title>
  
    <link rel="stylesheet" href="../css/prism.css">
    <link rel="stylesheet" href="../css/bootstrap.css">	
    <link rel="stylesheet" href="../css/master.css">
    <link rel="stylesheet" href="../css/tree.css">
    <link href="../css/jquery.circliful.css" rel="stylesheet" type="text/css" />
	<link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/jquery.switchButton.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
    
    <style type="text/css">
		*
		{
			font-family:UsEnergyEngineers;
		}
		
		#MenuBar_Gray ul li
		{
			  margin: 10px 6px;
		}
		#Building_EUI_Box_1
		{
			background-color:#248BCA;
			float:left;
			width:23%;							
			text-align:center;
			padding:1px;
			border:1px solid #CCCCCC;
		}
		#Building_EUI_Box_2
		{
			background-color:#ffcd31;
			float:left;
			width:23%;
			margin-left:2px;
			text-align:center;
			padding:1px;
			border:1px solid #CCCCCC;
		}
		#Building_EUI_Box_3
		{
			background-color:#f8981d;
			float:left;
			width:24%;
			margin-left:2px;
			text-align:center;
			padding:1px;
			border:1px solid #CCCCCC;
		}
		#Building_EUI_Box_4
		{
			background-color:#ef3823;
			float:left;
			width:23%;
			margin-left:2px;
			text-align:center;
			padding:1px;
			border:1px solid #CCCCCC;
		}
	</style>
    
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
   	<script type='text/javascript' src="<?php echo URL?>js/prism.js"></script>
    <script type='text/javascript' src="<?php echo URL?>js/fabric.js"></script>
	<script type='text/javascript' src="<?php echo URL?>js/jquery.js"></script>  
	<script type='text/javascript' src="<?php echo URL?>js/bootstrap.js"></script>
	<script type='text/javascript' src="<?php echo URL?>js/paster.js"></script>
    <script type='text/javascript' src="<?php echo URL?>js/angular.min.js"></script>
    <script type='text/javascript' src="<?php echo URL?>js/font_definitions.js"></script>    
    <script type='text/javascript' src="<?php echo URL?>js/utils.js"></script>
	<script type='text/javascript' src="<?php echo URL?>js/app_config.js"></script>
	<script type='text/javascript' src="<?php echo URL?>js/controller.js"></script>
    <script type='text/javascript' src='<?php echo URL?>js/tree.jquery.js'></script>
    <script src="<?php echo URL?>js/jquery-ui.js"></script>
    <script src="<?php echo URL?>js/jquery.circliful.min.js"></script>
    <script src="<?php echo URL?>js/jquery.switchButton.js"></script>
    
    <script type="text/javascript">
		
		var SiteSerial=-1;
		var SiteCount=<?php echo ($strSiteCount-1);?>;
		
		$(function(){
			$( ".monthPicker" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: false,
				dateFormat: 'MM yy',
				maxDate: new Date(),
				
				beforeShowMonth:function(){
					$(this).datepicker('setDate', $(this).val());
				},		
				
				onClose: function(dateText, inst) { 
					var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
					var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
					$(this).datepicker('setDate', new Date(year, month, 1));
					$(".monthPicker").datepicker('setDate', new Date(year, month, 1));
				}
			});
			
			
			
			
			$(".monthPicker").focus(function () {
				$(".ui-datepicker-calendar").hide();
				$("#ui-datepicker-div").position({
					my: "center top",
					at: "center bottom",
					of: $(this)
				});
			});
			
			
			
			
			
		});
		
		
		
		$(document).ready(function(){			
			$('#myStat').circliful();
			
			
			
			/*$('#Top_Menu_Home').click(function(){
				window.location='<?php echo URL?>customer/home.php';
			});
			
			$('#Top_Menu_Operations').click(function(){
				window.location='<?php echo URL?>customer/';
			});
			
			$('#Top_Menu_Files').click(function(){
				window.location='<?php echo URL?>customer/file.php';
			});*/
			
			$('#Gray_Button').click(function(){
				$('#Gray_Button').css('z-index',1);
				$('#Blue_Button').css('z-index',0);
				$('#Building_Elements').css('display','block');
				$('#Building_Elements_Details').css('display','none');
				
			});
			
			$('#Blue_Button').click(function(){				
				$('#Blue_Button').css('z-index',1);
				$('#Gray_Button').css('z-index',0);
				
				$.get("<?php echo URL?>ajax_pages/customers/building_elements.php",
			  	{
					type:2				
			  	},
			  	function(data,status){						
					$('#Building_Elements_Details').html(data);
					
					
					
					$('#Building_Elements_Details').css('display','block');	
					
					
					$("#ddlBuildingElemntsList").empty();
					$('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingElemntsList');
					$("#ddlBuildingElemntsList").val( $('#ddlBuildingForSite').val() );
					UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(),0);
					$('#Building_Elements').css('display','none');
			  	});
				
			});
			
			$('#Gray_Button_Text').click(function(){
				$('#Gray_Button').trigger('click');				
			});
			
			$('#Blue_Button_Text').click(function(){				
				$('#Blue_Button').trigger('click');
			});
			
			
			$('#Site_Details_Summary_Button').click(function(){
				
				$('#site_details_dynamic_content').html('Loading....');
				
				$('#site_details_dynamic_title').html('SITE SUMMARY');
				
				$('#Site_Details_Energy_Button').css('z-index',1);
				$('#Site_Details_Metrics_Button').css('z-index',2);
				$('#Site_Details_GHG_Button').css('z-index',3);
				$('#Site_Details_Summary_Button').css('z-index',4);
				$.get("<?php echo URL?>ajax_pages/customers/site_details.php",
			  	{
					type:1				
			  	},
			  	function(data,status){						
					$('#site_details_dynamic_content').html(
						 data
						);				
			  	});
				
			});
			
			$('#Site_Details_GHG_Button').click(function(){
				$('#site_details_dynamic_content').html('Loading....');
				$('#site_details_dynamic_title').html('GREENHOUSE GAS');
				
				$('#Site_Details_Energy_Button').css('z-index',1);
				$('#Site_Details_Metrics_Button').css('z-index',2);
				$('#Site_Details_Summary_Button').css('z-index',3);
				$('#Site_Details_GHG_Button').css('z-index',4);
				
				$.get("<?php echo URL?>ajax_pages/customers/site_details.php",
			  	{
					type:2			
			  	},
			  	function(data,status){						
					$('#site_details_dynamic_content').html(
						 data
						);				
			  	});
				
			});
			
			$('#Site_Details_Metrics_Button').click(function(){
				$('#site_details_dynamic_content').html('Loading....');
				$('#site_details_dynamic_title').html('SITE METRICS');
				
				$('#Site_Details_Energy_Button').css('z-index',1);
				$('#Site_Details_Metrics_Button').css('z-index',4);
				$('#Site_Details_Summary_Button').css('z-index',2);
				$('#Site_Details_GHG_Button').css('z-index',3);
				
				$.get("<?php echo URL?>ajax_pages/customers/site_details.php",
			  	{
					type:3			
			  	},
			  	function(data,status){						
					$('#site_details_dynamic_content').html(
						 data
						);				
			  	});
				
			});
			
			$('#Site_Details_Energy_Button').click(function(){
				$('#site_details_dynamic_content').html('Loading....');
				$('#site_details_dynamic_title').html('SITE CONSUMPTION');
				
				$('#Site_Details_Energy_Button').css('z-index',4);
				$('#Site_Details_Metrics_Button').css('z-index',3);
				$('#Site_Details_Summary_Button').css('z-index',2);
				$('#Site_Details_GHG_Button').css('z-index',1);
				
				$.get("<?php echo URL?>ajax_pages/customers/site_details.php",
			  	{
					type:4		
			  	},
			  	function(data,status){						
					$('#site_details_dynamic_content').html(
						 data
						);				
			  	});
				
			});
			
			
			$('#consumption_chart_container').html('Loading...');
			$.get("<?php echo URL?>ajax_pages/customers/consumption_chart.php",
			{
				type:1				
			},
			function(data,status){						
				$('#consumption_chart_container').html(
					 data
					);				
			});
			
			
			
			$('#Site_Details_Summary_Text').click(function(){
				$('#Site_Details_Summary_Button').trigger('click');
			});
			
			$('#Site_Details_GHG_Text').click(function(){
				$('#Site_Details_GHG_Button').trigger('click');
			});
			
			$('#Site_Details_Metrics_Text').click(function(){
				$('#Site_Details_Metrics_Button').trigger('click');
			});
			
			$('#Site_Details_Energy_Text').click(function(){
				$('#Site_Details_Energy_Button').trigger('click');
			});
			
			$('#Site_Details_Summary_Button').trigger('click');
			//$('#Gray_Button').trigger('click');
			
			
			$('#ddlBenchMarkBuilding').change(function(){
				var selectedBuilding=$('#ddlBenchMarkBuilding').val();
				//$("#ddlBuildingForSite").val(selectedBuilding);
				//$("#ddlBuildingForSite").val(selectedBuilding);
			});
			
			$('#Energy_Cost_Index_ECI_Button').click(function(){
				$('#Energy_Use_Intensity_EUI_Button').removeClass('benchmark_button_active');
				$('#Energy_Use_Intensity_EUI_Button').addClass('benchmark_button');
				
				$('#Energy_Cost_Index_ECI_Button').removeClass('benchmark_button');
				$('#Energy_Cost_Index_ECI_Button').addClass('benchmark_button_active');
				
				
				$('#Today_Site_EUI_ECI').html('Today SITE ECI');
				$('#Yesterday_Site_EUI_ECI').html('Yesterday SITE ECI');
				$('#Month_Site_EUI_ECI').html('Month SITE ECI');
				$('#Target_Site_EUI_ECI').html('Target SITE ECI');
				
				var Building_Square_Feet_For_Calculation= parseFloat( $('#Building_Square_Feet_For_Calculation').html());
				var electric_energy_consumption_now=$('#electric_energy_consumption_now').html();
				electric_energy_consumption_now=electric_energy_consumption_now.replace(",","");
				electric_energy_consumption_now=electric_energy_consumption_now.replace("kWh","");
				electric_energy_consumption_now=parseFloat(electric_energy_consumption_now);
				
				if(Building_Square_Feet_For_Calculation>0)
				{
					$('#Month_Site_EUI_ECI_Value_Amount').html( "$"+ round(((electric_energy_consumption_now*0.07) / Building_Square_Feet_For_Calculation),3) +"/ft<sup>2</sup>" );
				}
				else
				{
					$('#Month_Site_EUI_ECI_Value_Amount').html('0');
				}
				$('.EUI_Val').css('display','none');	
				$('.ECI_Val').css('display','block');	
										
			});
			
			$('#Energy_Use_Intensity_EUI_Button').click(function(){
				
				$('#Energy_Cost_Index_ECI_Button').removeClass('benchmark_button_active');
				$('#Energy_Cost_Index_ECI_Button').addClass('benchmark_button');
			
				$('#Energy_Use_Intensity_EUI_Button').removeClass('benchmark_button');
				$('#Energy_Use_Intensity_EUI_Button').addClass('benchmark_button_active');
				
				$('#Today_Site_EUI_ECI').html('Today SITE EUI');	
				$('#Yesterday_Site_EUI_ECI').html('Yesterday SITE EUI');
				$('#Month_Site_EUI_ECI').html('Month SITE EUI');
				$('#Target_Site_EUI_ECI').html('Target SITE EUI');
				
				$('.EUI_Val').css('display','block');	
				$('.ECI_Val').css('display','none');
				
			});
			
			        
			
			
		});
		
		
		function GrayButtonClickLoad()
		{
			$.get("<?php echo URL?>ajax_pages/customers/building_elements.php",
			{
				type:1	
			},
			function(data,status){					
				$('#Building_Elements').html(data);				
			});
		}
		
		function UpdateBuildingElementDetails(strBuildingID, UpdateOtherBuildingDropDown)
		{
			$.get("<?php echo URL?>ajax_pages/customers/building_details.php",
			{
				building_id:strBuildingID
			},
			function(data,status){					
				$('#Building_Details_Container').html(data);
				if(UpdateOtherBuildingDropDown==1)
				{
					UpdateAllBuildingDropdown(strBuildingID);
				}			
			});		
		}
		
		
		GrayButtonClickLoad();
		
		function UpdateAllBuildingDropdown(strBuildingID)
		{
			$("#ddlBuildingForSite").val(strBuildingID);
			$("#ddlBenchMarkBuilding").val(strBuildingID);
			$("#ddlSiteSummaryBuilding").val(strBuildingID);
			$("#ddlConsumptionBuilding").val(strBuildingID);
			$("#ddlBuildingElemntsList").val(strBuildingID);
			UpdateBuildingElementDetails(strBuildingID,0);
			
			$('#ddlFilterElectric_Gas').trigger('change');
			$('#ddlMetricsType').trigger('change');
		}
		
		
		function ChangeBuildingDropdown(strBuildingID)
		{
			
			$("#ddlBenchMarkBuilding").empty();
			$('#ddlBuildingForSite option').clone().appendTo('#ddlBenchMarkBuilding');
			$("#ddlBenchMarkBuilding").val(strBuildingID);
			
			
			$("#ddlBuildingElemntsList").empty();
			$('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingElemntsList');
			$("#ddlBuildingElemntsList").val(strBuildingID);
			
			
			$("#ddlSiteSummaryBuilding").empty();
			$('#ddlBuildingForSite option').clone().appendTo('#ddlSiteSummaryBuilding');
			$("#ddlSiteSummaryBuilding").val(strBuildingID);
			
			$("#ddlConsumptionBuilding").empty();
			$('#ddlBuildingForSite option').clone().appendTo('#ddlConsumptionBuilding');
			$("#ddlConsumptionBuilding").val(strBuildingID);
			
			
			$('#Container_SystemsByBuilding').html('Loading...');
			$.get("<?php echo URL?>ajax_pages/customers/system_list_by_building_child_system.php",
			{
				building_id:strBuildingID		
			},
			function(data,status){
				$('#Container_SystemsByBuilding').html(data);				
			});
			
			
			$('#Building_BenchMark_Container').html('Loading....');		
			$.get("<?php echo URL?>ajax_pages/customers/building_benchmark_eui.php",{building_id:strBuildingID},function(data){
				$('#Building_BenchMark_Container').html(data);
			});
			
			$('#Consumption_Electric_System').html('Loading...');			
			$.get("<?php echo URL?>ajax_pages/customers/system_list_by_building_electric_system.php",
			{				
				building_id:strBuildingID,
				type:1,
			},
			function(data,status){			
				$('#Consumption_Electric_System').html(data);
				if(strType==1)
				{
					$('#Total_Electric_Gas_Label').html('Total Electric');
					//$('#Total_Electric_Gas_Value').html('11,181,865 kWh');
					$('#Main_Utility_Electric_Gas_Label').html('Electric Disconnect');
					//$('#Main_Utility_Electric_Gas_Value').html('181,865 kWh');
				}
				else if(strType==2)
				{
					$('#Total_Electric_Gas_Label').html('Total Natural Gas');
					//$('#Total_Electric_Gas_Value').html('11,181,865 Therms');
					$('#Main_Utility_Electric_Gas_Label').html('Main Natural Gas');
					//$('#Main_Utility_Electric_Gas_Value').html('181,865 Therms');
				}
			});
			
			$('#ddlFilterElectric_Gas').trigger('change');
			$('#ddlMetricsType').trigger('change');
			
			//$('#Container_SystemsByBuilding').html('asdsfds');
			
			/*var values = [];
			$('#ddlBuildingForSite option').each(function() { 
				values.push( $(this).attr('value') );
				
			});*/
			
		}
		
		
		function SwitchElectricGasSystem(strType)
		{
			$('#Consumption_Electric_System').html('Loading...');			
			$.get("<?php echo URL?>ajax_pages/customers/system_list_by_building_electric_system.php",
			{				
				building_id:$('#ddlBuildingForSite').val(),
				type:strType,
			},			
			function(data,status){			
				$('#Consumption_Electric_System').html(data);
				
				if(strType==1)
				{
					$('#Total_Electric_Gas_Label').html('Total Electric');
					//$('#Total_Electric_Gas_Value').html('11,181,865 kWh');
					$('#Main_Utility_Electric_Gas_Label').html('Electric Disconnect');
					//$('#Main_Utility_Electric_Gas_Value').html('181,865 kWh');
				}
				else if(strType==2)
				{
					$('#Total_Electric_Gas_Label').html('Total Natural Gas');
					//$('#Total_Electric_Gas_Value').html('11,181,865 Therms');
					$('#Main_Utility_Electric_Gas_Label').html('Main Natural Gas');
					//$('#Main_Utility_Electric_Gas_Value').html('181,865 Therms');
				}
				
			});
		}
		
		
	
		
		function showBuildingSystemChild(strParentSystemID, strBuildingID)
		{
			
			//$('#'+strParentSystemID+'_content').html(strParentSystemID);
			$('#'+strParentSystemID+'_content').html('Loading...');
			
			
			$.get("<?php echo URL?>ajax_pages/customers/system_list_by_building_child_system.php",
			{
				parent_id:strParentSystemID,
				building_id:strBuildingID
			},
			
			function(data,status){						
				$('#'+strParentSystemID+'_content').html(data);				
			});
		}
		
		
		 
		
		
		
		
		function LeftArrow_Click()
		{
			$('#Show_Dynamic_Sites').html('Loading...');
			SiteSerial--;
			if(SiteSerial<0)
			{
				SiteSerial=0;
			}
			$.get("<?php echo URL?>ajax_pages/customers/dynamic_sites_name.php",
			{
				serial:SiteSerial
			},
			function(data,status){						
				$('#Show_Dynamic_Sites').html(data);
				$.get("<?php echo URL?>ajax_pages/customers/dynamic_building_name.php",
				{
					serial:SiteSerial
				},
				function(data,status){						
					$('#Show_Dynamic_Buildings').html(data);
					ChangeBuildingDropdown($('#ddlBuildingForSite').val());	
					
					UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(),0);
										
				});				
			});
			
		}
		
		function RightArrow_Click()
		{
			$('#Show_Dynamic_Sites').html('Loading...');
			SiteSerial++;
			if(SiteSerial>SiteCount)
			{
				SiteSerial=SiteCount;
			}
			
			if(SiteSerial<0)
			{
				SiteSerial=0;
			}
			
			
			$.get("<?php echo URL?>ajax_pages/customers/dynamic_sites_name.php",
			{
				serial:SiteSerial
			},
			function(data,status){						
				$('#Show_Dynamic_Sites').html(data);					
				
				$.get("<?php echo URL?>ajax_pages/customers/dynamic_building_name.php",
				{
					serial:SiteSerial
				},
				function(data,status){						
					$('#Show_Dynamic_Buildings').html(data);
					ChangeBuildingDropdown($('#ddlBuildingForSite').val());
					UpdateBuildingElementDetails($("#ddlBuildingElemntsList").val(),0);
				});	
						
			});				
		}
		
		RightArrow_Click();
		
		
		
		function Expand_Collapse_System_Node_For_Building(strSystemID)
		{
			if( $('.System_ID_'+strSystemID).css('display') =='none' )
			{
				$('.System_ID_'+strSystemID).slideDown('slow');
				$('.System_ID_Expand_'+strSystemID).html('-');
				
				//$('.System_ID_'+strSystemID).css('display','block');
			}
			else
			{
				//$('.System_ID_'+strSystemID).css('display','none');
				$('.System_ID_'+strSystemID).slideUp('slow');
				$('.System_ID_Sub_'+strSystemID).slideUp('slow');
				$('.System_ID_Expand_'+strSystemID).html('+');
			}
			//$('.noclick').attr('onclick','').unbind('click');
		}
		
		
	</script>
    
  </head>
  
  <body>
 
  
  	<div id="Customer_Main_Container">
    	<div id="Customer_Header_Section">
        	<div style="float:left; border-right:1px solid #333333; padding-right:10px;">
            	<?php echo Globals::Resize('../uploads/customer/'.$client_logo, 150, 70);?>
            </div>
            <div style="float:left; margin-left:50px;">
            	<h5 style="text-transform:uppercase;"><?php echo $client_name; ?></h5>
                <span style="font-size:24px;"><?php echo $software_version?> - <?php echo $client_type; ?></span>
            </div>
            <div style="float:right; text-align:right; font-size:18px; margin-top:17px; font-weight: bold; color: #CCCCCC;">
            	energyDAS<br>
                <?php echo date("g:i a F dS, Y");?>
                
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="GrayBackground">
    		
            <?php require_once("menu.php");?>
                       
            
            <div id="Customer_Left_Panel" style="height:490px;">
            	
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:1; width:35px; height:159px; top:70px; background-image:url(<?php echo URL?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Gray_Button_Text">Buildings</div>
                    </div>
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:170px; background-image:url(<?php echo URL?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Blue_Button_Text">Elements</div>
                    </div>
                    
                </div>
                
                
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                	<div class="Window_Title_Bg">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading">PORTFOLIO</div>
							
                        </div>
                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="<?php echo URL?>/images/window_title_divider.png" />
                        </div>
                        
                        
                        <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;" id="Show_Dynamic_Sites">                        	
                          Loading...
                        </div>
                        
                        <div style="float:right; margin-top:15px; margin-right:15px;">
                   		  <img src="<?php echo URL?>/images/previous_next_arrow.png" border="0" usemap="#Map" />
                            <map name="Map">
                              <area shape="circle" coords="23,20,16" href="javascript:LeftArrow_Click();">
                              <area shape="circle" coords="61,22,15" href="javascript:RightArrow_Click();">
                            </map>
                      </div>
                        
                        <div class="clear"></div>
                        
                    </div>
                    <div class="Window_Container_Bg" style="height:423px;">
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:310px;" id="Building_Elements">&nbsp;</div>
                        <div style="padding:15px 10px 10px 20px; display:none; min-height:310px;" id="Building_Elements_Details">&nbsp;</div>
                   

                    </div>
                    
                </div>
              
                <div class="clear"></div>
                
                
<br>

            
            </div>
            
            
            <div id="Customer_Right_Panel" style="min-height:500px;">
            
            	<div style="width:94%; padding:3%; border-radius:10px; min-height:300px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">
                	<div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:250px; border-radius:5px;">
                    
                        
                        <div style="float:left; margin-top:5px;" class="heading">BENCHMARK</div>
                        	
                        <div style="float:left; margin-left:25px;  margin-top:5px;">
                        	
                            <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBenchMarkBuilding" id="ddlBenchMarkBuilding" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                            	<option value="">Select Building</option>
                            </select>
                        </div>
                        
                        <div style="float:right; margin-top:5px;">                        	   
                            <input type="text" name="txt_Benchmark_Date" id="txt_Benchmark_Date" placeholder="Select Month and Year" value="<?php echo date("F Y")?>" style="width:130px; font-size:12px; height:12px;" class="monthPicker" />                     	
                        	  	
                        </div>
                        <div class="clear"></div>
                        <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:5px;" />                      
                        
                       <div id="Building_BenchMark_Container"></div>                    
                        
                        
                    </div>
                	
                    <div style="margin-top:10px;">
                        <div class="benchmark_button_active" style="float:left;" id="Energy_Use_Intensity_EUI_Button">Energy Use Intensity (EUI)</div>
                        <div style="float:left; margin-left:10px; padding:5px;">|</div>     
                        <div class="benchmark_button" style="float:left; margin-left:10px; padding:5px;" id="Energy_Cost_Index_ECI_Button">Energy Cost Index (ECI)</div>
                        <div style="float:right; background-color:#FFFFFF; border-radius:10px; padding:5px 5px; border:1px solid #CCCCCC; width:120px; text-align:center;">
                        	<a href="#Portfolii_Link" target="_blank"><img src="<?php echo URL?>/images/portfolio_manager_logo.png" border="0" /></a>
                        </div>
                        <div class="clear"></div>
                   </div>
                   
                </div>
            
            </div>
            <div class="clear"  style="height:20px;"></div>
            
            
            
            <div id="Customer_Left_Panel">
            	
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:3; width:35px; height:159px; top:70px; background-image:url(<?php echo URL?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Summary_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Site_Details_Summary_Text">Summary</div>
                    </div>
                	<div style="position:absolute; z-index:2; width:35px; height:159px; top:180px; background-image:url(<?php echo URL?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_GHG_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:105px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Site_Details_GHG_Text">GHG</div>
                    </div>
                    <div style="position:absolute; z-index:1; width:35px; height:159px; top:290px; background-image:url(<?php echo URL?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Metrics_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Site_Details_Metrics_Text">Metrics</div>
                    </div>
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:400px; background-image:url(<?php echo URL?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Energy_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Site_Details_Energy_Text">Energy</div>
                    </div>
                    
                </div>
                
                
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                	<div class="Window_Title_Bg">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading">SITE DETAILS</div>
							
                        </div>
                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="<?php echo URL?>/images/window_title_divider.png" />
                        </div>
                        
                        
                        
                       
                      
                      <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;" id="site_details_dynamic_title">
                        	SITE SUMMARY
                        </div>
                        
                        <div class="clear"></div>
                        
                    </div>
                    <div class="Window_Container_Bg">
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:575px;">
                        
                            <div style="float:left;">
                               
                            <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlSiteSummaryBuilding" id="ddlSiteSummaryBuilding" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                            	
                            </select>
                                                            
                            </div>
                            
                            <div style="float:right; margin-left:30px;">
                            	<input type="text" name="txt_SiteDetails_Date" id="txt_SiteDetails_Date" placeholder="Select Month and Year" value="July 2015" style="width:130px; font-size:12px; height:12px;" class="monthPicker">
                            </div>
                            
                            <div class="clear"></div>
                            
                            
                             <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:10px;">
                              
                              <div id="site_details_dynamic_content">
                             
                              	Placeholder
                              
                              </div>
                              
                        </div>
                   

                    </div>
                    
                </div>
              
                <div class="clear"></div>
                
                
<br>

            
            </div>
            
             <div id="Customer_Right_Panel">
            
            	<div style="width:94%; padding:3%; border-radius:10px; min-height:300px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">
                	<div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:625px; border-radius:5px;">
                    
                        
                        <div style="float:left; margin-top:5px;" class="heading">CONSUMPTION</div>
                        	
                        <div style="float:left; margin-left:25px;  margin-top:5px;">
                        	
                            <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlConsumptionBuilding" id="ddlConsumptionBuilding" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                            	<option value="">Zeeland Mainsite</option>
                            </select>
                        </div>
                        
                        <div style="float:right; margin-top:5px;">
                        	<input type="text" name="txt_Consumptions_Date" id="txt_Consumptions_Date" placeholder="Select Month and Year" value="July 2015" style="width:130px; font-size:12px; height:12px;" class="monthPicker">                        	
                        </div>
                        <div class="clear"></div>
                        <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px;" />                      
                        
                        
                        <div>                        
                        	<div style="float:left; margin-top:2px;">ELECTRIC CONSUMPTION NOW</div>
                            <div style="float:left; margin-left:3px;" class="light_blue_box_for_value" id="electric_energy_consumption_now">0 kWh</div>
                          
                        	
                            <div style="float:left; margin-left:6px; margin-top:2px;">GAS CONSUMPTION NOW</div>
                            <div style="float:left; margin-left:3px;" class="gray_box_for_value" id="natural_gas_energy_consumption_now">0 Therms</div>
                            <div class="clear"></div>
                        	
                            
                            <div style="margin-bottom:5px; margin-top:10px; color:#666666; font-weight:bold; font-size:16px; text-decoration:underline; text-align:center;">Combined Systems Consumption (MBTU)</div>
                            
                            <div style="margin:10px 0px; height:250px;" id="consumption_chart_container">
                            	Loading...
                            </div>
                            
                            <div style="float:left; width:96%; margin:1%;">
                            	
                           	  <div style="float:left; font-weight:bold;">                                	
                                    <select name="ddlFilterElectric_Gas" id="ddlFilterElectric_Gas" onChange="SwitchElectricGasSystem(this.value)" style="font-weight:bold;">
                                    	<option value="1" selected>Electric System</option>
                                        <option value="2">Natural Gas System</option>
                                    </select>
                              </div>
                                
                                <div style="float:right; margin-right:20px; font-weight:bold;"> % Total</div><div class="clear"></div>
                                <div style=" padding-bottom:10px; padding-top:5px; margin-top:5px; border-top:1px solid #999999; border-bottom:1px solid #999999; height:100px; overflow-y: scroll;" id="style-2">
                            	
                                <div id="Consumption_Electric_System">
                            		Loading...
                                </div>
                            	
                                		
                    		</div>
                            	
                                <div style="float:left; width:90%;">                                
                                    <div class="clear" style="margin-top:10px;"></div>
                                    <div style="float:left; width:325px; text-align:right; margin-top:3px; font-weight:bold; margin-right:5px;" id="Total_Electric_Gas_Label">Total Electric</div>
                                    <div style="float:left; min-width: 106px; " class="normal_blue_box_for_value" id="Total_Electric_Gas_Value">0 kWh</div> 
                                    <div class="clear"></div>
                                    
                                    <div class="clear" style="margin-top:3px;"></div>
                                    <div style="float:left; width:325px;  text-align:right; font-size:12px; margin-top:3px; margin-right:5px;" id="Main_Utility_Electric_Gas_Label">Electric Disconnect</div>
                                    <div class="light_blue_box_for_value" style="float:left; min-width:104px; font-weight:normal; background:none; border:1px solid #DDDDDD;" id="Main_Utility_Electric_Gas_Value">0 kWh</div>
                                    
                                    <div class="clear"></div>
                                </div>
                                
                                <div style="float:left; margin-left:3px;" class="right_bracket_bg">
                                	<div style="margin-top:25px; background-color:#FFFFFF;">0%</div>
                            	</div>
                                
                                <div class="clear"></div>
                                
                            </div>
                            
                            
                        
                        
                        
                    </div>
                	
                    
                   
                </div>
            
            </div>
            
            <div class="clear"></div>
            
        </div>
   		
		 <div class="clear"></div>
        </div>
    </div>
  
  </body>

<script src="<?php echo URL?>highstock/js/highstock.js"></script>
<script src="<?php echo URL?>highstock/js/modules/exporting.js"></script>
<script src="<?php echo URL?>highcharts/js/highcharts.js"></script>
<script src="<?php echo URL?>highcharts/js/modules/exporting.js"></script>  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

</html>