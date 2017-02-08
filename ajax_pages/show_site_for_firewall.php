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
				<div id='".$router->router_id."_sh_f' onclick='showMAC_firewall(".$router->router_id.")' style='cursor: pointer; cursor: hand; background-color:#38A7E2;float:left;width:15px;padding-left:4px;padding-right:1px; font-weight: bold;'>+</div>
			".$router->router_name." - MAC ID :".$router->router_macid."</div><br><br>
			<div id='".$router->router_id."_div_f' style='float:left;margin:-5px 0 0 10px;display:none;'>
				<table>
					<tr style='background-color:#7BECB2'>
						<th></th>
						<th>Input</th>
						<th>Output</th>
						<th>Forward</th>
						<th>Masquerading</th>
						<th>MSS Clamping</th>
					</tr>
					<tr>
						<td style='width:150px;height:40px;'>
							<div style='padding:2px;text-align:center;background-color:#66A886;height:20px;width:70px;border-radius:5px;'>
								WAN
							</div>
						</td>
						<td style='width:120px;height:40px;text-align:center;'>
							<select id='".$router->router_id."_winput' name='winput' style='border:1px solid #7BECB2;font-size:12px;padding:2px;text-align:center;background-color:#7BECB2;height:25px;width:70px;border-radius:5px;'>
								<option value='accept'>Accept</option>
								<option value='reject'>Reject</option>
								<option value='drop'>Drop</option>
							</select>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<select id='".$router->router_id."_woutput' name='woutput' style='border:1px solid #7BECB2;font-size:12px;padding:2px;text-align:center;background-color:#7BECB2;height:25px;width:70px;border-radius:5px;'>
								<option value='accept'>Accept</option>
								<option value='reject'>Reject</option>
								<option value='drop'>Drop</option>
							</select>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<select id='".$router->router_id."_wfwd' name='wfwd' style='border:1px solid #7BECB2;font-size:12px;padding:2px;text-align:center;background-color:#7BECB2;height:25px;width:70px;border-radius:5px;'>
								<option value='accept'>Accept</option>
								<option value='reject'>Reject</option>
								<option value='drop'>Drop</option>
							</select>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<input type='checkbox' name='wmasq' id='".$router->router_id."_wmasq' value=1>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<input type='checkbox' name='wmms' id='".$router->router_id."_wmss' value=1>
						</td>
					</tr>
					<tr>
						<td style='width:150px;height:40px;'>
							<div style='padding:2px;text-align:center;background-color:#66A886;height:20px;width:70px;border-radius:5px;'>
								LAN
							</div>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<select id='".$router->router_id."_linput' name='linput' style='border:1px solid #7BECB2;font-size:12px;padding:2px;text-align:center;background-color:#7BECB2;height:25px;width:70px;border-radius:5px;'>
								<option value='accept'>Accept</option>
								<option value='reject'>Reject</option>
								<option value='drop'>Drop</option>
							</select>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<select id='".$router->router_id."_loutput' name='loutput' style='border:1px solid #7BECB2;font-size:12px;padding:2px;text-align:center;background-color:#7BECB2;height:25px;width:70px;border-radius:5px;'>
								<option value='accept'>Accept</option>
								<option value='reject'>Reject</option>
								<option value='drop'>Drop</option>
							</select>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<select id='".$router->router_id."_lfwd' name='lfwd' style='border:1px solid #7BECB2;font-size:12px;padding:2px;text-align:center;background-color:#7BECB2;height:25px;width:70px;border-radius:5px;'>
								<option value='accept'>Accept</option>
								<option value='reject'>Reject</option>
								<option value='drop'>Drop</option>
							</select>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<input  type='checkbox' name='lmasq' id='".$router->router_id."_lmasq' value='1'>
						</td>
						<td style='width:120px;height:40px;text-align:center'>
							<input  type='checkbox' name='lmms' id='".$router->router_id."_lmss' value='1'>
						</td>
					</tr>
				</table>	
				<div style='float:right;width:200px;height:80px;'>
				<img src='../images/loading.gif' height='20' width='100' id='firewallld_".$router->router_id."' style='margin-top:-20px;display:none;float:right;margin-right:-50px;'>
					<input type='submit' style='border:1px solid #486958;border-radius:3px;float:right;background-color:#66A886;' onclick=saveFirewall(".$router->router_id.",'".$router->router_macid."') value='SET'>
					
				</div>
			</div>";
	}
	
		
	$output .=	"</div>";
    echo $output;
}
?>
