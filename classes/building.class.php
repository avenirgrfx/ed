<?php
ob_start();
session_start();
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");
$DB=new DB;

$All_Sites_Allow=false;
if($_SESSION['client_details']->overrided_login==true or $_SESSION['client_details']->customer_user_access_id==0)
{
	$All_Sites_Allow=true;
}
else
{
	# Allowed sites for Operations
	$_SESSION['Allowed_Sites_Operations']=array();
	$strSQL="Select site_id from t_customer_user_access_site where customer_user_access_id=".$_SESSION['client_details']->customer_user_access_id." and type=2";
	$strRsAllowedSitesArr=$DB->Returns($strSQL);
	while($strRsAllowedSites=mysql_fetch_object($strRsAllowedSitesArr))
	{
		$_SESSION['Allowed_Sites_Operations'][]= $strRsAllowedSites->site_id;
	}
}

class Building
{
	public $site_id, $client_id, $site_name, $address_line1, $address_line2, $city, $state, $zip, $country, 
	$square_feet, $gas_utility, $electricity_utility, $water_utility, $climate_zone, $cost_gas, $cost_electric,
	$electric_account, $gas_account, $electric_rate, $gas_rate,	
	$contact_name, $contact_email, $department, $telephone, $note;
	public $building_id, $room_id, $building_name, $location;
	public $doc, $dom, $created_by, $modified_by, $delete_flag;
	
	public $device_id, $status, $serial, $model, $alarm, $time_zone;
	
	
	private  $strQuery;
	
	public function Building()
	{
		$this->site_id=0;
		$this->client_id=0;
		$this->site_name="";
		$this->address_line1="";
		$this->address_line2="";
		$this->city="";
		$this->state="";
		$this->zip="";
		$this->country="";		
		$this->square_feet="";
		$this->gas_utility="";
		$this->electricity_utility="";
		$this->water_utility="";
		$this->climate_zone="";
		$this->cost_gas=0;
		$this->cost_electric=0;
		$this->electric_account='';
		$this->gas_account='';
		$this->electric_rate='';
		$this->gas_rate='';
				
		$this->contact_name="";
		$this->contact_email="";
		$this->department="";
		$this->telephone="";		
		$this->note="";		
		$this->building_id=0;
		$this->room_id=0;
		$this->building_name="";
		$this->location="";
		
		$this->doc=date("Y-m-d");
		$this->dom=date("Y-m-d");
		$this->created_by=0;
		$this->modified_by=0;
		$this->delete_flag=0;
		$this->strQuery="";
		
		$this->device_id='';
		$this->status=0;
		$this->serial='';
		$this->model='';
		$this->alarm=0;
		$this->time_zone='';
		
	}
	
