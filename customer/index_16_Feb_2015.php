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

    <title>energyDAS Engineers</title>
  
    <link rel="stylesheet" href="../css/prism.css">
    <link rel="stylesheet" href="../css/bootstrap.css">	
    <link rel="stylesheet" href="../css/master.css">
    <link rel="stylesheet" href="../css/tree.css">
    
    <link href='http://fonts.googleapis.com/css?family=Plaster' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Engagement' rel='stylesheet' type='text/css'>
    
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
    
    
  </head>
  <body>
 

 
 
  <div id="MainContainer" ng-controller="CanvasControls">
  
  <div id="Logo">
  		<a href="<?php echo URL?>"><img src="<?php echo URL?>images/logo.png" border="0" /></a>
  </div>
  
  
  <div>
  	<div class="TopMenu" id="Administrator_Main_Menu">Administrator</div>
    <div class="TopMenu TopMenu_active">Engineer</div>
    <div class="TopMenu">Customer</div>
    
    <div class="GreetingsMenu" style="float:right; margin-left:10px; margin-right:10px;">
    	Felix Goto - Administrator<br>
		<a href="#">Change Password</a> | <a href="#">Logout</a>
    </div>
    
    <div style="float:right;">
    	<img src="<?php echo URL;?>images/energydas-ticket.png" />
    </div>    
    <div class="clear"></div>
  </div>
  
  
  <div id="Menu">
  	<ul>
    	<li id="Projects_Main_Menu" class="LargeMenu" style="margin-right:30px;">Projects</li>      	
        <li id="Design_Menu" style="margin-right:30px;" class="LargeMenu active">Design</li>
        <li id="Controls_Main_Menu" style="margin-right:30px;" class="LargeMenu">Controls</li>
        <li id="Application_Main_Menu" style="margin-right:30px; float:right;" class="LargeMenu">Application</li>
     </ul>
     
     <div class="clear"></div>
     
  </div>
  
  <div id="Menu" style="border-top:1px solid #EFEFEF;">
  		<ul class="System_Menu">
        	<li id="showSystemNodes">System Management</li>
            <li id="showTree">Node Management</li>            
    		<li id="showNewControl" class="active">Control Workspace</li>
        </ul>
        
       
        <ul class="Project_Sub_Menu" style="display:none;">
        	<li id="showProjectSetup">Project Setup</li>
        </ul>
        
        <div class="clear"></div>
  </div>
  
  
  
 
  


  
 

<div id="bd-wrapper">    
  	<div class="clear"></div>
  	<br><br>
	<div style="position:relative; width:800px; height:500px; margin-bottom:50px; float:left;" id="canvas-wrapper"></div>
	<div class="clear"></div>
</div>

  
  
  
  </div>

<script>

function ShowCustomerWorkspace()
{
	$('#RefreshWorkspace').css('display','block');
	$.get("<?php echo URL?>ajax_pages/customer_project_view.php",
	{
		id:1,
	},
	function(data,status){
		$('#canvas-wrapper').html(data);
		$('#canvas-wrapper').focus();
		$('#RefreshWorkspace').css('display','none');
	});
}

ShowCustomerWorkspace();

setInterval(ShowCustomerWorkspace, 30000);

</script>	


  </body>
</html>
