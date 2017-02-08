<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");

class Login
{
	public $strQuery, $login_id, $user_id, $email_address, $password, $user_type, $delete_flag;
	
	public function Login()
	{
		$this->strQuery="";
		$this->login_id=0;
		$this->user_id="";
		$this->email_address="";
		$this->password="";
		$this->user_type=0;
		$this->delete_flag=0;
	}
	
	
	public function setVal($args)
	{
		$this->login_id=mysql_escape_string($args['login_id']);
		$this->user_id=mysql_escape_string($args['user_id']);
		$this->email_address=mysql_escape_string($args['email_address']);
		$this->password=mysql_escape_string($args['password']);
		$this->user_type=mysql_escape_string($args['user_type']);
		$this->delete_flag=mysql_escape_string($args['delete_flag']);		
	}
	
	public function LoginCheck()
	{
		$DB=new DB;		
		$this->strQuery="Select * from t_login where (email_address='".$this->email_address."' And password='".md5($this->password)."' and delete_flag=0)";
		if($rs=mysql_fetch_object($DB->Returns($this->strQuery)))
		{
			$_SESSION['user_login']=$rs;
			
			if($rs->user_type==1)
			{
				# Customer Login
				$this->strQuery="Select * from t_client where client_id=".$rs->user_id;
				$rsClientDetailsArr=$DB->Returns($this->strQuery);
				if($rsClientDetails=mysql_fetch_object($rsClientDetailsArr))
				{
					if($rsClientDetails->customer_user_access_id<>0)
					{
						$this->strQuery="Select * from  t_customer_user_access where user_access_id=".$rsClientDetails->customer_user_access_id;	
						$strRsUserAccessDetailsArr=$DB->Returns($this->strQuery);
						if($strRsUserAccessDetails=mysql_fetch_object($strRsUserAccessDetailsArr))
						{
							$_SESSION['customer_user_login']->USER_ACCESS= $strRsUserAccessDetails->user_access;
							$_SESSION['customer_user_login']->USER_ACCESS_TYPE=$strRsUserAccessDetails->user_access_type;
							$_SESSION['client_details']=$rsClientDetails;
							$_SESSION['client_details']->client_id=$strRsUserAccessDetails->client_id;
						}
					}
					else
					{
						$_SESSION['client_details']=$rsClientDetails;
					}
				}
				else
				{
					$_SESSION['client_details']=array();
				}
			}
			else
			{
				$this->strQuery="Select * from  t_user_access where user_access_id=".$rs->user_access_id;	
				$strRsUserAccessDetailsArr=$DB->Returns($this->strQuery);
				if($strRsUserAccessDetails=mysql_fetch_object($strRsUserAccessDetailsArr))
				{
					$_SESSION['user_login']->USER_ACCESS= $strRsUserAccessDetails->user_access;
					$_SESSION['user_login']->USER_ACCESS_TYPE=$strRsUserAccessDetails->user_access_type;
				}
			}
			
			return 'SUCCESS';
		}
		else
		{
			$_SESSION['user_login']=array();
			return "INVALID";
		}
	}		
	
}

?>