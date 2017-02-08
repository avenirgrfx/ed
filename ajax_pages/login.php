<?php
ob_start();
session_start();

require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/login.class.php");
$DB=new DB;
$Login=new Login();

$strSQL="Select count(*) as TotalTry from t_login_try_iplog where email_id='".mysql_escape_string($_POST['EmailID'])."' and ip_address='".$_SERVER['REMOTE_ADDR']."'";
$strRsLoginTryCountArr=$DB->Returns($strSQL);
if($strRsLoginTryCount=mysql_fetch_object($strRsLoginTryCountArr))
{
	if($strRsLoginTryCount->TotalTry==5)
	{
		print "LOCKED";
		exit();
	}
}

$arr=array('email_address'=>$_POST['EmailID'], 'password'=>$_POST['Password']);
$Login->setVal($arr);
if($Login->LoginCheck()=='INVALID')
{	
	$strSQL="Insert into t_login_try_iplog(ip_address, email_id, doc) values('".$_SERVER['REMOTE_ADDR']."','".mysql_escape_string($arr['email_address'])."',now())";
	$DB->Execute($strSQL);
	
	print "<div class='error_message'>The Email Address or Password does not match System Records. Please correct and try again.";
	
	$strSQL="Select count(*) as TotalTry from t_login_try_iplog where email_id='".mysql_escape_string($arr['email_address'])."' and ip_address='".$_SERVER['REMOTE_ADDR']."'";
	$strRsLoginTryCountArr=$DB->Returns($strSQL);
	if($strRsLoginTryCount=mysql_fetch_object($strRsLoginTryCountArr))
	{
		if($strRsLoginTryCount->TotalTry==3)
		{
			print "<br /> You have 2 more login attempts left.";
		}
		elseif($strRsLoginTryCount->TotalTry==4)
		{
			print "<br /> You have one more login attempt left.";
		}
		elseif($strRsLoginTryCount->TotalTry==5)
		{
			print "<br /> SECURITY: You have exceeded the maxium login attempts. <br />Access to energydas.com from this IP has been locked. Please call (800) 380 1120 for access";
		}
	}
	
	print "</div>";
	
}
else
{
    // Remember Me functionality ...
    if($_POST['RememberMe'] == 1) {	// if user check the remember me checkbox		
   		setcookie('remember_me', 'remember_me', time()+60*60*24*30, "/");
   		setcookie('txtEmailID', $_POST['EmailID'], time()+60*60*24*30, "/");
   		setcookie('txtPassword', $_POST['Password'], time()+60*60*24*30, "/");
	}
	else {   // if user not check the remember me checkbox
   		setcookie('remember_me', 'forget_me', time()-60*60*24*30, "/");			
   		setcookie('txtEmailID', '', time()-60*60*24*30, "/");			
   		setcookie('txtPassword', '', time()-60*60*24*30, "/");			
	}
    
	$strSQL="Delete from t_login_try_iplog where email_id='".mysql_escape_string($arr['email_address'])."' and ip_address='".$_SERVER['REMOTE_ADDR']."'";
	$DB->Execute($strSQL);
	print "<div id='Login_Success' class='success_message'>Login was successful. Please wait...</div>";
	
	$_SESSION['user_login']->ADMIN_ACCESS=false;
	$_SESSION['user_login']->ENGINEER_ACCESS=false;
	$_SESSION['user_login']->CUSTOMER_ACCESS=false;
	
	if($_SESSION['user_login']->user_type==0)
	{
		# Master Users
		$strSQL="Select * from t_users where user_id=".$_SESSION['user_login']->user_id;
		$strRsLoginUserDEtailsArr=$DB->Returns($strSQL);
		while($strRsLoginUserDEtails=mysql_fetch_object($strRsLoginUserDEtailsArr))
		{
			$_SESSION['user_login']->user_full_name=$strRsLoginUserDEtails->user_full_name;
			$_SESSION['user_login']->user_position=$strRsLoginUserDEtails->user_position;
		}
		
		$User_Access_Type=$_SESSION['user_login']->USER_ACCESS_TYPE;
		$arrAccessLevels=$_SESSION['user_login']->USER_ACCESS;
		$arrAccessLevels=explode('@~@',$arrAccessLevels);
		if(is_array($arrAccessLevels) && count($arrAccessLevels)>0)
		{
			foreach($arrAccessLevels as $Val)
			{				
				$Allowed_Access_Val=$Val;	
				$Allowed_Access_Val_Check=explode(';',$Allowed_Access_Val);
			
				if($Allowed_Access_Val_Check[1]==1)
				{
					$_SESSION['user_login']->ALLOWED_ACCCESS[]= $Allowed_Access_Val_Check[0];					
				}
				
				if( $Allowed_Access_Val_Check[0]=="FolderView" or $Allowed_Access_Val_Check[0]=="FolderUpload" or $Allowed_Access_Val_Check[0]=="FolderDelete" )
				{
					$_SESSION['user_login']->ALLOWED_ACCCESS[]= $Allowed_Access_Val_Check[0]."~".$Allowed_Access_Val_Check[1];
				}
			}
		}
		
		if($User_Access_Type==1)
		{
			$_SESSION['user_login']->ADMIN_ACCESS=true;
			$_SESSION['user_login']->ENGINEER_ACCESS=true;
			$_SESSION['user_login']->CUSTOMER_ACCESS=true;
		}
		elseif($User_Access_Type==2)
		{
			$_SESSION['user_login']->ADMIN_ACCESS=false;
			$_SESSION['user_login']->ENGINEER_ACCESS=true;
			$_SESSION['user_login']->CUSTOMER_ACCESS=true;
		}
		elseif($User_Access_Type==3)
		{
			$_SESSION['user_login']->ADMIN_ACCESS=false;
			$_SESSION['user_login']->ENGINEER_ACCESS=false;
			$_SESSION['user_login']->CUSTOMER_ACCESS=true;
		}
		
	}
	else
	{
		# Client Users
		$_SESSION['user_login']->CUSTOMER_ACCESS=true;
		
		
		$arrAccessLevels=$_SESSION['customer_user_login']->USER_ACCESS;
		$arrAccessLevels=explode('@~@',$arrAccessLevels);
		if(is_array($arrAccessLevels) && count($arrAccessLevels)>0)
		{
			foreach($arrAccessLevels as $Val)
			{				
				$Allowed_Access_Val=$Val;	
				$Allowed_Access_Val_Check=explode(';',$Allowed_Access_Val);
			
				if($Allowed_Access_Val_Check[1]==1)
				{
					$_SESSION['customer_user_login']->ALLOWED_ACCCESS[]= $Allowed_Access_Val_Check[0];					
				}
				
				if( $Allowed_Access_Val_Check[0]=="FolderView" or $Allowed_Access_Val_Check[0]=="FolderUpload" or $Allowed_Access_Val_Check[0]=="FolderDelete" )
				{
					$_SESSION['customer_user_login']->ALLOWED_ACCCESS[]= $Allowed_Access_Val_Check[0]."~".$Allowed_Access_Val_Check[1];
				}
			}
		}
		
		
	}
}

?>
