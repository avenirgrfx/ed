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

$_SESSION['user_login']->user_id=1;
$_SESSION['user_login']->login_id=1;

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
		$(document).ready(function(){
		 
			 //$('#myStat').circliful();
			 
			$('#Gray_Button').click(function(){
				$('#Gray_Button').css('z-index',1);
				$('#Blue_Button').css('z-index',0);
				
				$.get("<?php echo URL?>ajax_pages/customers/building_elements.php",
			  	{
					type:1				
			  	},
			  	function(data,status){						
					$('#Building_Elements').html(
						 data
						);				
			  	});
				
			});
			
			$('#Blue_Button').click(function(){				
				$('#Blue_Button').css('z-index',1);
				$('#Gray_Button').css('z-index',0);
				
				$.get("<?php echo URL?>ajax_pages/customers/building_elements.php",
			  	{
					type:2			
			  	},
			  	function(data,status){						
					$('#Building_Elements').html(
						 data
						);				
			  	});
				
			});
			
			$('#Gray_Button_Text').click(function(){
				$('#Gray_Button').trigger('click');				
			});
			
			$('#Blue_Button_Text').click(function(){				
				$('#Blue_Button').trigger('click');
			});
			
			
			
			$('#Energy_Summary_Consumption').click(function(){
				$('#electric_energy_system').html('Loading...');				
				$('#Energy_Summary_Consumption').css('z-index','2');
				$('#Energy_Summary_Cost').css('z-index','1');
				$('#System_Summary_Details_Title').html('SYSTEM SUMMARY');
				
				$.get("<?php echo URL?>ajax_pages/customers/electric_energy_system.php",
				{
					type:1
				},
				function(data,status){						
					$('#electric_energy_system').html(data);		
				});	
				
				
				$.get("<?php echo URL?>ajax_pages/customers/energy_summary.php",
				{
					type:1
				},
				function(data,status){
						
					$('#energy_summary_dynamic_content').html(data);	
					$('#CircualChart_1').circliful();
	 				$('#CircualChart_2').circliful();				
				});	
				
			});
			
			$('#Energy_Summary_Consumption').trigger('click');	
			$('#Energy_Summary_Consumption_Text').click(function(){
				$('#Energy_Summary_Consumption').trigger('click');
			});
			
			
			
			$('#Energy_Summary_Cost').click(function(){
				$('#electric_energy_system').html('Loading...');				
				$('#Energy_Summary_Consumption').css('z-index','1');
				$('#Energy_Summary_Cost').css('z-index','2');
				$('#System_Summary_Details_Title').html('SYSTEM DETAILS');
				
				$.get("<?php echo URL?>ajax_pages/customers/electric_energy_system.php",
				{
					type:2
				},
				function(data,status){						
					$('#electric_energy_system').html(data);	
				});	
				
				
				$.get("<?php echo URL?>ajax_pages/customers/energy_summary.php",
				{
					type:2
				},
				function(data,status){
						
					$('#energy_summary_dynamic_content').html(data);
					$('#CircualChart_3').circliful();
	 				$('#CircualChart_4').circliful();					
				});	
				
			});
			
			
			$('#Energy_Summary_Cost_Text').click(function(){
				$('#Energy_Summary_Cost').trigger('click');
			});
			
			
			$('#Site_Details_Summary_Button').trigger('click');
			$('#Gray_Button').trigger('click');
			
		});
		
		
		function LeftArrow_Click()
		{
			alert("Left arrow clicked!");
		}
		
		function RightArrow_Click()
		{
			alert("Right arrow clicked");
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
                    <li class="LargeMenu_Customer"><a href="<?php echo URL?>customer/">SUMMARY</a></li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer"><a href="<?php echo URL?>customer/graph.php">GRAPHS</a></li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer active"><a href="<?php echo URL?>customer/systems.php">SYSTEMS</a></li>
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
                
                	<div style="position:absolute; z-index:1; width:35px; height:159px; top:70px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Gray_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Gray_Button_Text">Buildings</div>
                    </div>
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:170px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Blue_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:115px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Blue_Button_Text">Elements</div>
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
                        
                        
                        <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;">
                        	ALL SITES
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
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:324px;" id="Building_Elements">
                        
                           &nbsp;
                        
                        </div>
                   

                    </div>
                    
                </div>
              
                <div class="clear"></div>
                
                
<br>

            
            </div>
            
            
            <div id="Customer_Right_Panel">
            
            	<div style="width:96%; padding:1% 0%; border-radius:10px; min-height:355px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">
                	
                    <div style=" padding:5px; border-radius:5px;">                 
                        	
                        <div style="float:left; margin-left:10px;  margin-top:5px;">                        	
                            <select name="" id="" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers; background-color:#EFEFEF;">
                            	<option value="">Zeeland Mainsite</option>
                            </select>
                        </div>
                        
                        <div style="float:right; margin-top:5px;">
                        	<select name="ddlGraphType" id="ddlGraphType" style="width:200px; font-size:14px; font-family: UsEnergyEngineers; background-color:#EFEFEF">
                            	<option value="1">ELECTRIC SYSTEM</option>                               
                            </select>                        	
                        </div>
                        <div class="clear"></div>                         
                    </div>
                    
                    <hr style="border-color:#DEDEDE; margin-bottom:5px;" />
                	
                    <div id="electric_energy_system">Loading...</div>
              		
                    
                </div>
            
            </div>
            <div class="clear"></div>
            
            
            
            
            
              
            <div id="Customer_Left_Panel">
            	
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:2; width:35px; height:159px; top:70px; background-image:url(<?php echo URL?>/images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Energy_Summary_Consumption">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:120px; margin-left:5px; font-size:16px; font-weight:bold; color:#666666;" id="Energy_Summary_Consumption_Text">Summary</div>
                    </div>
                	<div style="position:absolute; z-index:1; width:35px; height:159px; top:180px; background-image:url(<?php echo URL?>/images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Energy_Summary_Cost"> 
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:105px; margin-left:5px; font-size:16px; font-weight:bold; color:#FFFFFF;" id="Energy_Summary_Cost_Text">Details</div>
                    </div>
                    
                    
                </div>
                
                
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                	<div class="Window_Title_Bg">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading" id="System_Summary_Details_Title">SYSTEM SUMMARY</div>
							
                        </div>
                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="<?php echo URL?>/images/window_title_divider.png" />
                        </div>
                        
                        <div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;">ENERGY SYSTEMS</div>
                        
                        <div class="clear"></div>
                        
                    </div>
                    <div class="Window_Container_Bg">
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:575px;">
                        
                            <div style="float:left;">
                               
                                <select name="" id="" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
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
                              
                              
                                <div id="energy_summary_dynamic_content">
                             
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
                    
                        
                        <div style="float:left; margin-top:5px;" class="heading">ENERGY SYSTEMS</div>
                        	
                        
                        <div class="clear"></div>
                        <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px;" />                      
                        
                        <div id="Workspace_Element_Container" style="border:1px solid #CCCCCC; margin-bottom:10px;">
                        	<!-- will load dynamic element with live updates-->
                        	<img src="http://khwab.net/energydas/element_image/Dean%20Workspace.png" />
                        </div>
                		
                        <div style="float:left; width:25%; border:1px solid #CCCCCC; border-radius:5px; height:150px; text-align:center;">
                            <div style="text-decoration:underline; font-size:16px; font-weight:bold;">OA CONDITIONS</div>
                            <div style="margin-bottom:10px;"><img src="<?php echo URL?>images/oa_conditions_icon.png" /></div>
                            <div style="float:left; width:50%; text-align:left;">Temp.</div>
                            <div style="float:left; border:1px solid #999999; width:45%;">75.4 &deg; F</div>
                            <div class="clear" style="margin-bottom:10px;"></div>
                            
                            <div style="float:left; width:50%; text-align:left;">Humidity</div>
                            <div style="float:left; border:1px solid #999999;  width:45%;">95.0 % RH</div>
                            <div class="clear"></div>
                            
                        </div>
                        
                        
                        <div style="float:left; width:73%; margin-left:1%; border:1px solid #CCCCCC; border-radius:5px; height:140px; padding-top:10px;">
                        	
                            
                            <div style="float:left; width:45%; margin-left:1%; font-size:12px;">
                            	<div style="float:left; width:65%;">CHW System Enable</div>
                                <div style="float:left; width:30%; border:1px solid #999999; text-align:center; ">True</div>
                                <div class="clear"></div>
                            </div>
                            
                            <div style="float:left; width:45%; margin-left:4%; font-size:12px;">
                            	<div style="float:left; width:65%;">ACCH-1 Runtime</div>
                                <div style="float:left; width:30%; border:1px solid #999999; text-align:center; ">1500.6 hrs</div>
                                <div class="clear"></div>
                            </div>                            
                            <div class="clear" style="margin-bottom:3px;"></div>
                            
                             <div style="float:left; width:45%; margin-left:1%; font-size:12px;">
                            	<div style="float:left; width:65%;">CHW Supply Setpoint</div>
                                <div style="float:left; width:30%; border:1px solid #999999; text-align:center; ">43.0 &deg; F</div>
                                <div class="clear"></div>
                            </div>
                            
                            <div style="float:left; width:45%; margin-left:4%; font-size:12px;">
                            	<div style="float:left; width:65%;">Lead Pump</div>
                                <div style="float:left; width:30%; border:1px solid #999999; text-align:center; ">1</div>
                                <div class="clear"></div>
                            </div>                            
                            <div class="clear" style="margin-bottom:3px;"></div>
                            
                             <div style="float:left; width:45%; margin-left:1%; font-size:12px;">
                            	<div style="float:left; width:65%;">ACCH-2 Runtime</div>
                                <div style="float:left; width:30%; border:1px solid #999999; text-align:center; ">1497.8 hrs</div>
                                <div class="clear"></div>
                            </div>
                            
                            <div style="float:left; width:45%; margin-left:4%; font-size:12px;">
                            	<div style="float:left; width:65%;">OA Enable Setpoint</div>
                                <div style="float:left; width:30%; border:1px solid #999999; text-align:center; ">60.0 &deg; F</div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear" style="margin-bottom:3px;"></div>
                            
                        </div>
                        
                        <div class="clear" style="margin-top:5px;"></div>
                        
                        <div style="float:left; text-align:right; width:65%;">ELECTRIC Consumption</div>
                    	<div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">181,865 BTU</div> 
                        <div class="clear" style="margin-top:5px;"></div>
                        
                        <div style="float:left; text-align:right; width:65%;">GAS Consumption</div>
                        <div style="float:right; text-align:center; margin-left:5px; width:25%;" class="gray_box_for_value">181,865 BTU</div>
                        <div class="clear"></div>
                   
                </div>
            
            </div>
            
            <div class="clear"></div>
            
        </div>
   		
		 <div class="clear"></div>
         
        
    </div>
  
  	</div>
    
  </body>
  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

</html>
