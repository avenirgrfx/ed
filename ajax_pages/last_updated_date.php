<?php
	ob_start();
	session_start();
	require_once("../configure.php");
	require_once(AbsPath."classes/all.php");

	$DB=new DB;
	$date = '';
	$strSQL="Select * from t_updateHistory";
	$strDateArr=$DB->Returns($strSQL);
	while($strDate=mysql_fetch_object($strDateArr))
	{
		$date = $strDate->updated_date;
	}	
	if($date != '')
	 echo date('d-m-Y', strtotime($date));
?>
