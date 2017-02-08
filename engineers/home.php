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

if($_SESSION['user_login']->ENGINEER_ACCESS!=1)
{
	Globals::SendURL(URL.'login.php');
}


?>
<!DOCTYPE html>
<html lang="en" ng-app="kitchensink">
  <head>
    <meta charset="utf-8">

    <title>energyDAS Engineers</title>
  
    <link rel="stylesheet" href="../css/prism.css">
    <link rel="stylesheet" href="../css/bootstrap.css">	
    <link rel="stylesheet" href="../css/master.css">
    <link rel="stylesheet" href="../css/tree.css">
    
    <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
    
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
   	<script type='text/javascript' src="<?php echo URL?>js/prism.js"></script>

	<script type='text/javascript' src="<?php echo URL?>js/jquery.js"></script>  
	<script type='text/javascript' src="<?php echo URL?>js/bootstrap.js"></script>

    <script type='text/javascript' src="<?php echo URL?>js/font_definitions.js"></script>    

    
    <script type="text/javascript">
	
		
		$(document).ready(function(){
			$('#Administrator_Main_Menu').click(function(){
				window.location='<?php echo URL?>';
			});
			
			$('#Engineer_Main_Menu').click(function(){
				window.location='<?php echo URL?>engineers';
			});
			
			$('#Summary_Menu').click(function(){
			
			/* For Messaging */
			user_id=1;		
			message_mode=1;	
			$('#Message_Container').html('Loading...');
			$.get('<?php echo URL?>ajax_pages/messages.php',{user_id:user_id, message_mode:message_mode}, function(data){ $('#Message_Container').html(data); });
			
			
			/* For Client List */
			user_id=1;
			$('#Client_List_Container').html('Loading...');
			$.get('<?php echo URL?>ajax_pages/client_list.php',{user_id:user_id}, function(data){ $('#Client_List_Container').html(data); });
			
			
			/* For Client Files */
			user_id=1;
			$('#Files_Container').html('Loading...');
			$.get('<?php echo URL?>ajax_pages/files.php',{user_id:user_id}, function(data){ $('#Files_Container').html(data); });
			
		});
		
		$('#Summary_Menu').trigger('click');
			
		});	

    </script>   

    
  </head>
  <body>
 

 
 
  <div id="MainContainer" ng-controller="CanvasControls">
  
  <div id="Logo">
  		<a href="<?php echo URL?>"><img src="<?php echo URL?>images/logo.png" border="0" /></a>
  </div>
  
  
  <div>
  	
    <div class="TopMenu TopMenu_active" id="Home_Main_Menu">Home</div>
    
    <?php if($_SESSION['user_login']->ADMIN_ACCESS==1){?>
    	<div class="TopMenu" id="Administrator_Main_Menu">Administrator</div>
    <?php }?>
    <div class="TopMenu" id="Engineer_Main_Menu">Engineer</div>
    
    <div class="GreetingsMenu" style="float:right; margin-left:10px; margin-right:10px;">
    	<?php echo $_SESSION['user_login']->user_full_name;?> - <?php echo $_SESSION['user_login']->user_position;?><br>
		<a href="#">Change Password</a> | <a href="<?php echo URL?>logout.php">Logout</a>
    </div>
    
    <div style="float:right;">
    	<img src="<?php echo URL;?>images/energydas-ticket.png" />
    </div>
    
    <div style="float:right; margin-right:10px;">
    	<img src="<?php echo URL;?>images/energydas_coms.png" />
    </div>
    
    <div class="clear"></div>
  </div>
  
  
   <div id="Menu">
  	<ul>
    	<li id="Summary_Menu" class="LargeMenu showNewProject" style="margin-right:30px;">Summary</li>
        <li id="Profile_Menu" class="LargeMenu showNewProject" style="margin-right:30px;">Profile</li>
    </ul>
     
     <div class="clear"></div>     
  </div>
  
 
 
  
  
  
 <div id="Message_Container">Message Content</div>
   
   <div id="Client_List_Container" style="float:left; width:60%; border:1px solid #CCCCCC; margin-top:10px;">Client List</div>
   <div id="Files_Container" style="float:left; width:35%; border:1px solid #CCCCCC; margin-top:10px; margin-left:2%;">Files</div>
   <div class="clear"></div>
 
  
  
  
  </div>

	


  </body>
</html>
