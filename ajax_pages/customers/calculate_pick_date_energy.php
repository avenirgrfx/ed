<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
//print_r($_GET);
$type=Globals::Get('type');


$strDate=Globals::Get('date');
$strDateArr=explode("/",$strDate);
if(is_array($strDateArr) && count($strDateArr)>0)
{
	if($type==1)
	{
		$timestamp=$strDateArr[2]."-".$strDateArr[0]."-".$strDateArr[1];
		$strPick_1_Month= date("F",strtotime($timestamp));
		$strPick_1_Year= date("Y",strtotime($timestamp));
		$strPick_1_Days= date("d",strtotime($timestamp));
	}
	elseif($type==2)
	{
		$timestamp=$strDateArr[2]."-".$strDateArr[0]."-".$strDateArr[1];
		$strPick_2_Month= date("F",strtotime($timestamp));
		$strPick_2_Year= date("Y",strtotime($timestamp));
		$strPick_2_Days= date("d",strtotime($timestamp));
	}
}
?>

<?php if($type==1){?>
<div style="float:left; text-align:right; width:55%; font-size:15px;"><?php echo $strPick_1_Month;?> <?php echo $strPick_1_Year;?> Consumption</div>
<div style="float:right; margin-left:20px;"><?php echo $strPick_1_Days;?> Days</div>
<div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">181,865 kWh</div>
<div class="clear"></div>
<?php }?>

<?php if($type==2){?>
<div style="float:left; text-align:right; width:55%; font-size:15px;"><?php echo $strPick_2_Month;?> <?php echo $strPick_2_Year;?> Consumption</div>
<div style="float:right; margin-left:20px;"><?php echo $strPick_2_Days;?> Days</div>
<div style="float:right; text-align:center; margin-left:5px; width:25%;" class="light_blue_box_for_value">371,865 kWh</div>
<div class="clear"></div>
<?php }?>