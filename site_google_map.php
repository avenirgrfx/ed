<?php
ob_start();
session_start();
require_once("configure.php");
require_once(AbsPath."classes/all.php");
$client_id=$_GET['client_id'];
$DB=new DB;


$strSQL="Select * from t_building where client_id=$client_id";
$strRsBuildingArr=$DB->Returns($strSQL);
if(mysql_num_rows($strRsBuildingArr)<=0)
{
	# Locate Sites on Google Map
	$strSQL="Select * from t_sites where client_id=$client_id";
	$strRsSiteArr=$DB->Returns($strSQL);
	
	header("Content-type: text/xml");
	echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
	echo "<markers>";
	
	while($strRsSite=mysql_fetch_object($strRsSiteArr))
	{
		$address_orig=$strRsSite->address_line1.', '.$strRsSite->city.', '.$strRsSite->state.', '.$strRsSite->zip.', '.$strRsSite->country;
		$address=urlencode($address_orig);
		
		$address_orig=$strRsSite->address_line1.'<br />'.$strRsSite->city.', '.$strRsSite->state.'<br />'.$strRsSite->zip.', '.$strRsSite->country;
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
		$output= json_decode($geocode);
		$lat = $output->results[0]->geometry->location->lat;
		$long = $output->results[0]->geometry->location->lng;
		
		if($lat=='' or $long=='')
			continue;
			
		$str.='	
		<marker>
		<name>'.$strRsSite->site_name.'</name>
		<address>'.$address_orig.'</address>
		<address1>'.$strRsSite->address_line1.'</address1>
		<city>'.$strRsSite->city.'</city>
		<state>'.$strRsSite->state.'</state>
		<zip>'.$strRsSite->zip.'</zip>
		<country>'.$strRsSite->country.'</country>
		<lat>'.$lat.'</lat>
		<lng>'.$long.'</lng>
		</marker>';
	}
	echo $str;
	echo "</markers>";
}
else
{
	# Locate Buildings on Google Map	
	header("Content-type: text/xml");
	echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
	echo "<markers>";
	
	while($strRsBuilding=mysql_fetch_object($strRsBuildingArr))
	{
		$address_orig=$strRsBuilding->address_line1.', '.$strRsBuilding->city.', '.$strRsBuilding->state.', '.$strRsBuilding->zip.', '.$strRsBuilding->country;
		$address=urlencode($address_orig);
		
		$address_orig=$strRsBuilding->address_line1.'<br />'.$strRsBuilding->city.', '.$strRsBuilding->state.'<br />'.$strRsBuilding->zip.', '.$strRsBuilding->country;
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
		$output= json_decode($geocode);
		$lat = $output->results[0]->geometry->location->lat;
		$long = $output->results[0]->geometry->location->lng;
		
		if($lat=='' or $long=='')
			continue;
			
		$str.='	
		<marker>
		<name>'.$strRsBuilding->building_name.'</name>
		<address>'.$address_orig.'</address>
		<address1>'.$strRsBuilding->address_line1.'</address1>
		<city>'.$strRsBuilding->city.'</city>
		<state>'.$strRsBuilding->state.'</state>
		<zip>'.$strRsBuilding->zip.'</zip>
		<country>'.$strRsBuilding->country.'</country>
		<lat>'.$lat.'</lat>
		<lng>'.$long.'</lng>
		</marker>';
	}
	echo $str;
	echo "</markers>";
}





?>