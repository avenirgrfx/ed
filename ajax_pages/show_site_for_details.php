<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

$strSiteID=$_GET['id'];
$strSQL="Select * from t_sites where client_id=$strSiteID order by site_name";
$strRsSiteArr=$DB->Returns($strSQL);
while($strRsSite=mysql_fetch_object($strRsSiteArr))
{
	$output = '';
	$output = "<div class='site_folder' style='cursor:auto; font-size:20px;'><span style='font-weight:normal;'>Site: </span> <span style='cursor:pointer;' onclick=showRouterDetails('D_".$strRsSite->site_id."')>".$strRsSite->site_name."</span></div>
	
	<div id='D_".$strRsSite->site_id."' style='overflow:auto;max-height:250px;width:100%;display:none; margin-bottom:30px;margin-left:2%;'> ";
	$strSQL_router = "Select * from t_router where site_id = ".$strRsSite->site_id." order by router_name";
	$routerArr=$DB->Returns($strSQL_router);
	while($router=mysql_fetch_object($routerArr)){
		$output .= "<div style='float:left; width:90%; background-color:#75CCDD; font-size:16px;'>
		<div id='".$router->router_id."_det' onclick='showMACdetails(".$router->router_id.")' style='cursor: pointer; cursor: hand; background-color:#38A7E2;float:left;width:15px;padding-left:4px;padding-right:1px; font-weight: bold;'>+</div>
		".$router->router_name." - MAC ID :".$router->router_macid."</div><br><br>
		<div id='".$router->router_id."_div_d' style='float:left;margin:-5px 0 0 10px;display:none;padding:10px 0 10px 0;'>
			<div style='min-width:400px;max-width:400px;float:left;max-height:50px;min-height:50px;background-color:#E1F1F4;margin:0 0 10px 0;'>
				<label style='text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>
					Firmware Version:
				</label>
				".$router->version."
				<br>
				<label style='text-transform:uppercase; font-size:14px; font-weight:bold;float:left;'>
					Alarm Status:
				</label>
				".$router->alarmstatus."
			
			</div>
			<div style='min-width:400px;max-width:400px;float:left;max-height:50px;min-height:50px;background-color:#E1F1F4;margin:0 0 10px 0;'>
				<label style='text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>
					RX:
				</label>
				".$router->rx." Bytes
				<label style='text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;margin-left:10px;'>
					TX:
				</label>
				".$router->tx." Bytes
				<br>
				<label style='text-transform:uppercase; font-size:14px; font-weight:bold;float:left;'>
					Other System logs:
				</label>
				".$router->logs."
			</div>
		</div>";
	}
	
		
	$output .=	"</div>";
    echo $output;
}
?>

