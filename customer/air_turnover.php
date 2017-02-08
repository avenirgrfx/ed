<?php
ob_start();
session_start();
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');
require_once(AbsPath."classes/customer.class.php");
require_once(AbsPath."classes/widget_category.class.php");

$DB=new DB;
$Category=new Category;
$System=new System;
$Gallery=new Gallery;
$Client = new Client;
$WidgetCategory=new WidgetCategory;

if($_SESSION['user_login']->login_id=="")
{
	Globals::SendURL(URL.'login.php');
}

$_SESSION['client_id']=$_SESSION['client_details']->client_id;
$strClientID=$_SESSION['client_id'];



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
    
    <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
    
    <style type="text/css">
		*
		{
			font-family:UsEnergyEngineers;
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
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="<?php echo URL?>js/jquery.circliful.min.js"></script>
    
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
    <script type="text/javascript">
		
		var SiteSerial=-1;
		var SiteCount=<?php echo ($strSiteCount-1);?>;
		
		$(document).ready(function(){
		
		
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
				$('#Site_Details_Energy_Button').css('z-index',1);
				$('#Site_Details_Metrics_Button').css('z-index',2);
				$('#Site_Details_GHG_Button').css('z-index',3);
				$('#Site_Details_Summary_Button').css('z-index',4);
				
				$('#ddlGraphType').val('1');
				$('#ddlGraphType').trigger('change');
				
			});
			
			$('#Site_Details_GHG_Button').click(function(){				
				$('#Site_Details_Energy_Button').css('z-index',1);
				$('#Site_Details_Metrics_Button').css('z-index',2);
				$('#Site_Details_Summary_Button').css('z-index',3);
				$('#Site_Details_GHG_Button').css('z-index',4);
				$('#ddlGraphType').val('2');
				$('#ddlGraphType').trigger('change');
			});
			
			$('#Site_Details_Metrics_Button').click(function(){
				$('#Site_Details_Energy_Button').css('z-index',1);
				$('#Site_Details_Metrics_Button').css('z-index',4);
				$('#Site_Details_Summary_Button').css('z-index',2);
				$('#Site_Details_GHG_Button').css('z-index',3);
				$('#ddlGraphType').val('3');
				$('#ddlGraphType').trigger('change');
			});
			
			$('#Site_Details_Energy_Button').click(function(){	
				$('#Site_Details_Energy_Button').css('z-index',4);
				$('#Site_Details_Metrics_Button').css('z-index',3);
				$('#Site_Details_Summary_Button').css('z-index',2);
				$('#Site_Details_GHG_Button').css('z-index',1);
				$('#ddlGraphType').val('4');
				$('#ddlGraphType').trigger('change');
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
			$("#ddlBuildingConsoleList").val(strBuildingID);
			$("#ddlBuildingForChartList").val(strBuildingID);
			$("#ddlBuildingElemntsList").val(strBuildingID);
			
			$('#ddlBuildingForSite').trigger('change');
			
			UpdateBuildingElementDetails(strBuildingID,0);
			
			
			
			
			
		}
		
		
		function ChangeBuildingDropdown(strBuildingID)
		{
			$("#ddlBuildingConsoleList").empty();
			$('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingConsoleList');
			$("#ddlBuildingConsoleList").val(strBuildingID);
			
			
			$("#ddlBuildingElemntsList").empty();
			$('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingElemntsList');
			$("#ddlBuildingElemntsList").val(strBuildingID);
			
			
			/*$("#ddlSiteSummaryBuilding").empty();
			$('#ddlBuildingForSite option').clone().appendTo('#ddlSiteSummaryBuilding');
			$("#ddlSiteSummaryBuilding").val(strBuildingID);*/
			
			$("#ddlBuildingForChartList").empty();
			$('#ddlBuildingForSite option').clone().appendTo('#ddlBuildingForChartList');
			$("#ddlBuildingForChartList").val(strBuildingID);
			
			
			$('#Container_SystemsByBuilding').html('Loading...');
			$.get("<?php echo URL?>ajax_pages/customers/system_list_by_building_child_system.php",
			{
				building_id:strBuildingID		
			},
			function(data,status){
				$('#Container_SystemsByBuilding').html(data);				
			});
			
			
			var graphtype=1;
			
			if(	$('#ddlGraphType').val() !=1 )
			{			
				var getElectricNGasButton='';
				if($('#ddlGraphType').val() == 2)
				{
					getElectricNGasButton=$('#Electric_Consumption_Button').attr('class');				
				}
				else if($('#ddlGraphType').val()==3)	
				{
					getElectricNGasButton=$('#Electric_Cost_Button').attr('class');
				}
				else if($('#ddlGraphType').val()==4)
				{
					getElectricNGasButton=$('#Electric_Saving_Button').attr('class');
				}
				
				getElectricNGasButton=getElectricNGasButton.indexOf("benchmark_button_active");
				if( getElectricNGasButton>0 )
				{
					graphtype=1;
					
					$('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
					$('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
					$('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");
					
					
					$('#Electric_Consumption_Button').removeClass("benchmark_button_active");
					$('#Electric_Consumption_Button').addClass("benchmark_button_active");			
					$('#Electric_Cost_Button').removeClass("benchmark_button_active");
					$('#Electric_Cost_Button').addClass("benchmark_button_active");
					$('#Electric_Saving_Button').removeClass("benchmark_button_active");				
					$('#Electric_Saving_Button').addClass("benchmark_button_active");
				}
				else
				{
					graphtype=2;
					
					$('#Electric_Consumption_Button').removeClass("benchmark_button_active");
					$('#Electric_Cost_Button').removeClass("benchmark_button_active");
					$('#Electric_Saving_Button').removeClass("benchmark_button_active");
					
					
					$('#Natural_Gas_Consumption_Button').removeClass("benchmark_button_active");
					$('#Natural_Gas_Consumption_Button').addClass("benchmark_button_active");			
					$('#Natural_Gas_Cost_Button').removeClass("benchmark_button_active");
					$('#Natural_Gas_Cost_Button').addClass("benchmark_button_active");
					$('#Natural_Gas_Saving_Button').removeClass("benchmark_button_active");				
					$('#Natural_Gas_Saving_Button').addClass("benchmark_button_active");
					
				}
			}
			
			
			
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
            
            
            <div id="Customer_Left_Panel">
            	
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:1; width:35px; height:159px; top:70px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Gray_Button_Text">Buildings</div>
                    </div>
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:170px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Blue_Button_Text">Elements</div>
                    </div>
                    
                </div>
                
                
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                	<div class="Window_Title_Bg">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading">PORTFOLIO</div>
							
                        </div>
                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="../images/window_title_divider.png" />
                        </div>
                        
                        
                       
                        
                        <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;" id="Show_Dynamic_Sites">                        	
                          Loading...
                        </div>
                        
                        
                        <div style="float:right; margin-top:15px; margin-right:15px;">
                   		  <img src="../images/previous_next_arrow.png" border="0" usemap="#Map" />
                            <map name="Map">
                              <area shape="circle" coords="23,20,16" href="javascript:LeftArrow_Click();">
                              <area shape="circle" coords="61,22,15" href="javascript:RightArrow_Click();">
                            </map>
                      </div>
                        
                        <div class="clear"></div>
                        
                    </div>
                    <div class="Window_Container_Bg">
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:310px;" id="Building_Elements">&nbsp;</div>
                        <div style="padding:15px 10px 10px 20px; display:none; min-height:310px;" id="Building_Elements_Details">&nbsp;</div>
                   
                   

                    </div>
                    
                </div>
              
                <div class="clear"></div>
                
                
<br>

            
            </div>
            
            
     
         <!--   <div id="Customer_Right_Panel">
            
            	<div style="width:100%; padding:3% 0%; border-radius:10px; min-height:365px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">
                	
                    <div style="height:100%; min-height:318px; border-radius:5px;">
                    
                        	
                        <div style="float:left; margin-left:10px;  margin-top:5px;">
                        	
                       
                        </div>
                        
                   
                        <div class="clear"></div>
                        <hr style="border-bottom:#CCCCCC 1px solid; margin-top:48px; margin-bottom:10px;" />             
                        
                        
                       
                        
                        
                    </div>
                	
                    
              		
                    <div id="Graph_Type_Bottom_Options_1" class="dark_green_box_for_value" style="width:200px; margin-top:10px; margin-right:10px;  border-radius:10px; text-align:center; font-size:16px; text-transform:uppercase; float:right;" >Node Management</div>
                    <div class="clear"></div>
                    
                    <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_2">
                        <div class="benchmark_button benchmark_button_active" style="float:left; cursor:pointer;" id="Electric_Consumption_Button">Electric Consumption</div>
                        <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                        <div class="benchmark_button" style="float:left; margin-left:10px; padding:5px; cursor:pointer;" id="Natural_Gas_Consumption_Button">Natural Gas Consumption</div>                       
                        <div class="clear"></div>
                    </div>
                    
                    <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_3">
                        <div class="benchmark_button benchmark_button_active" style="float:left;  cursor:pointer;" id="Electric_Cost_Button">Electric Cost</div>
                        <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                        <div class="benchmark_button" style="float:left; margin-left:10px; padding:5px; cursor:pointer;" id="Natural_Gas_Cost_Button">Natural Gas Cost</div>                       
                        <div class="clear"></div>
                    </div>
                    
                     <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_4">
                        <div class="benchmark_button benchmark_button_active" style="float:left; cursor:pointer;" id="Electric_Saving_Button">Electric Savings</div>
                        <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                        <div class="benchmark_button" style="float:left; margin-left:10px; padding:5px; cursor:pointer;" id="Natural_Gas_Saving_Button">Natural Gas Savings</div>                       
                        <div class="clear"></div>
                    </div>
                   
                </div>
            
            </div>-->
            
     
            
            
            
            <div class="Windows_Main" style="border:1px solid #999999; border-radius:10px; margin-left:-20px;">
                	<div class="Window_Title_Bg" style="width:590px;">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading" style="text-align: center;">AIR TURNOVER</div>
							
                        </div>
                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="../images/window_title_divider.png">
                        </div>
                        
                        
                       
                        
                        <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666; line-height:20px;" id="Show_Dynamic_Sites">
                        	<span style="font-size:22px; font-weight:bold;">UNIT 1</span><br> <span style="font-size:12px;">Location - Compressor Room (Left end)</span>
                        </div>
                        
                        

                        <div style="float:right; margin-top:15px; margin-right:15px;">
                   		  <img src="../images/previous_next_arrow.png" border="0" usemap="#Map">
                            <map name="Map">
                              <area shape="circle" coords="23,20,16" href="javascript:LeftArrow_Click();">
                              <area shape="circle" coords="61,22,15" href="javascript:RightArrow_Click();">
                            </map>
                      </div>
                        
                        <div class="clear"></div>
                        
                    </div>
                    <div class="Window_Container_Bg" >
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:310px; color:#999999;" id="">

                        
                        <div style="color:#666666; float:left; width:300px;">
                        	<span style="font-weight:bold; font-size:18px;">UNIT 1 - GTR 9600</span><br>TD-1 - (GTR 9600 MODEL)
                        </div>
                        
                        <div style="float:left; margin-left:20px; margin-top:10px;">
                        	<div style="border:1px solid #666666; float:left; padding:0px 5px; background-color:#CCCCCC; border-radius:5px; text-transform:uppercase; color:#000000;">Override</div>
                        	<div style="float:left; margin-left:5px;" class="system_on">&nbsp;</div>
                            <div class="clear"></div>
                        </div>
                        
                        <div class="clear"></div>
                        
                        
                        <div  style="float:left; width:370px; height:155px; background-repeat:no-repeat; background-image:url(<?php echo URL?>images/airturnover_bg.png); color:#FFFFFF; padding:30px;">
                        	Test Message
                        </div>
                        
                        <div style="float:left; margin-left: 10px; width: 108px; padding: 5px; text-align: center;">
                        	
                            <div style="border:1px solid #CCCCCC; border-radius:5px;">
                            	<div style="font-size:13px; text-decoration:underline; margin-bottom:5px;">ROOM</div>
                                <div style="background-image:url(../images/thermometer_icon.png); background-repeat:no-repeat; font-weight:bold; height:33px; font-size:18px; color:#666666; text-align: left;   padding-left: 30px; margin-left:5px; background-position:0px 5px;">72.3&deg;F</div>
                            	<div style="background-image:url(../images/humidity_icon.png); background-repeat:no-repeat; font-weight:bold; height:33px; font-size:18px; color:#666666;  text-align: left;   padding-left: 28px; margin-left:7px;">23%</div>
                            </div>
                           
                        	
                            <div style="border:1px solid #CCCCCC; border-radius:5px; margin-top:5px;">
                            	<div style="font-size:13px; text-decoration:underline; margin-bottom:5px;">BUILDING</div>
                                <div style="background-image:url(../images/thermometer_icon.png); background-repeat:no-repeat; font-weight:bold; height:33px; font-size:18px; color:#666666; text-align: left;   padding-left: 30px; margin-left:5px; background-position:0px 5px;">72.3&deg;F</div>
                            	<div style="background-image:url(../images/humidity_icon.png); background-repeat:no-repeat; font-weight:bold; height:33px; font-size:18px; color:#666666;  text-align: left;   padding-left: 28px; margin-left:7px;">23%</div>
                            </div>
                            
                        </div>
                        
                        <div class="clear"></div>
                        
                        	<div style="float:left; margin-top:9px;"><img src="<?php echo URL?>images/power_on_icon.png" /></div>
                            <div style="float:left; margin-left:5px;"><?php echo date("h:m a")?><br><?php echo date("M d, Y")?></div>
                            <div style="float:left; margin-left:20px; font-size:15px; width:235px;">1 0F 3 UNITS INSTALLED<br>
                            <span style="font-size:12px;">MANUFACTURER - THERMOCYCLER</span>
                            </div>
                            
                            <div style="float:left; margin-left:30px; margin-top:10px;"><img src="<?php echo URL?>images/settings_icon.png" /></div>
                            <div style="float:right; margin-right:30px; border:1px solid #CCCCCC; border-radius:5px;   padding: 0px 10px; background-color: #CCCCCC; color: #000000; font-size: 16px; margin-top:10px;">MAP</div>
                            <div class="clear"></div>                    
                        
                        </div>
                        
                        <div style="padding:15px 10px 10px 20px; display:none; min-height:310px;" id="">&nbsp;</div>
                   		
                   

                    </div>
                    
                </div>
            
            
            <div class="clear"></div>
            
            
            
            <div id="Customer_Left_Panel" style="width:93%;">
            	
                
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:3; width:35px; height:159px; top:70px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Summary_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0;  -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:100px; margin-left:5px; font-size:15px; font-weight:bold; color:#666666;" id="Site_Details_Summary_Text">Units</div>
                    </div>
                	<div style="position:absolute; z-index:2; width:35px; height:159px; top:180px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_GHG_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:127px; margin-left:5px; font-size:15px; font-weight:bold; color:#FFFFFF;" id="Site_Details_GHG_Text">Schedules</div>
                    </div>
                    <div style="position:absolute; z-index:1; width:35px; height:159px; top:290px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Metrics_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:100px; margin-left:5px; font-size:15px; font-weight:bold; color:#666666;" id="Site_Details_Metrics_Text">Alarms</div>
                    </div>
                    <!--
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:400px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Energy_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:110px; margin-left:5px; font-size:15px; font-weight:bold; color:#FFFFFF;" id="Site_Details_Energy_Text">Savings</div>
                    </div>
                    -->
                </div>
                
                
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px; width:100%;">
                	<div class="Window_Title_Bg" style="width:100%;">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading" id="Large_Graph_Type">AIR TURNOVER SYSTEMS</div>							
                        </div>                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="../images/window_title_divider.png" />
                        </div>
                        
                        
                      	<div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;">
                        	<div id="SubTitle_Temperature">
                            	<select id="" name="" style="text-transform:uppercase;">
                                	<option value="1">UNIT 1 - GTR 9600</option>                                    
                                </select>
                            </div>                        
                            
                        </div>
                        
                        <div style="float:left; margin-left:40px; margin-top:20px; font-size:18px; color:#666666;">
                        	Local Time:  <?php echo date("M jS, h:ia")?>
                        </div>
                        
                        <div style="float:right; margin-right:20px; margin-top:20px;">
                        	<select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBuildingForChartList" id="ddlBuildingForChartList" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                            	<option value="">Select</option>
                            </select>
                        </div>
                        
                        <div class="clear"></div>
                       
                                           
                    </div>
                    
                    
                    <div class="Window_Container_Bg">                    
                    	<div style="padding:15px 10px 10px 20px; min-height:450px;" id="Large_Graph_Area">        
                        
                        	<div style="float:left; font-size:18px; font-weight:bold;">UNIT 1 - GTR 9600	-</div>
                            <div style="float:left; margin-left:10px;">
                            	<select id="" name="" style="text-transform:uppercase; width:350px; font-size:18px; font-family:UsEnergyEngineers;">
                                	<option value="1">Operating Activity Trending</option>  
                                    <option>ALARMS</option>
                                    <option>COSTING</option>
                                    <option>ENERGY</option>                                    
                                    <option>SCHEDULES</option>
                                    <option>UNIT RECORDS</option>                      
                                </select>
                            </div>
                            <div style="float:left;  margin-left:10px;">
                            	<img src="<?php echo URL;?>images/airturnover_refresh.png" />
                            </div>
                            
                            <div style="float:right; margin-right:10px;">
                            	<select id="" name="" style="text-transform:uppercase; width:250px; font-size:18px; font-family:UsEnergyEngineers;">
                                	<option value="1">Burner Activity</option>
                                    <option>CURRENT (AMPS)</option>
                                    <option>VOLTAGE (V)</option>                         
                                </select>
                            </div>
                            
                            <div class="clear" style="margin-bottom:10px;"></div>
                            
                            
                            
                            
                        <div style="float:left; width:60%; border:1px solid #CCCCCC; border-radius:5px; min-height:380px;">
                        
                        	<script type="text/javascript">
								$(function () {

									$.getJSON('http://www.highcharts.com/samples/data/jsonp.php?filename=large-dataset.json&callback=?', function (data) {
								
										// Create a timer
										var start = +new Date();
								
										// Create the chart
										$('#chart_operating_activity_trending').highcharts('StockChart', {
											chart: {
												events: {
													load: function () {
														if (!window.isComparing) {
															this.setTitle(null, {
																text: 'Built chart in ' + (new Date() - start) + 'ms'
															});
														}
													}
												},
												zoomType: 'x'
											},
								
											rangeSelector: {
												
												buttons: [{
													type: 'day',
													count: 3,
													text: '3d'
												}, {
													type: 'week',
													count: 1,
													text: '1w'
												}, {
													type: 'month',
													count: 1,
													text: '1m'
												}, {
													type: 'month',
													count: 6,
													text: '6m'
												}, {
													type: 'year',
													count: 1,
													text: '1y'
												}, {
													type: 'all',
													text: 'All'
												}],
												selected: 3
											},
								
											yAxis: {
												title: {
													text: 'Temperature (°C)'
												}
											},
								
											title: {
												text: 'Hourly temperatures in Vik i Sogn, Norway, 2004-2010'
											},
								
											subtitle: {
												text: 'Built chart in ...' // dummy text to reserve space for dynamic subtitle
											},
								
											series: [{
												name: 'Temperature',
												data: data,
												pointStart: Date.UTC(2004, 3, 1),
												pointInterval: 3600 * 1000,
												tooltip: {
													valueDecimals: 1,
													valueSuffix: '°C'
												}
											}]
								
										});
									});
								});
							</script>
                        	<div id="chart_operating_activity_trending" style="height: 300px; width: 97%"></div>
                        
                        </div>
                        
                        <div style="float:left; width:38%; margin-left:1%; color:#666666; ">
                        	
                            <div style="border:1px solid #CCCCCC; border-radius:5px; min-height:175px; padding:5px;">
                            	<div style="float:left; font-weight:bold; margin-top:5px; font-size:15px;">UNIT 1 GTR 9600- BURNER ACTIVITY</div>
                                <div style="float:right; margin-right:5px;">
                                	<select id="" name="" style="width:120px;">
                                    	<option value="">May 2015</option>
                                    </select>
                                </div>
                                <div class="clear"></div>
                                
                                <script type="text/javascript">
									$(document).ready(function(){
										 $('#CircualPlaceholderChart_1').circliful();	
										  $('#CircualPlaceholderChart_2').circliful();											 
									});
								</script>
                                <div id="CircualPlaceholderChart_1" style="margin:10px auto 0px auto; float:left; width:150px;" data-dimension="130" data-text="Burner On" data-info="30%" data-width="10" data-info-fontsize="24" data-fontsize="20" data-percent="30" data-fgcolor="#61a9dc" data-bgcolor="#eee" data-fill="#ddd"></div>
								
                                <div style="float:left; margin-left:20px; font-size:20px; margin-top:40px; line-height:35px;">
                                	On for <u>03</u> total hours<br>
									Off for <u>57</u> total hours
                                </div>
                                <div class="clear"></div>
                                
                                
                            </div>
                            
                            
                            
                            <div style="border:1px solid #CCCCCC; border-radius:5px; min-height:175px; padding:5px; margin-top:5px;">
                            	<div style="float:left; font-weight:bold; margin-top:5px; font-size:15px;">UNIT 1 GTR 9600- BURNER ACTIVITY</div>
                                <div style="float:right; margin-right:5px;">
                                	<select id="" name="" style="width:120px;">
                                    	<option value="">2014-2015</option>
                                    </select>
                                </div>
                                <div class="clear"></div>
                                
                                
                                <div id="CircualPlaceholderChart_2" style="margin:10px auto 0px auto; float:left; width:150px;" data-dimension="130" data-text="Burner On" data-info="30%" data-width="10" data-info-fontsize="24" data-fontsize="20" data-percent="30" data-fgcolor="#61a9dc" data-bgcolor="#eee" data-fill="#ddd"></div>
								
                                <div style="float:left; margin-left:20px; font-size:20px; margin-top:40px; line-height:35px;">
                                	On for <u>113</u> total hours<br>
									Off for <u>57</u> total hours
                                </div>
                                <div class="clear"></div>
                                
                                
                            </div>
                        </div>
                        
                        <div class="clear"></div>
                            
                                                  
                        </div>
                        
                        
                        
                        
                        
                        
                        
                    </div>                    
                </div>
              
                <div class="clear"></div>
                
                <br>
			
            
            </div>
            
             
   		
		 <div class="clear"></div>
        
    </div>
  
  </body>
<script src="<?php echo URL?>highstock/js/highstock.js"></script>
<script src="<?php echo URL?>highstock/js/modules/exporting.js"></script>
<script src="<?php echo URL?>highcharts/js/highcharts.js"></script>
<script src="<?php echo URL?>highcharts/js/modules/exporting.js"></script>  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

</html>
