<?php
ob_start();
session_start();
require_once('configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');

if($_SESSION['user_login']->login_id=="")
{
	Globals::SendURL(URL.'login.php');
}

if($_SESSION['user_login']->ADMIN_ACCESS!=1)
{
	Globals::SendURL(URL.'login.php');
}

$DB=new DB;

/*print "<pre>";
print_r($_SESSION);
print "</pre>";*/

?>
<!DOCTYPE html>
<html lang="en" ng-app="kitchensink">
  <head>
    <meta charset="utf-8">

    <title>energyDAS Administrator</title>
  
    <link rel="stylesheet" href="css/prism.css">
    <link rel="stylesheet" href="css/bootstrap.css">	
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/tree.css">
    <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
   	<script type='text/javascript' src="js/jquery.js"></script>
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    
   
<script type="text/javascript">
	$(document).ready(function(){
		$('#Administrator_Main_Menu').click(function(){ window.location.href='<?php echo URL?>'; });
		$('#Engineer_Main_Menu').click(function(){ window.location.href='<?php echo URL?>engineers/'; });
		$('#Controls_Main_Menu').click(function(){ window.location.href='<?php echo URL?>controls/'; });
		$('#Summary_Menu').click(function(){
			
			/* For Messaging */
			$('#Summary_Menu').addClass('active');
			$('#CRM_Menu').removeClass('active');
			$('#Client_List_Container').slideUp('slow');
			$('#Files_Container').slideUp('slow');
			user_id=1;		
			message_mode=1;	
			$('#Message_Container').html('Loading...');
			$.get('ajax_pages/messages.php',{user_id:user_id, message_mode:message_mode}, function(data){ $('#Message_Container').html(data); });
			$('#Message_Container').slideDown('slow');
			
		});
		
		$('#CRM_Menu').click(function(){
			
			$('#CRM_Menu').addClass('active');
			$('#Summary_Menu').removeClass('active');
			$('#Message_Container').slideUp('slow');
			
			/* For Client List */
			user_id=1;
			$('#Client_List_Container').html('Loading...');
			$.get('ajax_pages/client_list.php',{user_id:user_id}, function(data){ $('#Client_List_Container').html(data); });
			$('#Client_List_Container').slideDown('slow');
			
			/* For Client Files */
			user_id=1;
			$('#Files_Container').html('Loading...');
			$.get('ajax_pages/files.php',{user_id:user_id}, function(data){ $('#Files_Container').html(data); });
			$('#Files_Container').slideDown('slow');
			
		});
		
		$('#Summary_Menu').trigger('click');
		
	});
</script> 
    
    
  </head>
  <body>
 
  <div id="MainContainer">
       <div id="Logo">
                <a href="<?php echo URL ?>"><img src="images/logo.png" border="0"  width="185px" height="70px" /></a>
       </div>
  <div>
       <div class="TopMenu TopMenu_active" id="Home_Main_Menu">Home</div>
       <div class="TopMenu" id="Administrator_Main_Menu">Administrator</div>
       <div class="TopMenu" id="Engineer_Main_Menu">Engineer</div>
       <div class="TopMenu" id="Controls_Main_Menu">Controls</div>
       <!--<div class="TopMenu" id="Customer_Main_Menu">USER</div>-->
       <div class="GreetingsMenu" style="float:right; margin-left:1%; margin-right:1%;">
    	    <?php echo $_SESSION['user_login']->user_full_name;?> - <?php echo $_SESSION['user_login']->user_position;?><br>
		    <a href="#">Change Password</a> | <a href="<?php echo URL?>logout.php">Logout</a>
       </div>
       <div style="float:right;text-align:right;width:13%;position:relative;top:30%;">
         	<img style="width:74%;" src="images/energydas-ticket.png" />
       </div>
       <div style="float:right; text-align:right;width:13%;position:relative;top:28%;right:-3%;">
    	    <img style="width:75%;" src="<?php echo URL;?>images/energydas_coms.png" />
       </div>
       <div class="clear"></div>
  </div>
  
  
  <div id="Menu">
  	<ul>
    	<li id="Summary_Menu" class="LargeMenu showNewProject" style="margin-right:30px;">Summary</li>
        <li id="CRM_Menu" class="LargeMenu showNewProject" style="margin-right:30px;">CRM</li>
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
