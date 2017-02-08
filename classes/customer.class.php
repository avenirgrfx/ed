<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");

class Client
{
	public $strQuery, $client_id, $client_type, $software_version_id, $distributor_id, $client_name, $email_address, $password, $address_line_1, $address_line_2, 
	$city, $state, $zip, $country, $doc, $dom, $created_by, $modified_by, $delete_flag;
	
	public $phone, $website, $contact_name, $contact_title, $contact_email, $manager_name, $manager_email, $manager_phone, $logo;
	
	public function Client()
	{
		$this->client_id=0;
		$this->client_type=0;
		$this->software_version_id=0;
		$this->distributor_id=0;	
		$this->client_name='';
		/*$this->contact_name='';*/
		$this->email_address='';
		$this->password='';
		$this->address_line_1='';
		$this->address_line_2='';		 
		$this->city='';
		$this->state='';
		$this->zip='';
		$this->country='';
		$this->doc=date("Y-m-d");
		$this->dom=date("Y-m-d");
		$this->created_by=0;
		$this->modified_by=0;
		$this->delete_flag=0;
		$this->phone='';
		$this->website='';
		$this->contact_name='';
		$this->contact_title='';
		$this->contact_email='';
		$this->manager_name='';
		$this->manager_email='';
		$this->manager_phone='';
		$this->logo='';
		
		$this->strQuery='';
	}
	
	public function setVal($args)
	{
		$this->client_id=mysql_escape_string($args['client_id']);
		$this->client_type=mysql_escape_string($args['client_type']);
		$this->software_version_id=mysql_escape_string($args['software_version_id']);				
		$this->distributor_id=mysql_escape_string($args['distributor_id']);
		$this->client_name=mysql_escape_string($args['client_name']);
		/*$this->contact_name=mysql_escape_string($args['contact_name']);*/
		$this->email_address=mysql_escape_string($args['email_address']);
		$this->password=mysql_escape_string($args['password']);		
		$this->address_line_1=mysql_escape_string($args['address_line_1']);
		$this->address_line_2=mysql_escape_string($args['address_line_2']);		
		$this->city=mysql_escape_string($args['city']);
		$this->state=mysql_escape_string($args['state']);
		$this->zip=mysql_escape_string($args['zip']);		
		$this->country=mysql_escape_string($args['country']);
		$this->doc=mysql_escape_string($args['doc']);
		$this->dom=mysql_escape_string($args['dom']);		
		$this->created_by=mysql_escape_string($args['created_by']);
		$this->modified_by=mysql_escape_string($args['modified_by']);
		$this->delete_flag=mysql_escape_string($args['delete_flag']);
		
		$this->phone=mysql_escape_string($args['phone']);
		$this->website=mysql_escape_string($args['website']);
		$this->contact_name=mysql_escape_string($args['contact_name']);
		$this->contact_title=mysql_escape_string($args['contact_title']);
		$this->contact_email=mysql_escape_string($args['contact_email']);
		$this->manager_name=mysql_escape_string($args['manager_name']);
		$this->manager_email=mysql_escape_string($args['manager_email']);
		$this->manager_phone=mysql_escape_string($args['manager_phone']);
		$this->logo=mysql_escape_string($args['logo']);
		
	}
	
	public function Insert()
	{
		$DB = new DB;
		$this->strQuery="Insert into t_client (client_type, software_version_id, distributor_id, client_name, email_address, address_line_1, address_line_2, 
		city, state, zip, country, doc, dom, created_by, modified_by, delete_flag,
		phone, website, contact_name, contact_title, contact_email, manager_name, manager_email, manager_phone, logo)
		Values(".$this->client_type.",".$this->software_version_id.",".$this->distributor_id.", '".$this->client_name."', '".$this->email_address."', '".$this->address_line_1."', '".$this->address_line_2."', 
		'".$this->city."', '".$this->state."', '".$this->zip."', '".$this->country."', now(), now(), ".$this->created_by.",". $this->modified_by.", 0,
		'".$this->phone."', '".$this->website."', '".$this->contact_name."', '".$this->contact_title."', '".$this->contact_email."', '".$this->manager_name."', '".$this->manager_email."', '".$this->manager_phone."','".$this->logo."')";
		
		$this->client_id=$DB->Execute($this->strQuery);
		
