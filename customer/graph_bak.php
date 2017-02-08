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
    
    <script type="text/javascript">
		$(document).ready(function(){
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
			
			
			
			
			$('#ddlGraphType').change(function(){
				$('#Graph_Summary_Window').html('Loading...');
				$('#Large_Graph_Area').html('Loading...');
				
				if(this.value==1)
				{
					$('#Large_Graph_Type').html('TEMPERATURE & HUMIDITY');
					$('#SubTitle_Temperature').css('display','block');
					$('#SubTitle_Consumption').css('display','none');										
					$('#SubTitle_Cost').css('display','none');
					$('#SubTitle_savings').css('display','none');
					$('#Graph_Type_Bottom_Options_1').css('display','block');
					$('#Graph_Type_Bottom_Options_2').css('display','none');
					$('#Graph_Type_Bottom_Options_3').css('display','none');
					$('#Graph_Type_Bottom_Options_4').css('display','none');
					
					$('#Site_Details_Energy_Button').css('z-index',1);
					$('#Site_Details_Metrics_Button').css('z-index',2);
					$('#Site_Details_GHG_Button').css('z-index',3);
					$('#Site_Details_Summary_Button').css('z-index',4);								
					 
				}
				else if(this.value==2)
				{
					$('#Large_Graph_Type').html('ENERGY CONSUMPTION');
					$('#SubTitle_Temperature').css('display','none');
					$('#SubTitle_Consumption').css('display','block');
					$('#SubTitle_Cost').css('display','none');
					$('#SubTitle_savings').css('display','none');
					$('#Graph_Type_Bottom_Options_1').css('display','none');
					$('#Graph_Type_Bottom_Options_2').css('display','block');
					$('#Graph_Type_Bottom_Options_3').css('display','none');
					$('#Graph_Type_Bottom_Options_4').css('display','none');
					
					$('#Site_Details_Energy_Button').css('z-index',1);
					$('#Site_Details_Metrics_Button').css('z-index',2);
					$('#Site_Details_Summary_Button').css('z-index',3);
					$('#Site_Details_GHG_Button').css('z-index',4);
				
				}
				else if(this.value==3)
				{
					$('#Large_Graph_Type').html('ENERGY COST BY SYSTEM');
					$('#SubTitle_Temperature').css('display','none');
					$('#SubTitle_Consumption').css('display','none');
					$('#SubTitle_Cost').css('display','block');	
					$('#SubTitle_savings').css('display','none');				
					$('#Graph_Type_Bottom_Options_1').css('display','none');
					$('#Graph_Type_Bottom_Options_2').css('display','none');
					$('#Graph_Type_Bottom_Options_3').css('display','block');
					$('#Graph_Type_Bottom_Options_4').css('display','none');
					
					$('#Site_Details_Energy_Button').css('z-index',1);
					$('#Site_Details_Metrics_Button').css('z-index',4);
					$('#Site_Details_Summary_Button').css('z-index',2);
					$('#Site_Details_GHG_Button').css('z-index',3);
					
				}
				
				else if(this.value==4)
				{
					$('#Large_Graph_Type').html('ENERGY SAVINGS BY SYSTEM'); 
					$('#SubTitle_Temperature').css('display','none');
					$('#SubTitle_Consumption').css('display','none');
					$('#SubTitle_Cost').css('display','none');					
					$('#SubTitle_savings').css('display','block');
					$('#Graph_Type_Bottom_Options_1').css('display','none');
					$('#Graph_Type_Bottom_Options_2').css('display','none');
					$('#Graph_Type_Bottom_Options_3').css('display','none');
					$('#Graph_Type_Bottom_Options_4').css('display','block');
					
					$('#Site_Details_Energy_Button').css('z-index',4);
					$('#Site_Details_Metrics_Button').css('z-index',3);
					$('#Site_Details_Summary_Button').css('z-index',2);
					$('#Site_Details_GHG_Button').css('z-index',1);
					
					
				}
				
				
				$.get("<?php echo URL?>ajax_pages/customers/graphs_type.php",
				{
					type:this.value
				},
				function(data,status){						
					$('#Graph_Summary_Window').html(data);		
				});	
				
				
				
				$.get("<?php echo URL?>ajax_pages/customers/large_graphs_type.php",
				{
					type:this.value
				},
				function(data,status){						
					$('#Large_Graph_Area').html(data);		
				});
				
				
				
				
						
				
			});
			
			$('#ddlGraphType').trigger('change');
			
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
                    <li class="LargeMenu_Customer active"><a href="<?php echo URL?>customer/graph.php">GRAPHS</a></li>
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
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:293px;" id="Building_Elements">
                        
                           &nbsp;
                        
                        </div>
                   

                    </div>
                    
                </div>
              
                <div class="clear"></div>
                
                
<br>

            
            </div>
            
            
            <div id="Customer_Right_Panel">
            
            	<div style="width:96%; padding:3% 2%; border-radius:10px; min-height:355px; background:-webkit-linear-gradient(180deg, #bbbbbb, white, #bbbbbb); border:1px solid #999999;">
                	
                    <div style="background-color:#FFFFFF; height:100%; border:1px solid #CCCCCC; padding:5px; min-height:300px; border-radius:5px;">
                    
                        	
                        <div style="float:left; margin-left:10px;  margin-top:5px;">
                        	
                            <select name="" id="" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                            	<option value="">Zeeland Mainsite</option>
                            </select>
                        </div>
                        
                        <div style="float:right; margin-top:5px;">
                        	<select name="ddlGraphType" id="ddlGraphType" style="width:200px; font-size:14px; font-family: UsEnergyEngineers;">
                            	<option value="1">TEMPERATURE & HUMIDITY</option>
                                <option value="2">CONSUMPTION</option>
                                <option value="3">COST BY SYSTEM</option>
                                <option value="4">SAVINGS BY SYSTEM</option>
                            </select>                        	
                        </div>
                        <div class="clear"></div>
                        <hr style="border-bottom:#CCCCCC 1px solid; margin-top:10px; margin-bottom:10px;" />                      
                        
                        
                        <div id="Graph_Summary_Window">Loading...</div>
                        
                    </div>
                	
                    
              		
                    <div id="Graph_Type_Bottom_Options_1" class="dark_green_box_for_value" style="width:200px; margin-top:10px; border-radius:10px; text-align:center; font-size:16px; text-transform:uppercase; float:right;" >Node Management</div>
                    <div class="clear"></div>
                    
                    <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_2">
                        <div class="benchmark_button_active" style="float:left;">Electric Consumption</div>
                        <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                        <div style="float:left; margin-left:10px; padding:5px;">Natural Gas Consumption</div>                       
                        <div class="clear"></div>
                    </div>
                    
                    <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_3">
                        <div class="benchmark_button_active" style="float:left;">Electric Cost</div>
                        <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                        <div style="float:left; margin-left:10px; padding:5px;">Natural Gas Cost</div>                       
                        <div class="clear"></div>
                    </div>
                    
                     <div style="margin-top:10px; display:none;" id="Graph_Type_Bottom_Options_4">
                        <div class="benchmark_button_active" style="float:left;">Electric Savings</div>
                        <div style="float:left; margin-left:10px; padding:5px;">|</div>           
                        <div style="float:left; margin-left:10px; padding:5px;">Natural Gas Savings</div>                       
                        <div class="clear"></div>
                    </div>
                   
                </div>
            
            </div>
            <div class="clear"></div>
            
            
            
            <div id="Customer_Left_Panel" style="width:93%;">
            	
                
                <div class="Windows_Left" style="position:relative; width:40px;">
                
                	<div style="position:absolute; z-index:3; width:35px; height:159px; top:70px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Summary_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:125px; margin-left:5px; font-size:15px; font-weight:bold; color:#666666;" id="Site_Details_Summary_Text">Temperature</div>
                    </div>
                	<div style="position:absolute; z-index:2; width:35px; height:159px; top:180px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_GHG_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:127px; margin-left:5px; font-size:15px; font-weight:bold; color:#FFFFFF;" id="Site_Details_GHG_Text">Consumption</div>
                    </div>
                    <div style="position:absolute; z-index:1; width:35px; height:159px; top:290px; background-image:url(../images/gray_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Metrics_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:100px; margin-left:5px; font-size:15px; font-weight:bold; color:#666666;" id="Site_Details_Metrics_Text">Costs</div>
                    </div>
                	<div style="position:absolute; z-index:0; width:35px; height:159px; top:400px; background-image:url(../images/blue_button.png); background-repeat:no-repeat; cursor:pointer;" id="Site_Details_Energy_Button">
                    	<div style="transform: rotate(270deg); transform-origin: left top 0; margin-top:110px; margin-left:5px; font-size:15px; font-weight:bold; color:#FFFFFF;" id="Site_Details_Energy_Text">Savings</div>
                    </div>
                    
                </div>
                
                
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px; width:100%;">
                	<div class="Window_Title_Bg" style="width:100%;">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading" id="Large_Graph_Type">TEMPERATURE & HUMIDITY</div>							
                        </div>                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="../images/window_title_divider.png" />
                        </div>
                      	<div style="float:left; margin-left:20px; margin-top:20px; font-size:18px; color:#666666;">
                        	<div id="SubTitle_Temperature">ALL AVAILABLE NODES</div>
                            <div id="SubTitle_Consumption" style="display:none;"> 
                            	<select id="ddlMetricsType" name="ddlMetricsType">
                                    <option value="1">ELECTRIC CONSUMPTION</option>
                                    <option value="2">NATURAL GAS CONSUMPTION</option>
                                </select>
                            </div>
                             <div id="SubTitle_Cost" style="display:none;"> 
                            	<select id="ddlCostType" name="ddlCostType">
                                    <option value="1">ELECTRIC SYSTEM</option>
                                    <option value="2">NATURAL GAS SYSTEM</option>
                                </select>
                            </div>
                            
                             <div id="SubTitle_savings" style="display:none;"> 
                            	<select id="ddlSavingsType" name="ddlSavingsType">
                                    <option value="1">ELECTRIC SAVINGS</option>
                                    <option value="2">NATURAL GAS SAVINGS</option>
                                </select>
                            </div>
                            
                        </div>
                        
                        <div style="float:right; margin-right:20px; margin-top:20px;">
                        	<select name="" id="" style="font-size:16px; font-weight:bold; font-family: UsEnergyEngineers;">
                            	<option value="">Zeeland Mainsite</option>
                            </select>
                        </div>
                        
                        <div class="clear"></div>                        
                    </div>
                    
                    
                    <div class="Window_Container_Bg">                    
                    	<div style="padding:15px 10px 10px 20px; min-height:500px;" id="Large_Graph_Area">        
                        
                        	Loading...
                                                  
                        </div>
                    </div>                    
                </div>
              
                <div class="clear"></div>
                
                
<br>

            
            </div>
            
             
   		
		 <div class="clear"></div>
        
    </div>
  
  </body>
  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

</html>
