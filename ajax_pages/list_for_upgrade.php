<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
$counter = 0;
$DB=new DB;

$strSQL="Select * from t_router";
$strRouterArr=$DB->Returns($strSQL);
$output = "<table style='min-width:680px;max-width:680px;'>
				<thead style='display:block;'>
						<tr>
							<th width=210>ROUTER</th>
							<th style='width:210px;'>MAC ID</th>
							<th width=210>Site Name</th>
							<th width=80>
								Select All
								<input type='checkbox' id='selectAll' onclick='selectAllbox()' value='all'>
							</th>
						</tr>
				</thead>
				<tbody style='display:block;overflow:auto;min-height:150px;max-height:250px;'>";
$i = 0;
while($strRouter=mysql_fetch_object($strRouterArr))
{
	$i++;
	if($i % 2) $tr_bg = '#75CCDD';
	else $tr_bg = '#D7E7EA';
	$str1SQL="Select site_name from t_sites where site_id = '".$strRouter->site_id."'";
	$strSiteArr=$DB->Returns($str1SQL);
	$strSite=mysql_fetch_object($strSiteArr);
	$counter++;
	$output .= "<tr bgcolor='".$tr_bg."' height=35>
					<td width=210>".$strRouter->router_name."</td>
					<td width=210>".$strRouter->router_macid."</td>
					<td width=210>".$strSite->site_name."</td>
					<td width='50' style='text-align:center;'>
						<input style='float:right;' type='checkbox' name='name[]' class='checkboxList' value='".$strRouter->router_macid."'>
					</td>
				</tr>";
}
$output .= "</tbody></table>";
if($counter == 0) $output = 'No Routers Added';
echo $output;
?>
