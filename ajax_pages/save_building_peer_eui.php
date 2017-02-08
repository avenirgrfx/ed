<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");
require_once(AbsPath."classes/system.class.php");
require_once(AbsPath."classes/projects.class.php");

$DB=new DB;

$building_id = $_POST['building_id']; 
$strPeer = $_POST['peer'];
$strEui = $_POST['eui'];

$strSQL = "insert into t_building_benchmark (building_id, building_PEER_value, building_EUI_value) values('$building_id', '$strPeer', '$strEui') ON DUPLICATE KEY UPDATE building_PEER_value = values(building_PEER_value), building_EUI_value = values(building_EUI_value)";
$DB->Returns($strSQL);
?>
<div style="float: left;">
    <div style='float:left; margin-top:5px; margin-left:50px;'>
        <span style="float:left; margin-top:5px;width:90px;">PEER:</span>
        <input type="text" style="float:left; margin-left:15px;" value="" id="peer_<?=$building_id?>">
    </div>
    <div class='clear'></div>
    <div style='float:left; margin-top:5px; margin-left:50px;'>
        <span style="float:left; margin-top:5px;width:90px;">EUI (kBtu/ft<sup>2</sup>):</span>
        <input type="text" style="float:left; margin-left:15px;" value="" id="eui_<?=$building_id?>">
    </div>
    <div class='clear'></div>
</div>
<div style="float: left; margin-top: 25px;">
    <input type="button" style="float:left; margin-left:30px;" value="SET" name="btnSET" id="btnSET" onclick="set_peer_eui(<?=$building_id?>)">
</div>