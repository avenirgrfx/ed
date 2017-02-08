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
		
		#Product_Subscription_Header
		{
			height:50px;
			color:#CCCCCC;
			font-size:12px;
		}
		#Product_Subscription_Filter
		{
			padding:5px 10px;
			/*background-color:#DEDEDE;*/
		}
		
		#Product_Subscription_List
		{
			font-weight:bold;
			text-decoration:underline;
		}
		
		#Product_Subscription_List div
		{
			padding:5px;
			/*border-left:1px solid #CCCCCC;
			border-top:1px solid #CCCCCC;
			border-bottom:1px solid #CCCCCC;*/
		}
		
		.Product_Subscription_ItemList div
		{
			padding:5px;
			/*border-left:1px solid #CCCCCC;
			border-bottom:1px solid #CCCCCC;*/
		}
		
		
		.Renew
		{
		  background: url(<?php echo URL?>images/product_subscription_sprite.gif);
		  background-position: -72px -99px;
		  width: 36px;
		  height: 25px;
		  background-repeat: no-repeat;
		  margin-left:56px;
		}
		
		.AutoRenewOn
		{
			background: url(<?php echo URL?>images/product_subscription_sprite.gif);
			background-position: -36px -29px;
			width: 36px;
			height: 25px;
			background-repeat: no-repeat;
			margin-left:56px;
		}
		
		.AutoRenewOf
		{
			background: url(<?php echo URL?>images/product_subscription_sprite.gif);
			background-position: -144px -99px;
			width: 36px;
			height: 25px;
			background-repeat: no-repeat;
			margin-left:56px;
		}
		
		.UpdatePaymentMethod
		{
			background: url(<?php echo URL?>images/product_subscription_sprite.gif);
			background-position: -108px -29px;
			width: 36px;
			height: 25px;
			background-repeat: no-repeat;
			margin-left:56px;
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
		 	
			$.get('<?php echo URL?>ajax_pages/customers/customer_user_access.php',{},function(data){
				$('#User_Template_Container').html(data);
			});
			
			$.get('<?php echo URL?>ajax_pages/customers/users.php',{},function(data){
				$('#User_Access_Container').html(data);
			});
			
			$.get('<?php echo URL?>ajax_pages/customers/users_list.php',{},function(data){
				$('#Users_Container').html(data);
			});
				
		});
		
	
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
                <span style="font-size:24px;"><?php echo $client_type; ?></span>
            </div>
            <div style="float:right; text-align:right; font-size:18px; margin-top:17px; font-weight: bold; color: #CCCCCC;">
            	energyDAS<br>
                <?php echo date("g:i a F dS, Y");?>
                
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="GrayBackground">
    		
      		<?php require_once("menu.php");?>      
      
            
            <div id="Customer_Left_Panel" style="margin-left:10px;">
            	
                
                
                
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                	<div class="Window_Title_Bg">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading">USERS</div>
							
                        </div>
                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="../images/window_title_divider.png" />
                        </div>
                        
                        
                        <div class="clear"></div>
                        
                    </div>
                    <div class="Window_Container_Bg" style="min-height:300px; padding:5px;" id="Users_Container">
                    
                    	Loading...

                    </div>
                    
                </div>
              	
                <div class="Windows_Main" style="margin-left:15px; border:1px solid #999999; border-radius:10px;">
                	<div class="Window_Title_Bg" style="width:600px;">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading">USER ACCESS</div>							
                        </div>                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="../images/window_title_divider.png" />
                        </div>
                        <div class="clear"></div>
                        
                    </div>
                    <div class="Window_Container_Bg" style="min-height:325px; padding:10px;" id="User_Access_Container">
                    
                    	Loading...

                    </div>
                    
                </div>
                
                <div class="clear" style="margin-bottom:20px;"></div>
                
                 
               
              	
                <div class="Windows_Main" style="margin-left:35px; border:1px solid #999999; border-radius:10px;">
                	<div class="Window_Title_Bg" style="width:1118px;">
                    	                        
                        <div style="float:left; margin-top:20px; margin-left:20px; color:#666666;">
                        	<div class="heading">USER TEMPLATE</div>							
                        </div>
                        
                        <div style="float:left; margin-left:20px;">
                        	<img src="../images/window_title_divider.png" />
                        </div>                        
                        
                        <div class="clear"></div>
                        
                    </div>
                    <div class="Window_Container_Bg" style="min-height:300px;">
						<div id="User_Template_Container">
                        	Loading...
                        </div>
                    </div>
                    
                </div>
                
                <div class="clear"></div>
            
            </div>
            

            
            
            <div class="clear"></div>
            
            <br><br>
            
            
            
              
            
            
             
   		
		 <div class="clear"></div>
         
        
    </div>
  
  	</div>
 
    
  </body>
  
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

</html>