		$this->strQuery="Insert into t_login (user_id, email_address, password, user_type, delete_flag)
		Values(".$this->client_id.",'".$this->email_address."','".$this->password."', 1, 0)";
		$DB->Execute($this->strQuery);
	}
	
	
	public function Update()
	{
		$DB = new DB;
		
		if($this->logo<>'')
		{
			$this->logo=" , logo='".$this->logo."'";
		}
		
		$this->strQuery="Update t_client  set
		client_type=".$this->client_type.",
		software_version_id=".$this->software_version_id.",
		distributor_id=".$this->distributor_id.", 
		client_name='".$this->client_name."', 
		email_address='".$this->email_address."', 
		address_line_1='".$this->address_line_1."', 
		address_line_2='".$this->address_line_2."', 
		city='".$this->city."', 
		state='".$this->state."', 
		zip='".$this->zip."', 
		country='".$this->country."', 		 
		dom=now(), 		 
		modified_by=".$this->modified_by.",
		phone='".$this->phone."',
		website='".$this->website."',
		contact_name='".$this->contact_name."',
		contact_title='".$this->contact_title."',
		contact_email='".$this->contact_email."',
		manager_name='".$this->manager_name."',
		manager_email='".$this->manager_email."',
		manager_phone='".$this->manager_phone."'
		".$this->logo."
		Where client_id=".$this->client_id;		
		//print $this->strQuery;
		
		$DB->Execute($this->strQuery);
		
		
		
		if($this->password=='')
		{
			$this->strQuery="Select password from t_login where  user_id=".$this->client_id." And user_type = 1";	
			$PwdArr=$DB->Returns($this->strQuery);
			if($Pwd=mysql_fetch_object($PwdArr))
			{
				$this->password=$Pwd->password;
			}
		}
		
		$this->strQuery="Update t_login set 
		email_address='".$this->email_address."', 
		password='".$this->password."'
		Where user_id=".$this->client_id."
		And user_type = 1";
		$DB->Execute($this->strQuery);		
	}
	
	
	public function Deactivate()
	{
		$DB = new DB;
		$this->strQuery="Update t_client set delete_flag=1
		Where client_id=".$this->client_id;
		$DB->Execute($this->strQuery);
	}
	
	public function Activate()
	{
		$DB = new DB;
		$this->strQuery="Update t_client set delete_flag=0
		Where client_id=".$this->client_id;
		$DB->Execute($this->strQuery);
	}
	
	public function FetchCustomerType($strCustomerType=0)
	{
		$DB = new DB;		
		$this->strSQL="Select * from t_client_type order by client_type";
		$strRsClientTypes=$DB->Returns($this->strSQL);
		print '<optgroup label="Create New Industry">
			<option value="-1">New Industry</option>
		</optgroup>';
		
		print '<optgroup label="Select from List">';
		$iCtr=0;
		
		while($strClientTypes=mysql_fetch_object($strRsClientTypes))
		{
			$AllowDelete=1;
			$this->strSQL="Select client_id from t_client where client_type=".$strClientTypes->client_type_id;
			$strRsCheckDeleteArr=$DB->Returns($this->strSQL);
			if($strRsCheckDelete=mysql_fetch_object($strRsCheckDeleteArr))
			{
				$AllowDelete=0;
			}
			
			if($strCustomerType==0)
			{
				$selected='';
				$iCtr++;
				if($iCtr==1) $selected='selected';
			}
			else
			{
				$selected='';
				if($strCustomerType==$strClientTypes->client_type_id) $selected='selected';
			}
			print '<option value="'.$AllowDelete.'~'.$strClientTypes->client_type_id.'" '.$selected.'> '.$strClientTypes->client_type.'</option>';
		}
		print '</optgroup>';
	}
	
	public function AllCustomers()
	{
		$DB=new DB;
		$this->strSQL="Select t_client.*, t_client_type.client_type as client_type_name from t_client, t_client_type where t_client.delete_flag=0 and  t_client_type.client_type_id=t_client.client_type order by t_client.client_name";
		$strRsCustomerLists=$DB->Returns($this->strSQL);
		while($strRsCustomerList=mysql_fetch_object($strRsCustomerLists))
		{
			$strSQL="Select software_version from t_software_version where software_version_id=".$strRsCustomerList->software_version_id;
			$strRsSoftwareVersionArr=$DB->Returns($strSQL);
			if($strRsSoftwareVersion=mysql_fetch_object($strRsSoftwareVersionArr))
			{
				$software_version_name=$strRsSoftwareVersion->software_version;
			}
			$strRsCustomerListArr[]=array
			(
				'client_id'=>$strRsCustomerList->client_id,
				'client_name'=>$strRsCustomerList->client_name,
				'client_type_name'=>$strRsCustomerList->client_type_name,
				'software_version_name'=>$software_version_name,
				'client_type'=>$strRsCustomerList->client_type,
				'distributor_id'=>$strRsCustomerList->distributor_id, 
				'email_address'=>$strRsCustomerList->email_address, 
				'password'=>$strRsCustomerList->password, 
				'address_line_1'=>$strRsCustomerList->address_line_1, 
				'address_line_2'=>$strRsCustomerList->address_line_2, 
				'city'=>$strRsCustomerList->city, 
				'state'=>$strRsCustomerList->state, 
				'zip'=>$strRsCustomerList->zip, 
				'country'=>$strRsCustomerList->country, 
				'doc'=>$strRsCustomerList->doc, 
				'dom'=>$strRsCustomerList->dom, 
				'created_by'=>$strRsCustomerList->created_by, 
				'modified_by'=>$strRsCustomerList->modified_by,
				'delete_flag'=>$strRsCustomerList->delete_flag
			);
			
		}
		return $strRsCustomerListArr;
	}
	
	
	
	public function ListClient($strClientID=0)
	{
		print '<option value="0">Select Client</option>';
		$DB=new DB;
		$strSQL="Select * from t_client where delete_flag=0 order  by client_name asc";	
		$strRsClientArr=$DB->Returns($strSQL);		
		while($strRsClient=mysql_fetch_object($strRsClientArr))
		{
			if($strClientID==$strRsClient->client_id)
			{
				$strSelected='Selected';
			}
			else
			{
				$strSelected='';
			}
			print '<option value="'.$strRsClient->client_id.'" '.$strSelected.' >'.$strRsClient->client_name.'</option>';
		}
	}
	
	public function ListSoftwareVersion($strSoftwareVersionID=0)
	{
		print '<option value="">Select Version</option>';
		$DB=new DB;
		$strSQL="Select * from t_software_version order  by software_version asc";	
		$strRsVersionArr=$DB->Returns($strSQL);		
		while($strRsVersion=mysql_fetch_object($strRsVersionArr))
		{
			if($strSoftwareVersionID==$strRsVersion->software_version_id)
			{
				$strSelected='Selected';
			}
			else
			{
				$strSelected='';
			}
			print '<option value="'.$strRsVersion->software_version_id.'" '.$strSelected.' >'.$strRsVersion->software_version.'</option>';
		}
	}
	
	
}

?>