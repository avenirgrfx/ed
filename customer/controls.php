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
    <script type='text/javascript' src='<?php echo URL?>js/jquery-ui.js'></script>
    <script src="<?php echo URL?>js/jquery-ui.js"></script>
    <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="<?php echo URL?>js/jquery.circliful.min.js"></script>
    
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
			UpdateBuildingElementDetails(strBuildingID,0);
		}
		
		
		function ChangeBuildingDropdown(strBuildingID)
		{		
			$.get("<?php echo URL?>ajax_pages/customers/get_building_control.php",
			{
				building_id:strBuildingID
			},
			function(data,status){
				$('#Container_SystemsByBuilding').css('overflow-y','auto');
				$('#Container_SystemsByBuilding').removeClass('myscroll');	
				$('#Container_SystemsByBuilding').html(data);	
						
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
            
            
            <div id="Customer_Left_Panel" style="margin-left:300px;">
            	
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:1; width:35px; height:159px; top:70px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Gray_Button_Text">Controls</div>
                    </div>
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:170px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; -moz-transform: rotate(270deg); -o-transform: rotate(270deg); -webkit-transform: rotate(270deg); margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Blue_Button_Text">System</div>
                    </div>
                    
                </div>
                
                
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                	<div class="Window_Title_Bg">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading">CONTROLS</div>
							
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
            
            
            
            <div class="clear"></div>
            
            
            
            
            
              
            
            
             
   		
		 <div class="clear"></div>
         
        
    </div>
  
  	</div>
 
    
  </body>
  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

</html>
