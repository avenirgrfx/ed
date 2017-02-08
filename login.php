<?php
ob_start();
session_start();

require_once("configure.php");
require_once(AbsPath."classes/all.php");
$DB=new DB;

/*print "<pre>";
print_r($_SESSION['user_login']);
print "</pre>";*/

if($_SESSION['user_login']->login_id<>'')
{
	if($_SESSION['user_login']->ADMIN_ACCESS==1)
	{
		Globals::SendURL(URL."home.php");
	}
    elseif($_SESSION['user_login']->ENGINEER_ACCESS==1)
	{
		Globals::SendURL(URL."engineers/");
	}
	elseif($_SESSION['user_login']->CUSTOMER_ACCESS==1)
	{
		Globals::SendURL(URL."customer/home.php");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EnergyDas Login</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
<?php
	$strArrDivCount=2;
	$strShowDiv=rand(1,$strArrDivCount);
	
	
	$ArrReflectionText=array('ELECTRICAL REALTIME TRACKING','ELECTRIC METERING','System CONTROLS',
					'Utility Meter Validation','BUILDING ENERGY MANAGEMENT','REAL-TIME TEMPERATURE & HUMIDITY','ENERGY REPORTING',
					'MULTI-LOCATION ENERGY MANAGEMENT','NATURAL GAS METERING','Utility Billing','DEMAND RESPONSE','PEAK DEMAND CURTAILEMENT',
					'WHAT-IF SCENARIOS','GREENHOUSE GAS','M&V PROTOCOLS','AUTOMATED COMMISSIONING',					
					'LEGACY CONTROL SYSTEM CONSOLIDATION', 'LIGHTING CONTROLS', 'AIR TURNOVER CONTROLS', 'REALTIME ENERGY COST MANAGEMENT', 
					'PREVENTATIVE MAINTENANCE', 'ALARMS', 'OPERATIONS MANAGEMENT', 'CORPORATE PORTFOLIO MANAGEMENT', 
					'SYSTEM DIRECT EMAIL FACILITY', 'HVAC UNIT CONTROLS'					
					);
	shuffle($ArrReflectionText);
	
?>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){

	/* Start Slide Show Script */
	var ReflectionTextCount=<?php echo count($ArrReflectionText);?>;	
	var StartSlide=0;
	var EndSlide=ReflectionTextCount;
	var NextSlide=StartSlide+1;
	
	SlideShow(StartSlide,NextSlide,EndSlide);
	/* End Slide Show Script */
	
	$('#btnLogin').click(function(){
		var EmailID=$('#txtEmailID').val();
		var Password=$('#txtPassword').val();
		var RememberMe = 0;
        if($('#chkRememberMe').is(":checked")){
            RememberMe = 1;
        }
        
		if(EmailID=="")
		{
			alert("Please enter Valid Email ID");
			$('#txtEmailID').focus();
			return;
		}
		if(Password=="")
		{
			alert("Please enter your Password");
			$('#txtPassword').focus();
			return;
		}
		
		$.post('ajax_pages/login.php',{EmailID:EmailID, Password:Password, RememberMe: RememberMe}, 
		function(data){
			
			if(data=='LOCKED')
			{
				window.location='<?php echo URL?>login.php';
			}
			$('#txtEmailID').val('');
			$('#txtPassword').val('');
			$('#LoginMessage').html(data);
			$('#LoginMessage').fadeIn(500, function(){ $('#LoginMessage').fadeOut(10000) });
			
			if( $('#Login_Success').html()!='' )
			{
				window.location='<?php echo URL?>login.php';
			}
			
		});
		
	});
	
});

function SlideShow(StartSlide,NextSlide,EndSlide)
{
	var FadeInTime=1000;
	var FadeOutTime=3000;
	
	if(StartSlide>=EndSlide || NextSlide>=EndSlide )
	{
		StartSlide=0;
		NextSlide=1;
		var FinalSlide=EndSlide-1;
		$('#Reflect_'+FinalSlide).css('display','none');
	}
	
	//console.log(StartSlide + " "+NextSlide+ " "+EndSlide);
	
	$('#Reflect_'+StartSlide).fadeOut(FadeOutTime, 
		function(){
			$('#Reflect_'+NextSlide).fadeIn(FadeInTime, function(){ 
			SlideShow(NextSlide,NextSlide+1,EndSlide); })
	});
	
	
}


</script>

</head>



<body>

<?php

//$strSQL="Select count(*) as TotalTry from t_login_try_iplog where ip_address='".$_SERVER['REMOTE_ADDR']."'";
$strSQL="Select count(*) as TotalTry, email_id from t_login_try_iplog where ip_address='".$_SERVER['REMOTE_ADDR']."' GROUP by email_id Order By TotalTry DESC";
$strRsLoginTryCountArr=$DB->Returns($strSQL);
if($strRsLoginTryCount=mysql_fetch_object($strRsLoginTryCountArr))
{
	if($strRsLoginTryCount->TotalTry==5)
	{
		print "<div style='text-align:center; font-weight:bold; font-size:20px; color:#FF0000; margin-top:100px;'>SECURITY: Access to energydas.com from this IP has been blocked. Please call (800) 380 1120 for access<br /><br /> Your IP Address is: <i>".$_SERVER['REMOTE_ADDR']."</i></div>";
		exit();
	}
}
?>

