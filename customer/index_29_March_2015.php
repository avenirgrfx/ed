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

$_SESSION['user_login']->user_id=1;
$_SESSION['user_login']->login_id=1;
$_SESSION['client_id']=5;
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
$rsSiteArr=$DB->Returns($strQuery);
$strSiteCount=mysql_num_rows($rsSiteArr);
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
    <script src="<?php echo URL?>js/jquery-ui.js"></script>
    <script src="<?php echo URL?>js/jquery.circliful.min.js"></script>
    
    <script type="text/javascript">
		
		var SiteSerial=-1;
		var SiteCount=<?php echo ($strSiteCount-1);?>;
	
		
		$(document).ready(function(){
			
						
			$('#myStat').circliful();
		
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
			
			
			$('#Consumption_Electric_System').html('Loading...');			
			$.get("<?php echo URL?>ajax_pages/customers/system_list_by_building_electric_system.php",
			{
				
				building_id:strBuildingID
			},			
			function(data,status){						
				$('#Consumption_Electric_System').html(data);
			});
			
			//$('#Container_SystemsByBuilding').html('asdsfds');
			
			/*var values = [];
			$('#ddlBuildingForSite option').each(function() { 
				values.push( $(this).attr('value') );
				
			});*/
			
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
            	<img src="http://khwab.net/energydas/uploads/customer/160215nibco.png" />
            </div>
            <div style="float:left; margin-left:50px;">
            	<h5>HERMAN MILLER CORPORATION</h5>
                <span style="font-size:24px;">ENTERPRISE</span>
            </div>
            <div style="float:right; text-align:right; font-size:18px; margin-top:17px; font-weight: bold; color: #CCCCCC;">
            	energyDAS<br>
                <?php echo date("g:i a F dS, Y");?>
                
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="GrayBackground">
    		<div id="Customer_Menu_Section">
            	<div class="TopMenu_Customer">HOME</div>
            	<div class="TopMenu_Customer">CORPORATE</div>
                <div class="TopMenu_Customer TopMenu_Customer_active">OPERATIONS</div>
                <div class="TopMenu_Customer">BILLING</div>   
                <div class="TopMenu_Customer">PROGRAMS</div>             
                <div class="TopMenu_Customer TopMenu_Customer_active" style="float:right; font-weight:normal; font-size:16px; padding:13px;">&nbsp;&nbsp;<img src="<?php echo URL?>images/support_envelop_icon.png" />&nbsp; |&nbsp;&nbsp; SUPPORT</div>
                <div class="TopMenu_Customer" style="float:right; font-size:16px; padding:13px;">PROJECTS</div>
                <div class="clear"></div>
            </div>
            
            
            <div id="MenuBar_Gray">
                <ul>
                    <li class="LargeMenu_Customer active"><a href="<?php echo URL?>customer/">SUMMARY</a></li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer"><a href="<?php echo URL?>customer/graph.php">GRAPHS</a></li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer"><a href="<?php echo URL?>customer/systems.php">SYSTEMS</a></li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer">CONTROLS</li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer">REPORTS</li>
                    
                    <li style="background:none; border:none; font-size:16px; float:right; cursor:default;">
                    	<div style="float:left; margin-top:3px;"><img src="<?php echo URL?>images/person_icon.png" /></div>
                    	<div style="margin-top:6px; margin-left:10px; float:left;">Felix Goto</div>
                        <div class="clear"></div>
                    </li>
                    <li  style="background:none; border:none; float:right; margin-top: 16px; margin-left:5px; cursor:default; color:#CCCCCC;">|</li>
                    
                    <li style="background:none; border:none; font-size:16px; float:right; cursor:default;">
                    	<div style="float:left;"><img src="<?php echo URL?>images/lock_icon.png" /></div>
                    	<div style="margin-top:6px; margin-left:10px; float:left;">ADMINISTRATOR</div>
                        <div class="clear"></div>
                    </li>
                 </ul>     
     			<div class="clear"></div>     
  			</div>
            
            <div class="clear"></div>
            
            <br>
            
            
            <div id="Customer_Left_Panel">
            	
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:1; width:35px; height:159px; top:70px; background-image:url(<?php echo URL?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Gray_Button_Text">Buildings</div>
                    </div>
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:170px; background-image:url(<?php echo URL?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Blue_Button_Text">Elements</div>
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
                    <div class="Window_Container_Bg">
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:310px;" id="Building_Elements">&nbsp;</div>
                        <div style="padding:15px 10px 10px 20px; display:none; min-height:310px;" id="Building_Elements_Details">&nbsp;</div>
                   

                    </div>
                    
                </div>
              
                <div class="clear"></div>
                
                
<br>

            
            </div>
            
            
            <div id="Customer_Right_Panel">
            
            	<div style="width:94%; padding:3%; border-radius:10px; min-height:300px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">
                	<div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:250px; border-radius:5px;">
                    
                        
                        <div style="float:left; margin-top:5px;" class="heading">BENCHMARK</div>
                        	
                        <div style="float:left; margin-left:25px;  margin-top:5px;">
                        	
                            <select onChange="UpdateAllBuildingDropdown(this.value)" name="ddlBenchMarkBuilding" id="ddlBenchMarkBuilding" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                            	<option value="">Select Building</option>
                            </select>
                        </div>
                        
                        <div style="float:right; margin-top:5px;">
                        	<select name="" id="" style="width:130px; font-size:12px; font-family: UsEnergyEngineers;">
                            	<option value="">September 2014</option>
                            </select>                        	
                        </div>
                        <div class="clear"></div>
                        <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:5px;" />                      
                        
                       
                       <div style="float:left; width:72%;">
                           	SITE MONTH EUI BENCHMARK FROM TARGET 
                           <div>                          		                      	
                                <div style="float:left;" class="energy_benchmark_boxes">SITE - <strong class="green_font">23%</strong></div>
                                <div style="float:left;" class="energy_benchmark_boxes">SITE TARGET- <strong>52%</strong></div>
                                <div style="float:left;" class="energy_benchmark_boxes">CORPORATE TARGET - <strong>21%</strong></div>
                                <div class="clear"></div>
                            </div>
                            
                            <div style="margin:10px auto; text-align:left;">
                            	SITE MONTH EUI BENCHMARK & TARGET
                                
                                <div style="width:100%; background-image:url(<?php echo URL?>/images/slider_benchmark_target.png); background-repeat:no-repeat; height: 37px; background-position-y: 14px; background-position-x:50%;">
                            		<div class="light_blue_bar left_bar" style="width:60%; float:left;">&nbsp;</div>
                                	<div class="gray_bar right_bar" style="width:40%; float:left;">&nbsp;</div>
                                	<div class="clear"></div>
                                </div>
                            </div>
                            
                             <div style="margin:5px auto; text-align:left;">
                            	CORPORATE MONTH EUI BENCHMARK & TARGET (4% CORPORATE)                                
                                <div style="width:100%; background-image:url(<?php echo URL?>/images/slider_benchmark_target.png); background-repeat:no-repeat; height: 37px; background-position-y: 14px; background-position-x:55%;">
                            		<div class="light_blue_bar left_bar" style="width:40%; float:left;">&nbsp;</div>
                                	<div class="gray_bar right_bar" style="width:60%; float:left;">&nbsp;</div>
                                	<div class="clear"></div>
                                </div>
                            </div>
                        
                        </div>
                        
                        <div style="float:left;" class="portfoli_manager_score_container">
                        	<div style="margin-top:36px;text-align:center;">Your building score</div>
                            <div style="text-align: center; margin-top: 4px; font-size: 20px; color: #FFFFFF;">64</div>
                            
                            <div style="margin-top:11px; text-align:center;">Avg. Score for District</div>
                            <div style="text-align: center; margin-top: 4px; font-size: 20px; color: #FFFFFF;">12</div>
                        </div>
                        
                        <div class="clear"></div>
                        
                        
                        
                        <div>
                        	<div style="float:left; border:1px solid #CCCCCC; text-align:center;">
                            	
                                <div style="background-color:#DDDDDD; font-size:14px; padding:3px 7px;">Site Realtime Energy EUI</div>
                                <div class="green_font" style="font-size:16px; padding:6px 0px;"><strong>1.23</strong> kBtu/ft<sup>2</sup></div>
                            
                            </div>
                            
                            <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
                            	 <div style="background-color:#DDDDDD; font-size:14px; padding:3px 7px;;">Yesterday Energy EUI</div>
                                <div class="red_font" style="font-size:16px; padding:6px 0px;"><strong>1.73</strong> kBtu/ft<sup>2</sup></div>
                            
                            </div>
                            
                            <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
                            	 <div style="background-color:#DDDDDD; font-size:14px; padding:3px 7px;">Month Energy EUI</div>
                                <div class="red_font" style="font-size:16px; padding:6px 0px;"><strong>1.45</strong> kBtu/ft<sup>2</sup></div>
                            
                            </div>
                            
                             <div style="float:left; border:1px solid #CCCCCC; margin-left:7px; text-align:center;">
                            	 <div style="background-color:#DDDDDD; font-size:14px; padding:3px 7px;">Target EUI</div>
                                <div  style="font-size:16px; padding:6px 2px;"><strong>1.25</strong> kBtu/ft<sup>2</sup></div>
                            
                            </div>
                            
                            <div class="clear"></div>
                            
                        </div>
                        
                    </div>
                	
                    <div style="margin-top:10px;">
                        <div class="benchmark_button_active" style="float:left;">Energy Use Intensity (EUI)</div>
                        <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                        <div style="float:left; margin-left:10px; padding:5px;">Energy Cost Index (ECI)</div>
                        <div style="float:right; background-color:#FFFFFF; border-radius:10px; padding:5px 15px; border:1px solid #CCCCCC;"><a href="#Portfolii_Link" target="_blank"><img src="<?php echo URL?>/images/portfolio_manager_logo.png" border="0" /></a></div>
                        <div class="clear"></div>
                   </div>
                   
                </div>
            
            </div>
            <div class="clear"></div>
            
            
            
            <div id="Customer_Left_Panel">
            	
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:3; width:35px; height:159px; top:70px; background-image:url(<?php echo URL?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Summary_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Site_Details_Summary_Text">Summary</div>
                    </div>
                	<div style="position:absolute; z-index:2; width:35px; height:159px; top:180px; background-image:url(<?php echo URL?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_GHG_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:105px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Site_Details_GHG_Text">GHG</div>
                    </div>
                    <div style="position:absolute; z-index:1; width:35px; height:159px; top:290px; background-image:url(<?php echo URL?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Metrics_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Site_Details_Metrics_Text">Metrics</div>
                    </div>
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:400px; background-image:url(<?php echo URL?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Energy_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Site_Details_Energy_Text">Energy</div>
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
                            	<option value="">Zeeland Mainsite</option>
                            </select>
                                                            
                            </div>
                            
                            <div style="float:right; margin-left:30px;">
                               <select name="" id="" style="width:130px; font-size:12px; font-family: UsEnergyEngineers;">
                            	<option value="">September 2014</option>
                            </select>
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
                        	<select name="" id="" style="width:130px; font-size:12px; font-family: UsEnergyEngineers;">
                            	<option value="">September 2014</option>
                            </select>                        	
                        </div>
                        <div class="clear"></div>
                        <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px;" />                      
                        
                        
                        <div>                        
                        	<div style="float:left; margin-top:2px;">ELECTRIC CONSUMPTION NOW</div>
                            <div style="float:left; margin-left:3px;" class="light_blue_box_for_value">181,865 kWh</div>
                          
                        	
                            <div style="float:left; margin-left:6px; margin-top:2px;">GAS CONSUMPTION NOW</div>
                            <div style="float:left; margin-left:3px;" class="gray_box_for_value">1,865 Therms</div>
                            <div class="clear"></div>
                        	
                            <div style="margin:10px 0px;">
                            	<img src="<?php echo URL?>/images/placeholder_consumption_chart.png" />
                            </div>
                            
                            <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:10px;">
                            
                            <div style="margin-bottom:10px; color:#666666; font-weight:bold; font-size:16px; text-decoration:underline; text-align:center;">Systems Consumption Breakout</div>
                            
                            <div style="float:left; width:48%; margin:1%;">
                            	<div style="float:left; font-weight:bold;">Electric System</div> <div style="float:right; margin-right:20px; font-weight:bold;"> % Total</div><div class="clear"></div>
                                <div style=" padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; border-bottom:1px solid #999999; height:150px; overflow-y: scroll;" id="style-2">
                            	
                                <div id="Consumption_Electric_System">
                            		Loading...
                                </div>
                            	
                                		
                    		</div>
                            	
                                <div style="float:left; width:83%;">                                
                                    <div class="clear" style="margin-top:10px;"></div>
                                    <div style="float:left; width:100px; text-align:center; margin-top:3px; font-weight:bold;">Total Electric</div>
                                    <div style="float:left; min-width: 106px; " class="normal_blue_box_for_value">11,181,865 kWh</div>
                                    <div class="clear"></div>
                                    
                                    <div class="clear" style="margin-top:3px;"></div>
                                    <div style="float:left; width:100px; text-align:center; font-size:12px; margin-top:3px;">Utility Disconnect</div>
                                    <div class="light_blue_box_for_value" style="float:left; min-width:104px; font-weight:normal; background:none; border:1px solid #DDDDDD;" >181,865 kWh</div>
                                    
                                    <div class="clear"></div>
                                </div>
                                
                                <div style="float:left; margin-left:3px;" class="right_bracket_bg">
                                	<div style="margin-top:25px; background-color:#FFFFFF;">-1.8%</div>
                            	</div>
                                
                                <div class="clear"></div>
                                
                            </div>
                            
                            <div style="float:left; width:48%; margin:1%;">
                            	
                                
                                <div style="float:left; font-weight:bold;">Natural Gas System</div> <div style="float:right; margin-right:20px; font-weight:bold;"> % Total</div><div class="clear"></div>
                             
                                
                                <div style=" padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; border-bottom:1px solid #999999; height:150px; overflow-y: scroll;" id="style-2">
                            
                            	<div style="float:left; width:40%;"><span style="font-weight:bold;">+</span>HVAC:</div>
                                <div class="gray_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">1,615 therms</div>
                                <div style="float:right; margin-right:20px;">33%</div>
                                <div class="clear" style="margin-bottom:2px;"></div>
                                
                            	<div style="float:left; width:40%;"><span style="font-weight:bold;">+</span>Lighting:</div>
                                <div class="gray_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">250 therms</div>
                                <div style="float:right; margin-right:20px;">14%</div>
                                <div class="clear" style="margin-bottom:2px;"></div>
                                
                                <div style="float:left; width:40%;"><span style="font-weight:bold;">+</span>Office Load:</div>
                                <div class="gray_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">15 therms</div>
                                <div style="float:right; margin-right:20px;">05%</div>
                                <div class="clear" style="margin-bottom:2px;"></div>
                                
                                <div style="float:left; width:40%;"><span style="font-weight:bold;">+</span>Plant Load:</div>
                                <div class="gray_box_for_value" style="float:left; font-weight:normal; min-width: 80px;">215 therms</div>
                                <div style="float:right; margin-right:20px;">15%</div>
                                <div class="clear" style="margin-bottom:2px;"></div>
                                	
                            </div>
                            <div class="clear"></div>
                            
                             <div style="float:left; width:83%;">                                
                                    <div class="clear" style="margin-top:10px;"></div>
                                    <div style="float:left; width:100px; text-align:center; margin-top:3px; font-weight:bold;">Total Gas</div>
                                    <div style="float:left; min-width: 106px; " class="normal_blue_box_for_value">1,865 therms</div>
                                    <div class="clear"></div>
                                    
                                    <div class="clear" style="margin-top:3px;"></div>
                                    <div style="float:left; width:100px; text-align:center; font-size:12px; margin-top:3px;">Main Utility Gas</div>
                                    <div class="gray_box_for_value" style="float:left; min-width:104px; font-weight:normal; background:none; border:1px solid #DDDDDD;" >1,944 therms</div>
                                    
                                    <div class="clear"></div>
                                </div>
                                
                                <div style="float:left; margin-left:3px;" class="right_bracket_bg">
                                	<div style="margin-top:25px; background-color:#FFFFFF;">-1.8%</div>
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
  
  </body>
  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

</html>