	public function setVal($args)
	{
		$this->site_id=mysql_escape_string($args['site_id']);
		$this->client_id=mysql_escape_string($args['client_id']);
		$this->site_name=mysql_escape_string($args['site_name']);
		$this->address_line1=mysql_escape_string($args['address_line1']);
		$this->address_line2=mysql_escape_string($args['address_line2']);
		$this->city=mysql_escape_string($args['city']);
		$this->state=mysql_escape_string($args['state']);
		$this->zip=mysql_escape_string($args['zip']);
		$this->time_zone=mysql_escape_string($args['time_zone']);
		$this->country=mysql_escape_string($args['country']);		
		$this->square_feet=mysql_escape_string($args['square_feet']);
		$this->gas_utility=mysql_escape_string($args['gas_utility']);
		$this->electricity_utility=mysql_escape_string($args['electricity_utility']);
		
		$this->water_utility=mysql_escape_string($args['water_utility']);
		$this->climate_zone=mysql_escape_string($args['climate_zone']);
		
		$this->cost_gas=mysql_escape_string( ($args['cost_gas']<>''? $args['cost_gas'] : 0 ));
		$this->cost_electric=mysql_escape_string( ($args['cost_electric']<>'' ? $args['cost_electric'] : 0) );	
		
		$this->electric_account=mysql_escape_string($args['electric_account']);
		$this->gas_account=mysql_escape_string($args['gas_account']);
		$this->electric_rate=mysql_escape_string($args['electric_rate']);
		$this->gas_rate=mysql_escape_string($args['gas_rate']);
		
		
		$this->contact_name=mysql_escape_string($args['contact_name']);
		$this->contact_email=mysql_escape_string($args['contact_email']);
		$this->department=mysql_escape_string($args['department']);
		$this->telephone=mysql_escape_string($args['telephone']);			
		$this->note=mysql_escape_string($args['note']);		
		$this->building_id=mysql_escape_string($args['building_id']);
		$this->room_id=mysql_escape_string($args['room_id']);
		$this->building_name=mysql_escape_string($args['building_name']);
		$this->location=mysql_escape_string($args['location']);
		
		$this->doc=mysql_escape_string($args['doc']);
		$this->dom=mysql_escape_string($args['dom']);
		$this->created_by=mysql_escape_string($args['created_by']);
		$this->modified_by=mysql_escape_string($args['modified_by']);
		$this->delete_flag=mysql_escape_string($args['delete_flag']);
		
		$this->device_id=mysql_escape_string($args['device_id']);
		$this->status=mysql_escape_string($args['status']);
		$this->serial=mysql_escape_string($args['serial']);
		$this->model=mysql_escape_string($args['model']);
		$this->alarm=mysql_escape_string($args['alarm']);
		$this->time_zone=mysql_escape_string($args['time_zone']);
		
	}
	
	public function InsertSite()
	{
		$DB=new DB;
		$this->strQuery="Insert into t_sites (client_id, site_name, address_line1, address_line2, city, 
		state, zip, country, note, doc, dom, created_by, modified_by, delete_flag, time_zone)
		
		Values(".$this->client_id.", '".$this->site_name."', '".$this->address_line1."', '".$this->address_line2."', '".$this->city."', 
		'".$this->state."', '".$this->zip."', '".$this->country."', '".$this->note."', now(), now(), ".$this->created_by.", ".$this->modified_by.", ".$this->delete_flag.",'".$this->time_zone."')";
		
		$DB->Execute($this->strQuery);
		
