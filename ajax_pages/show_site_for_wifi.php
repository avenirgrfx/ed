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
	$output = "<div class='site_folder' style='cursor:auto; font-size:20px;'><span style='font-weight:normal;'>Site: </span> <span style='cursor:pointer;' onclick=ShowTextFields('w_".$strRsSite->site_id."')>".$strRsSite->site_name."</span></div>
	
	<div id='w_".$strRsSite->site_id."' style='overflow:auto;max-height:250px;width:100%;display:none; margin-bottom:30px;margin-left:2%;'> ";
	$strSQL_router = "Select * from t_router where site_id = ".$strRsSite->site_id." order by router_name";
	$routerArr=$DB->Returns($strSQL_router);
	while($router=mysql_fetch_object($routerArr)){
		$output .= "<div style='float:left; width:90%; background-color:#75CCDD; font-size:16px; margin:10px 0 8px 0;'>	
				<div id='".$router->router_id."_sh' onclick='showMACfield(".$router->router_id.")' style='cursor: pointer; cursor: hand; background-color:#38A7E2;float:left;width:15px;padding-left:4px;padding-right:1px; font-weight: bold;'>+</div>
			".$router->router_name." - MAC ID :".$router->router_macid."</div><br><br>
		<div id='".$router->router_id."_div' style='float:left;margin-top:-5px;display:none;'>
			<div style='min-width:300px'>
				<label style='text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>
					SSID :
				</label>
			</div>
			<input type='text' style='float:left;' name='".$router->router_id."_ssid' value='".$router->ssid."' >
			<input type='submit' style='float:left;' onclick='setSSID(".$strRsSite->site_id.",".$router->router_id.",1)' value='SET'>
			<img src='../images/loading.gif' height='20' width='100' id='ssidld_".$router->router_id."' style='margin-top:-20px;display:none;float:left;'><br><br><br>
			<div style='min-width:240px;max-width:240px;float:left;height:20px;'>
				<label style='text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>Protocol :</label>
			</div>
			<select id='".$router->router_id."_protocol' onchange='selectProtocol(".$router->router_id.")' style='float:left;margin:28px 0 0 -240px;'>
				<option value = '0'";
				if($router->protocol == '0') $output .= "selected ='selected'";
				$output.= ">Choose Protocl</option><option value = '1'";
				if($router->protocol == '1') $output.= "selected='selected'";
				$output.= ">Static IP</option><option value = '2'";
				if($router->protocol == '2')$output.="selected='selected'";
				$output.=">DHCP</option>
			</select>
			<div id='".$router->router_id."_static_ip' style='width:720px;float:left;display:";
			if($router->protocol == '1') $output.="block;'>";
			else $output.="none;'>";
			$output.="<div style='max-width:240px;float:left;height:70px;'>
					<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>IP Address :</label>
					<input type='text' name='".$router->router_id."_ipaddress' value='".$router->ipaddress."' style='float:left;'> 
				</div>
				<div style='max-width:240px;float:left;height:70px;'>
					<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>IP Netmask :</label>
					<input type='text' name='".$router->router_id."_ipnetmask' value='".$router->ipnetmask."' style='float:left;'>
				</div>
				<div style='max-width:240px;float:left;height:70px;'>
					<label style='float:left; text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;'>IP Gateway :</label>
					<input type='text' name='".$router->router_id."_gateway' value='".$router->gateway."' style='float:left;'>
					
				</div>
			</div>
			<div style='float:left;width:180px;height:30px;margin:30px 0 0 -15px;'>
				<input type='submit' style='float:left' onclick='setSSID(".$strRsSite->site_id.",".$router->router_id.",2)' value='SET'>
				<img src='../images/loading.gif' height='20' width='100' id='protold_".$router->router_id."' style='margin-top:-20px;display:none;float:left;'>

			</div>
		</div>";
	}
	
		
	$output .=	"</div>";
    echo $output;
}
?>
