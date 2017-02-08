<?php
error_reporting(0);
mysql_connect("localhost","root","");
mysql_select_db("db_editor");

$id=$_GET['id'];


if($id=="" or $id==0)
{
	$strSQL="Select * from t_control Order By id desc";
}
else
{
	$strSQL="Select * from t_control where id=$id";
}


$strDiv='';

$strRsJsonData=mysql_query($strSQL);
if($strJsonData=mysql_fetch_object($strRsJsonData))
{
	
	$jsonArr=(json_decode($strJsonData->json_data));
	print "<pre>";
	foreach($jsonArr as $jsonVal)
	{
		
		if(is_array($jsonVal) && count($jsonVal))
		{
			foreach($jsonVal as $jsonArrVal)
			{
				$strFunctionName='';
				print_r($jsonArrVal);
				if($jsonArrVal->type=='image')
				{
					if(strstr($jsonArrVal->src,"Chiller.png"))
						$strFunctionName='chiller();';
					$strDiv.='<div style="position:absolute; left:'.$jsonArrVal->left.'; top:'.$jsonArrVal->top.'"><img src="'.$jsonArrVal->src.'" width="'.($jsonArrVal->width*$jsonArrVal->scaleX).'" height="'.($jsonArrVal->height* $jsonArrVal->scaleY).'" onclick="'.$strFunctionName.'" /></div>';
				}
			}
		}
	}
	
	print "</pre>";
	
	$json_data=$strJsonData->json_data;
	$json_data=str_replace('},',' ,"lockRotation":"true" ,"lockMovementX":"true","lockMovementY":"true","lockScalingX":"true","lockScalingY":"true" ,"selectable":"false","hasBorders":"false","hasControls":"false", "hasRotatingPoint": "false" }, ',$json_data);
	$json_data=str_replace('}]','  ,"lockRotation":"true" ,"lockMovementX":"true","lockMovementY":"true","lockScalingX":"true","lockScalingY":"true" ,"selectable":"false","hasBorders":"false","hasControls":"false", "hasRotatingPoint": "false" }]',$json_data);
}
else
{
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>System Design</title>
</head>

<body>
</body>
</html>
