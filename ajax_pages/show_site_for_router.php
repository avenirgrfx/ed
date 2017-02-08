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
	$output ='';
	$output = "	<div class='site_folder' style='cursor:auto; font-size:20px;'><span style='font-weight:normal;'>Site: </span> <span style='cursor:pointer;' id='".'s_'.$strRsSite->site_id."' onclick=ShowAddMAC('r_".$strRsSite->site_id."')>".$strRsSite->site_name."</span></div>
				<div >
				<div id='r_".$strRsSite->site_id."' style='border:1px solid #3D91A2;border-radius:5px;padding:10px;display:none; margin-bottom:50px;margin-left:2%;min-height:220px;max-height:250px;width:650px;'>
					<label style='text-transform:uppercase; font-size:14px; font-weight:bold; margin-top:5px;float:left'>ADD MAC ID  :</label>
					<form name='selection' method='post' onSubmit='return selectAll()'> 
						<div style='float:left;'>
						<select multiple size='10' id='from_".$strRsSite->site_id."' style='background-color:#A7CCD3'>";
			$strSQL_router0 = "Select * from t_router where site_id = 0 order by router_name";
			$routerArr0=$DB->Returns($strSQL_router0);
			while($router0=mysql_fetch_object($routerArr0)){
				$output .= "<option value='".$router0->router_id."'>".$router0->router_name."</option>";
			}
			
			$output .= "</select>
					</div>
					<div class='controls'>  
						<a href=\"javascript:moveSelected('from_".$strRsSite->site_id."', 'to_".$strRsSite->site_id."')\">&gt;</a> 
						<a href=\"javascript:moveSelected('to_".$strRsSite->site_id."', 'from_".$strRsSite->site_id."')\">&lt;</a> 
					</div>
					<select multiple id='to_".$strRsSite->site_id."' size='10' name='topics[]' style='background-color:#A7CCD3'>";
			$strSQL_router_used = "Select * from t_router where site_id = ".$strRsSite->site_id." order by router_name";
			$routerArr_used=$DB->Returns($strSQL_router_used);
			while($router_used=mysql_fetch_object($routerArr_used)){
				$output .= "<option value='".$router_used->router_id."'>".$router_used->router_name."</option>";
			}
			$output .= "</select>
					</form>
					<div style='float:left;margin:-5px 0 0 550px;background-color:red;'>
						<input type='submit' style='background-color:#3D91A2; border: 2px solid #3D91A2;' onclick='set_MAC_id(".$strRsSite->site_id.")' value='SET'>
					</div>
				</div>";
    echo $output;
}
?>
