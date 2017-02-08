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
		 	
			$('#Top_Menu_Home').click(function(){
				window.location='<?php echo URL?>customer/home.php';
			});
			
			$('#Top_Menu_Operations').click(function(){
				window.location='<?php echo URL?>customer/';
			});
			
			$('#Files_Folder_View').html('Loading...');
			$.get('<?php echo URL?>ajax_pages/files.php',{client_id:<?php echo $strClientID;?>, show_client:1},function(data){
				$('#Files_Folder_View').html(data);
			});
				
		});
	</script>
  

  </head>
  
  <body>
  
  
  
  	<div id="Customer_Main_Container">
    	<div id="Customer_Header_Section">
        	<div style="float:left; border-right:1px solid #333333; padding-right:10px;margin-top:37px;">
            	<?php echo Globals::Resize('../uploads/customer/'.$client_logo, 150, 70);?>
            </div>
            <div style="float:left; margin-left:50px;margin-top:32px;">
            	<h5 style="text-transform:uppercase;"><?php echo $client_name; ?></h5>
                <span style="font-size:24px;"><?php echo $software_version?> - <?php echo $client_type; ?></span>
            </div>
            <div style="float:right; text-align:right; font-size:18px; margin-top:17px; font-weight: bold; color: #CCCCCC;">
            	 <div id="Logo">
                <a href="<?php echo URL ?>"><img src="<?php echo URL ?>images/logo.png" border="0" width="185px" height="70px" /></a>
            </div>
                <?php echo date("g:i a F dS, Y");?>
                
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="GrayBackground">
    		
            <?php require_once("menu.php");?>
            
           
          	
            <div id="Customer_Left_Panel" style="margin:20px auto; width:80%; float:none; ">
                <div class="Windows_Main" style="border:1px solid #999999; border-radius:10px; float:none; margin-bottom:20px;">
                	<div class="Window_Title_Bg" style="width:100%; height:50px; padding-top:20px;">
                        <div class="heading" style="margin-left:20px;">FILE MANAGER</div>
                        <div class="clear"></div>                        
                    </div>
                    
                    <div class="Window_Container_Bg" style="padding:20px;" id="Files_Folder_View">
                        Loading...
                    </div>
                    
                </div>
              
                <div class="clear"></div>
            </div>
         	
             <div class="clear" style="height:20px;"></div>

        
    </div>
  		
         <div class="clear"></div>
        
  	</div>
 
    
  </body>
  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

</html>
