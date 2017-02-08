
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
	$output = "<div class='site_folder' style='cursor:auto; font-size:20px;'><span style='font-weight:normal;'>Site: </span> <span style='cursor:pointer;' onclick=ShowFirewallSettings('f_".$strRsSite->site_id."')>".$strRsSite->site_name."</span></div>
	
	<div id='f_".$strRsSite->site_id."' style='overflow:auto;max-height:250px;width:100%;display:none; margin-bottom:30px;margin-left:2%;'> ";
	$strSQL_router = "Select * from t_router where site_id = ".$strRsSite->site_id." order by router_name";
	$routerArr=$DB->Returns($strSQL_router);
	while($router=mysql_fetch_object($routerArr)){
		$output .= "<div style='float:left; width:90%; background-color:#75CCDD; font-size:16px; margin:10px 0 8px 0;'>	
				<div id='".$router->router_id."_me' onclick='showMACmesh(".$router->router_id.")' style='cursor: pointer; cursor: hand; background-color:#38A7E2;float:left;width:15px;padding-left:4px;padding-right:1px; font-weight: bold;'>+</div>
			".$router->router_name." - MAC ID :".$router->router_macid."</div><br><br>
			<div id='".$router->router_id."_div_me' style='width:1000px;float:left;margin:-5px 0 0 10px;display:none;'>
				<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>Select Mode :</label>
				<select  id='".$router->router_id."_mode' onchange=modeCheck(".$router->router_id.") style='float:left;'>
					<option value='1'>adhoc</option>
					<option value='0'>802.11s</option>
				</select>
					<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin:5px 10px 0 15px;'>ESSID &nbsp&nbsp&nbsp&nbsp&nbsp
					&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:</label>
					<input type='text' id='".$router->router_id."_essid' style='float:left;'>
					<div style='display:block;' id='".$router->router_id."_bssid_div'>
						<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin:5px 10px 0 15px;'>BSSID :</label>
						<input type='text' id='".$router->router_id."_bssid' style='float:left;'>
					</div>
					<br><br>
					<div style='display:none;' id='".$router->router_id."_gate_div'>
						<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin:5px 5px 0 0px;'>IPV4 Address :</label>
						<input type='text' id='".$router->router_id."_ip4a' style='float:left;'>
					
					
						<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin:5px 10px 0 15px;'>IPV4 Gateway :</label>
						<input type='text' id='".$router->router_id."_ip4g' style='float:left;'>
					</div>
				
					<div style='float:right;width:100px;height:30px;margin-right:30px;'>
						<img src='../images/loading.gif' height='20' width='100' id='meshld_".$router->router_id."' style='margin-top:-20px;display:none;float:right;margin-right:-105px;'>						
						<input type='submit' style='border:1px solid #486958;border-radius:3px;float:right;background-color:#66A886;' onclick=\"saveMesh(".$router->router_id.",'".$router->router_macid."')\" value='SET'>
					
					</div>
			</div>";
	}
	
		
	$output .=	"</div>";
    echo $output;
}
?>