<div id="Main_Container">
	<div id="Header">
    	<div style="float:left;margin-top: 35px;">
			
               <img src="images/logo.png" border="0"  width="175px" height="40px" />
           
                 </div>
      <div style="float:right; margin-top: 60px; color: #999; font-size: 12px;">energyDAS.com | Privacy Notice</div>
        <div class="clear"></div>
    </div>
    
    <div id="Container">
    	<div id="Bar"><?php echo date("D, M d, Y");?></div>
        
        <div id="LoginMessage" style="display:none;">&nbsp;</div>
        
        <div id="content_area">
        
        	<div id="left_side_box">
        		<div id="login_box">
                	
                	<div style="font-size:16px; color:#666; font-weight:bold; text-align:center; margin-bottom:5px; background-image:url(images/lock_icon_login.png); background-repeat:no-repeat; background-position-x: 190px; margin-right: 25px;">Secure Login</div>
                   
                    
                    <div><input type="text" name="txtEmailID" id="txtEmailID" value="<?php if(isset($_COOKIE['remember_me'])) echo $_COOKIE['txtEmailID']; ?>" placeholder="Email ID" />
                  </div>
                    <div style="margin-top:5px;"><input type="password" name="txtPassword" id="txtPassword" value="<?php if(isset($_COOKIE['remember_me'])) echo $_COOKIE['txtPassword']; ?>" placeholder="Password" />
                  </div>
                    <div style="margin-top:5px;">
                    	<div style="float:left;"><input type="checkbox" value="1" name="chkRememberMe" id="chkRememberMe" <?php if(isset($_COOKIE['remember_me'])) { echo 'checked="checked"'; } else { echo ''; } ?> />
                   	  </div>
                    	<div style="float:left; margin-top:2px; font-size:13px; color:#666666;">Remember me in this Device</div>
                        <div class="clear"></div>
                    </div>
                    <div style="margin-top:10px;">
                    	<div style="width:50px; float:left; color:#FFFFFF; font-weight:bold; padding:5px; border-radius:3px; background-color:#86A5CC; text-align:center; cursor:pointer;" id="btnLogin">Login</div>
                    	<div style="float:left; margin-left:10px; margin-top:5px;"><a href="#">Forgot Password?</a></div>
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div style="color: #21409A; font-weight: bold; font-size: 20px; margin-top:5px;">GET A USER ID</div>
                <div style="  font-size: 16px; text-transform: uppercase; margin-bottom: 10px; border-bottom: 1px dotted #21409A; margin-top: 3px;">Manage energyDAS System</div>
                <div>If you are not already using energyDAS.com to access your account, sign up now. Access to energyDAS offers energy efficiency, energy management and systems control tools for your facility. These easy-to-use tools are consolidated into one - making it an effective system to reduce costs and understand how building systems function together.</div>
            	<div style="background-color:#86A5CC; color:#FFFFFF; border-radius:5px; font-size:18px; font-weight:bold; text-transform:uppercase; padding:5px; width: 100px; text-align: center; margin-top: 10px;">Sign Up</div>
            </div>
            
            <div id="right_side_box">
            	<div style="float:left;"><img src="images/energydas.png"  /></div>
              <div style="float:left; font-size:20px; margin-top:17px; font-weight:bold; color:#666666; font-family:UsEnergyEngineers;">energy systems</div>
                <div class="clear"></div>
                
                
                
                <?php if($strShowDiv==1){?>
                <div>
                  <div style="float:left; width:150px; margin-top:10px;">
                    <div style="font-size:18px; font-weight:bold; margin-bottom:10px; color:#666666;">SUPPORT CENTER</div>
                        <div><img src="images/energydas-ticket.png" /></div>
                    </div>
                    <div style="float:left; margin-left:10px; width:340px; text-align:left; margin-top:10px;">
                            In order to streamline support requests and better serve you, we 
                        utilize a support ticket system. Every support request is assigned 
                        a unique ticket number which you can use to track the progress 
                        and responses online. For your reference we provide complete 
                        archives and history of all your support requests.                                      
                  </div>
                     <div class="clear"></div>
                 </div>
                 <?php }elseif($strShowDiv==2){?>
                 
                 <div>
                    <div style="float:left; width:150px; margin-top:10px;">
                        <div style="font-size:18px; font-weight:bold; margin-bottom:10px; color:#666666; text-align:center;">BIG DATA ANALYTICS</div>
                        <div><img src="images/analytics_logo.png" /></div>
                    </div>
                    <div style="float:left; margin-left:10px; width:340px; text-align:left; margin-top:10px;">
                            Under the hood, energyDAS uses the Big Data Analytics to uncover
                            hidden energy usage patterns, correlations and other energy saving 
                            opportunities. Information collected by energyDAS devices in a building
                            is contastantly analyzed using statistical analysis, predictive modeling, 
                            algorithmic optimization, text mining and forecasting. This Proactive Big 
                            Data Analytics processing is constantly happening 24hrs per day - all
                            intended to optimize building energy consumption and reduce costs.                                      
                   </div>
                     <div class="clear"></div>
                 </div>
                 <?php }?>
                 
                 
                 
                 <div style="margin:70px auto 0px auto; width:520px; text-align:center;">
                 	<?php 

					if(is_array($ArrReflectionText) && count($ArrReflectionText)>0)
					{
						foreach($ArrReflectionText as $key=>$TextVal)
						{
							$strDisplay=true;
							if($key>0)
							{
								$strDisplay=false;
							}
					?>
                 			<div id="Reflect_<?php echo $key;?>" class="TextReflection" <?php if($strDisplay==false){?>style="display:none;"<?php }?>><?php echo $TextVal;?></div>       
                    <?php
						} 
					}
					?>            
                    <div class="clear"></div>
                 </div>
                 
            
          </div>
            
            <div class="clear"></div>
            
        </div>
    </div>
    
    <div id="Footer_Link"><a href="#">Security</a> | <a href="#">Terms of Use</a></div>
    <div style="width: 400px;  text-align: center;  margin: 5px auto;  font-size: 14px; color:#333333;">&copy;2015-2017 energyDAS Inc.</div>
    
    
</div>

</body>
</html>
