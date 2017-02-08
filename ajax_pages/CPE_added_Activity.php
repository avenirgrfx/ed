<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath."classes/all.php");

$DB=new DB;

if($_POST['mode']=="CPEDetails"){
    $node_id=$_POST['NodeID'];
    $strSQL="select * from t_cpe_link where node_id='".$node_id."'";
    $strRsParentIDsArr = $DB->Returns($strSQL);

 while ($strRsParentIDs = mysql_fetch_object($strRsParentIDsArr)) {
   echo  "<div style='margin-left:69px;'><span id='Node_Room_Plus_Minus_$strRsParentIDs->cpe_serial_no'>+</span><a href='javascript:VeiwActiveCPEDetails(".'"'.$node_id.'","'.$strRsParentIDs->cpe_serial_no.'"'.")' style=' outline: 0;'>".$strRsParentIDs->cpe_name. "--".$strRsParentIDs->cpe_serial_no."</a></div>"; 
   echo '<div id="ActiveCPE_'.$strRsParentIDs->cpe_serial_no.'" style="display:none;"></div>';
   }
}

if ($_POST['mode'] == "ActiveshowCPEDetails") {
    $CPEID = $_POST['cpe_id'];
    $node_id=$_POST['node_id'];
    ?>
    
        <?php 
        $strSQL = "select * from t_cpe_link where node_id='" .$node_id . "' and cpe_serial_no='" . $CPEID . "'";
        $strRsDescArr = $DB->Returns($strSQL);
        $iCtr = 0;
        $strSQL = "select * from t_cpe_child_link where node_id='" . $node_id . "' and parent_serial_no='" . $CPEID . "'";
        $strRsSystemNodeSerialArr = $DB->Returns($strSQL);
        $strRsSystemCPEArr = $DB->Returns($strSQL);
        $iCtr = 0;

        if (mysql_num_rows($strRsSystemNodeSerialArr) > 0) {
            ?>
                 <div class="clear"></div>
            
            <div style="margin-top:1px;margin-bottom:15px;margin-left:72px;"><b style="font-size:14px;">Available Linked Attachments</b></div>
            <table id="NodeListTable" width="70%" height="170px" border="0" cellspacing="1" cellpadding="2" style="font-size:12px;margin-left:72px;">
                <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
                    <td width="25%">Linked CPE</td>
                    <td width="14%">Serial</td>
                    <td width="10%">status</td>
                    <td width="20%">Data Received</td>
                    <td width="31%"><td>     
                </tr>
                <?php
                while ($strRsDesc = mysql_fetch_object($strRsDescArr)) {?>
                <tr> 
                    <td><u><b>CPE Type : <?= $strRsDesc->cpe_name?></b></u></td>
            <td><b><u><?= $strRsDesc->cpe_serial_no?></u></b></td>
                    <td>Active</td>
                    <td></td>
                    <td><input type="button" name="Restart" value="Restart" id="RestartCPE" style="width:70px;background-color:#CCCCCC;border-radius: 6px;"><td>
                </tr>
                <?php while ($strRsSystemNodeSerial = mysql_fetch_object($strRsSystemNodeSerialArr)) { ?>
                <tr>
                    <td>
                        
                        <div style="float:left; font-style:italic;" id="CustomName_<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>">Attached :<?php echo $strRsSystemNodeSerial->cpe_child_name; ?></div>
                        <div class="clear"></div>
                    </td>
                    <td><div id="CustomID_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>"><?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?></div></td>
                    <td>Active</td>
                    <td><?php echo Globals::DateFormat($strRsSystemNodeSerial->date_of_creation, 1); ?></td>
                </tr>
                 
                <?php } ?>
                 <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
                    <td width="25%">Communication</td>
                    <td width="14%">Mac</td>
                    <td width="10%">status</td>
                    <td width="20%">Data Sent</td>
                    <td width="31%"><td>     
                 </tr>
                <tr>
                    <td>energyDAS Router</td>
                    <td>60:AC:97:97:56:7D</td>
                    <td>Active</td>
                    <td>1/28/2016 04:44</td>
                    <td><input type="button" name="RestartRouter" value="Restart" id="RestartRouter" style="width:70px;background-color:#CCCCCC;border-radius: 6px;"><td>
                </tr>
                
                <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
                    <td   width="40%">Data</td>
                    <td  colspan="5" width="70%">Value</td>
                </tr>
                <tr>
                    <td><u><b><?= $strRsDesc->cpe_name."-".$strRsDesc->cpe_serial_no?></b></u></td>
                </tr>
                <tr>
                      
                    <td>
                        <table style="margin-left: 0;">
                             <?php 
                while ($strRsSystemNodeSerial = mysql_fetch_object($strRsSystemCPEArr)) {
                                       
                    ?>
                <tr>
                    <td>
                     
                        <div style="float:left; font-style:italic;" id="CustomName_<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>">
                            <span id="Arrow-image"></span>
                            
                            <span id="cpe_child_name" style="text-align:center;">Attached :<?php echo $strRsSystemNodeSerial->cpe_child_name; ?></span>
                        </div>
                        <div class="clear"></div>
                    </td>
                </tr>
                 
                <?php } ?>
                        </table>
                    </td>
                    <td colspan="5">
                        <table border="1" height="120%" width="100%">
                            <tr>
                                <td>SYSKWH1567</td>
                                <td>LEG1KWH13.4</td>
                                <td>LEG2KWH34.5</td>
                                <td>LEG3KWH56.7</td>
                            </tr>
                            <tr>
                                <td>MOD 1234</td>
                                <td>kl34 873Gh</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table> 
                    </td>
                </tr> 
            
           <?php }} ?>
        </table>
            <br><br>
<?php } ?>
