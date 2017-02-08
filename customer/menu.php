<?php
$strPageName= str_replace(AbsPath."customer/","",$_SERVER['SCRIPT_FILENAME']);

if($_SESSION['user_login']->ENGINEER_ACCESS==1)
{
	if($_GET['login_id']<>'')
	{
		$strClient_ID=$_GET['login_id'];
		$_SESSION['client_details']->client_id=$strClient_ID;
		$_SESSION['client_details']->overrided_login=true;
		Globals::SendURL(URL."customer/");
	}
}

/*print "<pre>";
print_r($_SESSION['client_details']);
print "</pre>";

print "<pre>";
print_r($_SESSION['customer_user_login']);
print "</pre>";*/

$Sites_Corporate_Allow=false;
$Sites_Operations_Allow=false;
$Sites_Billing_Allow=false;
$Sites_Programs_Allow=false;
$Sites_Files_Allow=false;

if( in_array('Sites_Corporate',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Sites_Corporate_Allow=true;
}

if( in_array('Sites_Operations',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Sites_Operations_Allow=true;
}

if( in_array('Sites_Billing',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Sites_Billing_Allow=true;
}

if( in_array('Sites_Programs',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Sites_Programs_Allow=true;
}

if( in_array('Sites_Files',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Sites_Files_Allow=true;
}

# Second Level Menu (Operations)
$Operations_Summary_Allow=false;
$Operations_Graphs_Allow=false;
$Operations_Systems_Allow=false;
$Operations_Controls_Allow=false;
$Operations_Projects_Allow=false;
$Operations_Reports_Allow=false;
if( in_array('Operations_Summary',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Operations_Summary_Allow=true;
}

if( in_array('Operations_Graphs',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Operations_Graphs_Allow=true;
}

if( in_array('Operations_Systems',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Operations_Systems_Allow=true;
}

if( in_array('Operations_Controls',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Operations_Controls_Allow=true;
}

if( in_array('Operations_Projects',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Operations_Projects_Allow=true;
}

if( in_array('Operations_Reports',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Operations_Reports_Allow=true;
}

# Second Level Menu (Home)
$HomeUser_Template_Allow=false;
$Home_Users_Allow=true;
if( in_array('HomeUser_Template',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$HomeUser_Template_Allow=true;
}

if( in_array('Home_Users',$_SESSION['customer_user_login']->ALLOWED_ACCCESS) or $_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$Home_Users_Allow=true;
}

?>


<script type="text/javascript">
$(document).ready(function(){

	$('#Top_Menu_Home').click(function(){
		window.location='<?php echo URL?>customer/home.php';
	});
	
	$('#Top_Menu_Operations').click(function(){
		window.location='<?php echo URL?>customer/';
	});
	
	$('#Top_Menu_Files').click(function(){
		window.location='<?php echo URL?>customer/file.php';
	});
    
    $('#Top_Menu_Billing').click(function(){
		window.location='<?php echo URL?>customer/billing.php';
	});

});
</script>
<div id="Customer_Menu_Section">
    <div class="TopMenu_Customer <?php if($strPageName=="home.php"){?>TopMenu_Customer_active<?php }?>" id="Top_Menu_Home">HOME</div>
    <?php if($Sites_Corporate_Allow==true){?>
        <div class="TopMenu_Customer">CORPORATE</div>
    <?php }?>
    <?php if($Sites_Operations_Allow==true){?>
        <div class="TopMenu_Customer <?php if($strPageName=="index.php" or $strPageName=="graph.php" or $strPageName=="systems.php" or $strPageName=="project.php" or $strPageName=="controls.php"){?>TopMenu_Customer_active<?php }?>" id="Top_Menu_Operations">OPERATIONS</div>
    <?php }?>
    <?php if($Sites_Billing_Allow==true){?>
        <div class="TopMenu_Customer <?php if($strPageName=="billing.php" || $strPageName=="billing_electricity.php" || $strPageName=="billing_naturalgas.php" || $strPageName=="billing_water.php"){?>TopMenu_Customer_active<?php }?>" id="Top_Menu_Billing">BILLING</div>
    <?php }?>
    <?php if($Sites_Programs_Allow==true){?>
        <div class="TopMenu_Customer">PROGRAMS</div>
    <?php }?>
    <div class="TopMenu_Customer TopMenu_Customer_active" style="float:right; font-weight:normal; font-size:16px; padding:13px;">&nbsp;&nbsp;<img src="<?php echo URL?>images/support_envelop_icon.png" />&nbsp; |&nbsp;&nbsp; SUPPORT</div>
    
        <div class="TopMenu_Customer " style="float:right; font-size:16px; padding:13px;" id="Top_Menu_Wifi">WIFI</div>
    <?php if($Sites_Files_Allow==true){?>
        <div class="TopMenu_Customer <?php if($strPageName=="file.php"){?>TopMenu_Customer_active<?php }?>" style="float:right; font-size:16px; padding:13px;" id="Top_Menu_Files">FILES</div>
    <?php }?>
    <div class="clear"></div>
</div>



<div id="MenuBar_Gray">
    <ul>
    	
        
        <?php if($strPageName=="index.php" or $strPageName=="graph.php" or $strPageName=="systems.php" or $strPageName=="project.php" or $strPageName=="controls.php" ){?>
        
			<?php if($Operations_Summary_Allow==true){?>
                <li class="LargeMenu_Customer <?php if($strPageName=="index.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/">SUMMARY</a></li>
                <li  style="background:none; border:none;">|</li>
            <?php }?>
            <?php if($Operations_Graphs_Allow==true){?>
                <li class="LargeMenu_Customer <?php if($strPageName=="graph.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/graph.php">GRAPHS</a></li>
                <li  style="background:none; border:none;">|</li>
            <?php }?>
            <?php if($Operations_Systems_Allow==true){?>
                <li class="LargeMenu_Customer <?php if($strPageName=="systems.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/systems.php">SYSTEMS</a></li>
                <!--<li class="LargeMenu_Customer <?php if($strPageName=="systems.php"){?>active<?php }?>">SYSTEMS</li>-->
                <li  style="background:none; border:none;">|</li>
            <?php }?>
            <?php /*if($Operations_Controls_Allow==true){?>
                <li class="LargeMenu_Customer <?php if($strPageName=="controls.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/controls.php">CONTROLS</a></li>
                <li  style="background:none; border:none;">|</li>
            <?php }*/ ?>
            <?php if($Operations_Projects_Allow==true){?>
                <li class="LargeMenu_Customer <?php if($strPageName=="project.php"){?>active<?php }?>" > <a href="<?php echo URL; ?>customer/project.php" >PROJECTS</a></li>
                <li  style="background:none; border:none;">|</li>
            <?php }?>
            <?php if($Operations_Reports_Allow==true){?>
                <li class="LargeMenu_Customer">REPORTS</li>
            <?php }?>
        
        <?php }elseif($strPageName=="billing.php" or $strPageName=="billing_electricity.php" or $strPageName=="billing_naturalgas.php"  or $strPageName=="billing_water.php"){?>
        	<li class="LargeMenu_Customer <?php if($strPageName=="billing.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/billing.php">SUMMARY</a></li>
        	<li  style="background:none; border:none;">|</li>
            <li class="LargeMenu_Customer <?php if($strPageName=="billing_electricity.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/billing_electricity.php">ELECTRICITY</a></li>
        	<li  style="background:none; border:none;">|</li>
            <li class="LargeMenu_Customer <?php if($strPageName=="billing_naturalgas.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/billing_naturalgas.php">NATURAL GAS</a></li>
            
        	<li  style="background:none; border:none;">|</li>
            <li class="LargeMenu_Customer <?php if($strPageName=="billing_water.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/billing_water.php">WATER</li>
<!--        	<li  style="background:none; border:none;">|</li>
            <li class="LargeMenu_Customer">OTHER</li>-->
        
            
        <?php }elseif($strPageName=="home.php" or $strPageName=="user_access.php" ){?>
        		<li class="LargeMenu_Customer <?php if($strPageName=="home.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/home.php">SUMMARY</a></li>
                <li  style="background:none; border:none;">|</li>
<!--                <li class="LargeMenu_Customer"><a href="<?php echo URL?>customer/home.php#">PORTFOLIO</a></li>
                <li  style="background:none; border:none;">|</li>-->
                
                <?php if($HomeUser_Template_Allow==true or $HomeUser_Template_Allow==true){?>
                	
                    <li class="LargeMenu_Customer <?php if($strPageName=="user_access.php"){?>active<?php }?>"><a href="<?php echo URL?>customer/user_access.php">USER ACCESS</a></li>
                    <li  style="background:none; border:none;">|</li>  
                <?php }?>               
                <li class="LargeMenu_Customer">REPORTS</li>
        <?php }?>
        
        
        
        
        <li style="background:none; border:none; font-size:16px; float:right; cursor:default;">
            <div style="float:left; margin-top:3px;"><img src="<?php echo URL?>images/person_icon.png" /></div>
            <div style="margin-top:6px; margin-left:10px; float:left; <?php if(strlen($_SESSION['client_details']->contact_name)>20){?>font-size:13px;<?php  }?>">
                
                <?php if($_SESSION['user_login']->user_type==0){?>
                    <?php echo $_SESSION['user_login']->user_full_name;?> - <span style="font-size:12px; text-transform:none;">&nbsp;&nbsp;(<?php echo $_SESSION['user_login']->user_position;?>)</span>
                    <a href="" onClick="window.close();"><img src="<?php echo URL?>images/close_icon.png" alt="Close" title="Close" style="margin-top:-3px;" /></a>
                <?php }else{?>                
                    <?php echo $_SESSION['client_details']->contact_name;?><span style="font-size:12px; text-transform:none;">&nbsp;&nbsp;(<?php if($_SESSION['client_details']->customer_user_access_id==0) {echo 'Administrator';} else {echo $_SESSION['client_details']->contact_title; }?>)</span>                            
                    <a href="<?php echo URL?>logout.php"><img src="<?php echo URL?>images/logout_icon.png" alt="Logout" title="Logout" style="margin-top:-3px;" /></a>
                <?php }?>
                
            </div>
            <div class="clear"></div>
        </li>
       
     </ul>     
    <div class="clear"></div>     
</div>
<div class="clear"></div>
<br>