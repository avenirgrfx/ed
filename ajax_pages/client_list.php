<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/customer.class.php");
$Client=new Client;
?>


  <h2>Client Link</h2>
    <?php
		$iCtr=0;
		$strClientListArr=$Client->AllCustomers();	
	?>
    <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr style="font-weight:bold;">
    <td width="28%">Client Name</td>
    <td width="16%">Contact Person</td>
    <td width="14%">Industry</td>
    <td width="25%">Version</td>
    <td width="17%">Created On</td>
    <td width="17%">Login</td>
  </tr>
  <?php 
  	$strTableClass="OddRow";
  	if(is_array($strClientListArr) && count($strClientListArr)>0)
	{
		foreach($strClientListArr as $strClientList)
		{
			$iCtr++;
			if($iCtr % 2==0)
				$strTableClass="OddRow";
			else
				$strTableClass="EvenRow";
  ?>
  
  <tr style="font-size:12px;" class="<?php echo $strTableClass; ?>" >
    <td><?php echo $strClientList['client_name'];?></td>
    <td>XYZ</td>
    <td><?php echo $strClientList['client_type_name'];?></td>
    <td><?php echo $strClientList['software_version_name'];?></td>
    <td><?php echo Globals::DateFormat($strClientList['doc']);?></td>
    <td><a href="<?php echo URL?>customer/?login_id=<?php echo $strClientList['client_id'];?>" target="_blank">Login</a></td>
  </tr>
  <?php
  		}
  	} 
  ?>
</table>