		$folder_id=6; # For Sites under t_file_sharing_folders table
		$this->strQuery="Insert into t_client_files_under_folder(client_id, folder_id, file_name, sub_folder_id, folder_name, doc, created_by)
		Values(".$this->client_id.", $folder_id, '', 0, '".$this->site_name."', now(), ".$this->created_by.")";
		$DB->Execute($this->strQuery);
		
	}
	
	public function UpdateSite()
	{
		$DB=new DB;
		
		$strSQL="Select client_files_under_folder_id from t_client_files_under_folder where client_id=".$this->client_id." and folder_name=(select site_name from t_sites where site_id=".$this->site_id.")";
		$strSiteExistingNameArr=$DB->Returns($strSQL);
		if($strSiteExistingName=mysql_fetch_object($strSiteExistingNameArr))
		{
			$client_files_under_folder_id=$strSiteExistingName->client_files_under_folder_id;
		}
		
		$this->strQuery="Update t_sites set site_name='".$this->site_name."', address_line1='".$this->address_line1."', address_line2='".$this->address_line2."', 
		city='".$this->city."', state='".$this->state."', zip='".$this->zip."', country='".$this->country."', note='".$this->note."', dom=now(), time_zone='".$this->time_zone."' 
		where site_id=".$this->site_id;
				
		$DB->Execute($this->strQuery);
		
		$folder_id=6; # For Sites under t_file_sharing_folders table
		if($client_files_under_folder_id<>"")
		{
			$this->strQuery="Update t_client_files_under_folder set  folder_name='".$this->site_name."' where client_id=".$this->client_id." and client_files_under_folder_id=$client_files_under_folder_id";		
		}
		else
		{
			$this->strQuery="Insert into t_client_files_under_folder(client_id, folder_id, file_name, sub_folder_id, folder_name, doc, created_by)
			Values(".$this->client_id.", $folder_id, '', 0, '".$this->site_name."', now(), ".$this->created_by.")";
		}
		
		$DB->Execute($this->strQuery);
		
	}
	
	public function DeleteSite()
	{
		$DB=new DB;
		$this->strQuery="Delete from t_sites where site_id=".$this->site_id." and client_id=".$this->client_id;
		$DB->Execute($this->strQuery);
	}
	
	public function InsertBuilding()
	{
		$DB=new DB;
		$this->strQuery="Insert into t_building(client_id,site_id, building_name, location, address_line1, 
		address_line2, city, state, zip, country, square_feet, gas_utility, electricity_utility, water_utility, climate_zone,  cost_gas, cost_electric, 
		electric_account, gas_account, electric_rate, gas_rate,
		contact_name, contact_email, department, telephone, 
		note, doc, dom, created_by, modified_by, delete_flag)
		
		Values(".$this->client_id.",".$this->site_id.", '".$this->building_name."', '".$this->location."', '".$this->address_line1."', 
		'".$this->address_line2."', '".$this->city."', '".$this->state."', '".$this->zip."', '".$this->country."', 
		'".$this->square_feet."', '".$this->gas_utility."', '".$this->electricity_utility."', '".$this->water_utility."', '".$this->climate_zone."',
		".$this->cost_gas.", ".$this->cost_electric.",'".$this->electric_account."','".$this->gas_account."','".$this->electric_rate."','".$this->gas_rate."',
		'".$this->contact_name."', '".$this->contact_email."', '".$this->department."', '".$this->telephone."',
		'".$this->note."', now(), now(), ".$this->created_by.", ".$this->modified_by.", 0)";		
		
		#print $this->strQuery;
		
		$this->building_id=$DB->Execute($this->strQuery);
		
		return $this->building_id;
	}
	
	public function UpdateBuilding()
	{
		$DB=new DB;
		$this->strQuery="Update t_building set site_id=".$this->site_id.", building_name='".$this->building_name."', location='".$this->location."', address_line1='".$this->address_line1."', address_line2='".$this->address_line2."', 
		city='".$this->city."', state='".$this->state."', zip='".$this->zip."', time_zone='".$this->time_zone."', country='".$this->country."', square_feet='".$this->square_feet."', gas_utility='".$this->gas_utility."', electricity_utility='".$this->electricity_utility."', 
		water_utility='".$this->water_utility."', climate_zone='".$this->climate_zone."', cost_gas=".$this->cost_gas.", cost_electric=".$this->cost_electric.",
		electric_account='".$this->electric_account."', gas_account='".$this->gas_account."', electric_rate='".$this->electric_rate."', gas_rate='".$this->gas_rate."', 
		contact_name='".$this->contact_name."', contact_email='".$this->contact_email."',
		department='".$this->department."', telephone='".$this->telephone."', note='".$this->note."', dom=now() Where building_id=".$this->building_id;		
		
		$DB->Execute($this->strQuery);
	}
	
	public function FetchSites($ClientID,$strSiteID=0)
	{
		$DB=new DB;
		$this->strQuery="Select site_id, site_name  from t_sites where client_id=".$ClientID." order by site_name";
		$rsSiteArr=$DB->Returns($this->strQuery);
		print '<option value="">Select Active Site from List</option>';
		while($SiteArr=mysql_fetch_object($rsSiteArr))
		{
			if($strSiteID==$SiteArr->site_id)
			{
				$strSelected=' Selected="Selected" ';
			}
			else
			{
				$strSelected='';
			}
			print '<option value="'.$SiteArr->site_id.'" '.$strSelected.'>'.$SiteArr->site_name.'</option>';
		}
	}
	
	
	
	public function FetchBuilding($ClientID,$strBuildingID=0)
	{
		$DB=new DB;
		$this->strQuery="Select building_id, building_name  from t_building where client_id=".$ClientID." order by building_name";
		$rsSiteArr=$DB->Returns($this->strQuery);
		print '<option value="">Select Active Building from List</option>';
		while($SiteArr=mysql_fetch_object($rsSiteArr))
		{
			if($strBuildingID==$SiteArr->building_id)
			{
				$strSelected=' Selected="Selected" ';
			}
			else
			{
				$strSelected='';
			}
			print '<option value="'.$SiteArr->building_id.'" '.$strSelected.'>'.$SiteArr->building_name.'</option>';
		}
	}
	
	
	
	public function FetchBuildingSites($ClientID, $strBuildingID=0, $strSiteID=0)
	{
		$DB=new DB;
		$this->strQuery="Select site_id, site_name  from t_sites where client_id=".$ClientID." order by site_name";
		$rsSiteArr=$DB->Returns($this->strQuery);	
		while($SiteArr=mysql_fetch_object($rsSiteArr))
		{
			print '<optgroup label="'.$SiteArr->site_name.'">';			
			
			$this->strQuery="Select building_id, building_name  from t_building where site_id=".$SiteArr->site_id." order by building_name";
			$rsSiteArr1=$DB->Returns($this->strQuery);
			
			while($SiteArr1=mysql_fetch_object($rsSiteArr1))
			{
				if($strBuildingID==$SiteArr1->building_id)
				{
					$strSelected=' Selected="Selected" ';
				}
				else
				{
					$strSelected='';
				}
				print '<option value="'.$SiteArr1->building_id.'" '.$strSelected.'>'.$SiteArr1->building_name.'</option>';
			}
			
			print '</optgroup>';
		}
	}
	
	
	public function InsertDevice()
	{
		$DB=new DB;
		$this->strQuery="Insert into t_device_status(device_id, status, building_id, room_id, client_id, 
		serial, model, doc, alarm, notes, location)
		Values('".$this->device_id."',0, '".$this->building_id."',".$this->room_id.",".$this->client_id.",'".$this->serial."', '".$this->model."',
		now(), 0, '".$this->notes."','".$this->location."')";
		
		$DB->Execute($this->strQuery);
	}
	
	
	public function DeleteDevice($strDeviceID)
	{
		$DB=new DB;
		
		$this->strQuery="Select device_id from t_device_status where device_status_id=$strDeviceID";
		$DeviceIDsArr=$DB->Returns($this->strQuery);
		if($DeviceIDs=mysql_fetch_object($DeviceIDsArr))
		{
			$device_id=$DeviceIDs->device_id;
		}
		
		# Delete from Device Note
		$this->strQuery="Delete from t_device_note where device_status_id=$strDeviceID";
		$DB->Execute($this->strQuery);
		
		# Delete from Device Feed
		$this->strQuery="Delete from t_device_feed where device_id='$device_id'";
		$DB->Execute($this->strQuery);
		
		# Delete from Device Status Table
		$this->strQuery="Delete from t_device_status where device_status_id=$strDeviceID";
		$DB->Execute($this->strQuery);
		
		
		
	}
	
	public function DeleteBuilding($strBuildingID,$strClientID)
	{
		$DB=new DB;
		/*$this->strQuery="Select device_status_id from t_device_status where building_id=$strBuildingID and client_id=$strClientID";		
		$DeviceIDsArr=$DB->Returns($this->strQuery);
		if($DeviceIDs=mysql_fetch_object($DeviceIDsArr))
		{
			$this->DeleteDevice($DeviceIDs->device_status_id);
		}*/
				
		$this->strQuery="Delete from t_building where building_id=$strBuildingID and client_id=$strClientID";
		$DB->Execute($this->strQuery);
	}
	
	public function DeleteRoom($strRoomID,$strClientID)
	{
		$DB=new DB;
		$this->strQuery="Select device_status_id from t_device_status where room_id=$strRoomID and client_id=$strClientID";		
		$DeviceIDsArr=$DB->Returns($this->strQuery);
		if($DeviceIDs=mysql_fetch_object($DeviceIDsArr))
		{
			return;
		}
				
		$this->strQuery="Delete from t_room where room_id=$strRoomID";
		$DB->Execute($this->strQuery);
	}
	
	
	public function FetchRoom($ClientID,$strRoomID=0)
	{
		$DB=new DB;
		$this->strQuery="Select room_id, room_name  from t_room where client_id=".$ClientID." order by room_name";
		$rsSiteArr=$DB->Returns($this->strQuery);
		print '<option value="">Select Active Room from List</option>';
		while($SiteArr=mysql_fetch_object($rsSiteArr))
		{
			if($strRoomID==$SiteArr->room_id)
			{
				$strSelected=' Selected="Selected" ';
			}
			else
			{
				$strSelected='';
			}
			print '<option value="'.$SiteArr->room_id.'" '.$strSelected.'>'.$SiteArr->room_name.'</option>';
		}
	}
	
	public function FetchRoomWithBuilding($ClientID,$strRoomID=0)
	{
		$DB=new DB;
		
		$this->strQuery="Select building_id, building_name from t_building where client_id=".$ClientID." Order By building_name";
		$strRsBuildingsArr=$DB->Returns($this->strQuery);
		print '<option value="">Select Active Room from List</option>';
		while($strRsBuildings=mysql_fetch_object($strRsBuildingsArr))
		{
			$building_id=$strRsBuildings->building_id;		
		
			$this->strQuery="Select room_id, room_name  from t_room where client_id=".$ClientID." and building_id=$building_id order by room_name";
			$rsSiteArr=$DB->Returns($this->strQuery);
			
			print '<optgroup label="'.$strRsBuildings->building_name.'">';
			
			while($SiteArr=mysql_fetch_object($rsSiteArr))
			{
				if($strRoomID==$SiteArr->room_id)
				{
					$strSelected=' Selected="Selected" ';
				}
				else
				{
					$strSelected='';
				}
				//print '<option value="'.$SiteArr->room_id.'" '.$strSelected.'>'.$strRsBuildings->building_name." >> ".$SiteArr->room_name.'</option>';
				print '<option value="'.$SiteArr->room_id.'" '.$strSelected.'>'.$SiteArr->room_name.'</option>';
			}
			
			print '</optgroup>';
			
		}
	}
	
	
	
	public function GetClientSitesByClientID($strClientID, $strSelected=0)
	{
		$arrSites=array();
		$DB=new DB;
		
		if($All_Sites_Allow==true)
		{
			$this->strQuery="Select * from t_sites where client_id=".$strClientID." order by site_name LIMIT $strSelected,1";
		}
		else
		{
			if($_SESSION['Allowed_Sites_Operations'][0]<>0)
			{
				$this->strQuery="Select * from t_sites where client_id=".$strClientID." and site_id in(".implode(',',$_SESSION['Allowed_Sites_Operations']).") order by site_name LIMIT $strSelected,1";
			}
			else
			{
				$this->strQuery="Select * from t_sites where client_id=".$strClientID." order by site_name LIMIT $strSelected,1";
			}
		}
		$rsSiteArr=$DB->Returns($this->strQuery);
		while($rsSite=mysql_fetch_object($rsSiteArr))
		{
			$arrSites[]=array($rsSite->site_id, $rsSite->site_name);
		}
		
		return $arrSites;
	}
    
    public function GetClientSitesByClientIDAndSiteId($strClientID, $Site_Id)
	{
		$arrSites=array();
		$DB=new DB;
		
		$this->strQuery="Select * from t_sites where client_id=".$strClientID." and site_id=". $Site_Id;
		$rsSiteArr=$DB->Returns($this->strQuery);
		while($rsSite=mysql_fetch_object($rsSiteArr))
		{
			$arrSites[]=array($rsSite->site_id, $rsSite->site_name);
		}
		
		return $arrSites;
	}
	
}

?>