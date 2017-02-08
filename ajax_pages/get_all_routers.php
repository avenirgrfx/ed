<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$counter = 0;
$DB=new DB;

$strSQL="Select * from t_router";
$strRouterArr=$DB->Returns($strSQL);
$output = "<table style='text-align:center;min-width:720px;float:left;'>
				<thead style='display:block;min-width:720px;'>
						<tr>
							<td width=200 style='text-align:center;'>ROUTER</td>
							<td width=20 style='text-align:center;'>Status</td>
							<td width=200 style='text-align:center;'>MAC ID</td>
							<td width=200 style='text-align:center;'>DATE</td>
							<td width=100 style='text-align:center;'>OPTIONS</td>
						</tr>
				</thead>
				<tbody style='display:block;overflow:auto;min-height:250px;max-height:250px;'>";
$i = 0;
while($strRouter=mysql_fetch_object($strRouterArr))
{
	$i++;
	$date = strtotime(date("Y-m-d H:i:s"));
	$active_time = strtotime($strRouter->active_time);
	$diff = $date - $active_time ;
	if($i % 2) $tr_bg = '#75CCDD';
	else $tr_bg = '#D7E7EA';
	$counter++;
	$output .= "<tr bgcolor='".$tr_bg."' style='height:30px;' id='".$strRouter->router_macid."'>
					<td width=200 style='text-align:center;'>".$strRouter->router_name."</td>
					<td width=20 style='text-align:center;'>";
					if($diff < 350)
						$output .="	<img style='margin:0 auto;' src='../images/system_on_symbol.png'>";
					else
						$output .="<img style='margin:0 auto;' src='../images/system_off_symbol.png'>";
					
					$output .="</td>
					<td width=200 style='text-align:center;'>".$strRouter->router_macid."</td>
					<td width=200 style='text-align:center;'>".$strRouter->router_date."</td>
					<td width=100 style='text-align:center;'>";
					if($diff < 350)
						$output .= "<img  src='../images/reboot.png' title='Reboot' style='margin:0 auto;margin-right:3px; cursor: pointer;cursor: hand;' onclick=reboot('".$strRouter->router_macid."')></img>";
						$output .="<img  src='../images/edit-black.png' title='Edit' style='margin:0 auto; cursor: pointer;cursor: hand;'  onclick=editRouter('".$strRouter->router_id."','".$strRouter->router_macid."','".$strRouter->router_name."')></img>
						<img  src='../images/delete-email.png' title='Delete' style='margin:0 auto; cursor: pointer;cursor: hand;' onclick=deleteRouter('".$strRouter->router_macid."')></img>
					</td>
				</tr>";
}
$output .= "</tbody></table>";
if($counter == 0) $output = 'No Routers Added';
echo $output;
?>

