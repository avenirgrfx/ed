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
		#Customer_Main_Container
		{
			width:1200px;
			margin:0px auto;
			border:1px solid #EFEFEF;
		}
		
		#Customer_Header_Section
		{
			padding:10px 40px 0px 40px
		}
		#Customer_Menu_Section
		{
			margin:10px 50px 0px 40px;
		}
		.GrayBackground
		{
			background-color:#CCCCCC;
			min-height:500px;
			padding-top:5px;
			margin-top:10px;
		}
		#Customer_Left_Panel
		{
			float:left;
			margin-left:10px;	
		}
		#Customer_Right_Panel
		{
			float:left;
			width:500px;
			margin-left:70px;
		}
		h5
		{
			font-size:28px;
			color:#666666;
		}
		
		
		#style-2::-webkit-scrollbar-track
		{
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
			border-radius: 10px;
			background-color: #EFEFEF
		}
		
		#style-2::-webkit-scrollbar
		{
			width: 7px;
			background-color: #617ba7;
		}
		
		#style-2::-webkit-scrollbar-thumb
		{
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
			background-color: #617ba7;
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
    
    
    <script type="text/javascript">
		$(document).ready(function(){
			$('#Gray_Button').click(function(){
				$('#Gray_Button').css('z-index',1);
				$('#Blue_Button').css('z-index',0);
			});
			
			$('#Blue_Button').click(function(){				
				$('#Blue_Button').css('z-index',1);
				$('#Gray_Button').css('z-index',0);
			});
			
			$('#Gray_Button_Text').click(function(){
				$('#Gray_Button').trigger('click');				
			});
			
			$('#Blue_Button_Text').click(function(){				
				$('#Blue_Button').trigger('click');
			});
			
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
            	<div class="TopMenu_Customer">CORPORATE</div>
                <div class="TopMenu_Customer TopMenu_Customer_active">OPERATIONS</div>
                <div class="TopMenu_Customer">BILLING</div>                
                <div class="TopMenu_Customer TopMenu_Customer_active" style="float:right; font-weight:normal; font-size:16px; padding:13px;">&nbsp;&nbsp;<img src="<?php echo URL?>images/support_envelop_icon.png" />&nbsp; |&nbsp;&nbsp; SUPPORT</div>
                <div class="TopMenu_Customer" style="float:right; font-size:16px; padding:13px;">PROJECT MANAGEMENT</div>
                <div class="clear"></div>
            </div>
            
            
            <div id="MenuBar_Gray">
                <ul>
                    <li class="LargeMenu_Customer active">SUMMARY</li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer">GRAPHS</li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer">SYSTEMS</li>
                    <li  style="background:none; border:none;">|</li>
                    <li class="LargeMenu_Customer">CONTROLS</li>
                    
                    
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
                
                
                <div class="Windows_Main" style="margin-left:35px;">
                	<div class="Window_Title_Bg">
                    	                        
                        <div style="float:left; margin-top:10px; margin-left:20px; color:#666666;">
                        	<div class="heading">PORTFOLIO</div>
							Site & Buildings
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
                    
                    	<div style="padding:15px 10px 10px 20px; min-height:300px;">
                        
                            <div style="float:left;">
                                <div style="color:#666666; font-weight:bold; font-size:16px;">ALL SITES & BUILDINGS</div>
                                Choose Site for detailed view
                            </div>
                            <div class="clear"></div>
                            
                            
                            <div style="margin-top:15px; padding-bottom:10px; padding-top:3px; border-top:1px solid #999999; max-height:200px; overflow-y: scroll;" id="style-2">
                            
                            	<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_3">+</span>Node: <a href="javascript:ShowNode_Details(3)" style="text-decoration:underline;">CEN150001A (CEN First)</a></div>                           
                            	<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_4">+</span>Node: <a href="javascript:ShowNode_Details(4)" style="text-decoration:underline;">CEN150002A (Next to The First)</a></div>
								<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_3">+</span>Node: <a href="javascript:ShowNode_Details(3)" style="text-decoration:underline;">CEN150001A (CEN First)</a></div>                           
                            	<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_4">+</span>Node: <a href="javascript:ShowNode_Details(4)" style="text-decoration:underline;">CEN150002A (Next to The First)</a></div>
								<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_3">+</span>Node: <a href="javascript:ShowNode_Details(3)" style="text-decoration:underline;">CEN150001A (CEN First)</a></div>                           
                            	<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_4">+</span>Node: <a href="javascript:ShowNode_Details(4)" style="text-decoration:underline;">CEN150002A (Next to The First)</a></div>
								<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_3">+</span>Node: <a href="javascript:ShowNode_Details(3)" style="text-decoration:underline;">CEN150001A (CEN First)</a></div>                           
                            	<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_4">+</span>Node: <a href="javascript:ShowNode_Details(4)" style="text-decoration:underline;">CEN150002A (Next to The First)</a></div>
								<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_3">+</span>Node: <a href="javascript:ShowNode_Details(3)" style="text-decoration:underline;">CEN150001A (CEN First)</a></div>                           
                            	<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_4">+</span>Node: <a href="javascript:ShowNode_Details(4)" style="text-decoration:underline;">CEN150002A (Next to The First)</a></div>
								<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_3">+</span>Node: <a href="javascript:ShowNode_Details(3)" style="text-decoration:underline;">CEN150001A (CEN First)</a></div>                           
                            	<div><span style="font-weight:bold;" id="Node_Details_Plus_Minus_4">+</span>Node: <a href="javascript:ShowNode_Details(4)" style="text-decoration:underline;">CEN150002A (Next to The First)</a></div>
							
                    		</div>
                        
                        </div>
                   

                    </div>
                    
                </div>
                <div class="Windows_Right"></div>
                <div class="clear"></div>
                
                
<br>
<br>
<br>

            
            </div>
            <div id="Customer_Right_Panel">Right</div>
            <div class="clear"></div>
        </div>
    <br>
<br>
<br>
<br>
<br>

    </div>
  
  </body>
  
  
</html>
