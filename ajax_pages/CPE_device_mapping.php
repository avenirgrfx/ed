<?php
ob_start();
session_start();
require_once("../configure.php");
require_once(AbsPath . "classes/all.php");
require_once(AbsPath . "classes/widget_category.class.php");


$DB = new DB;
$WidgetCategory = new WidgetCategory;

if ($_POST['mode'] == "delete_cpe") {

    echo $strSQL = "delete from t_cpe_child_link where cpe_child_id=" . $_POST['child_id'] . " and cpe_child_serial_no='" . $_POST['device_id'] . "' and node_id='" . $_POST['nodeid'] . "' and parent_serial_no='" . $_POST['parent_serial'] . "'";
    $strCategoryArr = $DB->Returns($strSQL);
}

if ($_POST['mode'] == "insert") {
    $cpe_serial_number = $_POST['cpe_serial_number'];
    $CpeType_id = $_POST['CpeType_id'];
    $CpeChild = $_POST['CpeChild'];
    $CpeSerial = $_POST['CpeSerial'];
    $CpeChildName = $_POST['CpeChildName'];
    $CpeTypeName = $_POST['CpeTypeName'];
    $node_id = $_POST['node_id'];
    $CpeDescription = $_POST['Cpe_Description'];

    $strSQL = "Insert into t_cpe_child_link (cpe_parent_id,cpe_child_name,date_of_creation,cpe_child_serial_no,node_id,parent_serial_no) values ($CpeType_id,'$CpeChildName',now(),'$CpeSerial','$node_id','$cpe_serial_number')";
    $strCategoryArr = $DB->Returns($strSQL);
  
    $strSQL = "Insert into t_cpe_link (cpe_name,cpe_serial_no,node_id,cpe_description,doc) values ('$CpeTypeName','$cpe_serial_number','$node_id','$CpeDescription',now()) on duplicate key update cpe_id=cpe_id";
    $strCategoryArr = $DB->Returns($strSQL);
      if ($strCategoryArr == 1) {
       echo "Attachment Added"; 
    }
    
    exit;
}

if ($_POST['mode'] == "update") {
    $DeviceSerialNO = $_POST['EditNodeID'];
    $Node_id = $_POST['strNodeID'];
    $strChildID = $_POST['strChildID'];
    $parent_serial = $_POST['parent_serial'];
    echo $strSQL = "update t_cpe_child_link set cpe_child_serial_no='$DeviceSerialNO' where node_id ='$Node_id' and cpe_child_id='$strChildID' and parent_serial_no='$parent_serial'";
    $strUpdateARR = $DB->Returns($strSQL);
    if ($strUpdateARR == 1) {

        echo "CPE Updated";
    }
    exit;
}

$CpeType = $_POST['CpeType'];
if ($CpeType != "" && $CpeType != 0) {
    $node_id = $_POST['node_id'];
    $CpeType_id = $_POST['CpeType_id'];
    $parent_serial = $_POST['parent_serial'];

    $strSQL = "select * from t_cpe_child_link where node_id='" . $_POST['node_id'] . "' and parent_serial_no='" . $parent_serial . "'";
    $strRsSystemNodeSerialArr = $DB->Returns($strSQL);
    $iCtr = 0;

    if (mysql_num_rows($strRsSystemNodeSerialArr) > 0) {
        ?>
        <br><br>
        <div style="margin-top:15px;margin-bottom:15px;"><b style="font-size:14px;">Available Linked Attachments</b></div>
        <table id="NodeListTable" width="100%" height="170px" border="0" cellspacing="1" cellpadding="2" style="font-size:12px;">
            <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
                <td width="31%">Type</td>
                <td width="19%">Serial</td>
                <td width="22%">Date Created</td>
                <td width="22%">Action</td>
            </tr>
            <?php
            while ($strRsSystemNodeSerial = mysql_fetch_object($strRsSystemNodeSerialArr)) {
                $iCtr ++;
                if ($iCtr % 2 == 1)
                    $strRowClass = "OddRow";
                else
                    $strRowClass = "EvenRow";
                ?>
            <tr class="<?php echo $strRowClass; ?>">
                <td>
                    <br />
                    <div style="float:left; font-style:italic;" id="CustomName_<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>"><?php echo $strRsSystemNodeSerial->cpe_child_name; ?></div>
                    <div class="clear"></div>
                </td>
                <td><div id="CustomID_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>"><?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?></div></td>
                <td><?php echo Globals::DateFormat($strRsSystemNodeSerial->date_of_creation, 1); ?></td>
                <td><div id="CustomID_Control_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no; ?>" style="float:left;padding: 0 4px;"><a href="javascript:EditNodeCPE('<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>','<?php echo $node_id ?>','<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>','<?= $parent_serial ?>')" style="padding-left:4px;">Edit</a></div><a href="javascript:DeleteNodeCpe('<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>','<?php echo $node_id ?>','<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>','<?= $parent_serial ?>')" style="padding-left:4px;">Delete</a></td>
            </tr>
        <?php } ?>
        </table>
        <?php }
    }
    ?>
         <?php
        if (!empty($_POST['Version'])){
    $node_id = $_POST['node_id'];
    $systemID = $_POST['systemID'];
    $version = $_POST['Version'];
    if($version==1){
          $strCPEChildSQL = "Select * from t_cpe_child where cpe_level=0";
           
    }
    else {
          $strCPEChildSQL = "Select * from t_cpe_child where cpe_level=0 and version=0";
    
         
    }
   
    $strCpeChildTypeArr = $DB->Returns($strCPEChildSQL);
    ?>
         <select id="CpeChild" name="CpeChild" onchange="showTable()">    	
                <option value="0">Add Attachments<?=$version?></option>
                <?php
                while ($strCpeChildType = mysql_fetch_object($strCpeChildTypeArr)) {
                    print '<option value="' . $strCpeChildType->cpe_serial_no . '">' . $strCpeChildType->cpe_name . '</option>';
                }
                ?>
        </select><?php
        
                }?>
<?php
if ($_POST['mode'] == "showCPEDetails") {
    $CPEID = $_POST['CPEID'];
    ?>
    <div class="Cpe-outerContainer" style="top:50px; height: 400px; width:680px;margin: 0 0 0 260px;">
        <a class="R-close" onclick="CpeClose()"style="cursor:pointer"></a>
        <div><h2>Add New CPE</h2></div> 
        <div style="float: left;">
            <input type="text" readonly="readonly" node_serial='<?php echo $node_id ?>' name="txtcpe_serial_number" id="cpe_serial_number" value="<?php echo $CPEID; ?>" />
        </div>
        <div class="clear"></div>
        <?php 
        $strSQL = "select * from t_cpe_link where delete_flag = 0 and node_id='" . $_POST['node_id'] . "' and cpe_serial_no='" . $CPEID . "'";
        $strRsDescArr = $DB->Returns($strSQL);
        $iCtr = 0;
        if (mysql_num_rows($strRsDescArr) > 0) {
               while ($strRsDesc = mysql_fetch_object($strRsDescArr)) {
                   
        ?>
        <br><br>
        <div style="float:left;margin-top:11px">
            <h6>Description: <?= $strRsDesc->cpe_description;?></h6>
        </div>
        <?php }}?>
        <?php
        $strSQL = "select * from t_cpe_child_link where node_id='" . $_POST['node_id'] . "' and parent_serial_no='" . $CPEID . "'";
        $strRsSystemNodeSerialArr = $DB->Returns($strSQL);
        $iCtr = 0;

        if (mysql_num_rows($strRsSystemNodeSerialArr) > 0) {
            ?>
                 <div class="clear"></div>
            <br><br>
            <div style="margin-top:15px;margin-bottom:15px;"><b style="font-size:14px;">Available Linked Attachments</b></div>
            <table id="NodeListTable" width="100%" height="170px" border="0" cellspacing="1" cellpadding="2" style="font-size:12px;">
                <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
                    <td width="31%">Type</td>
                    <td width="19%">Serial</td>
                    <td width="22%">Date Created</td>
        <!--                    <td width="22%">Action</td>-->
                </tr>
                <?php
                while ($strRsSystemNodeSerial = mysql_fetch_object($strRsSystemNodeSerialArr)) {
                    $iCtr ++;
                    if ($iCtr % 2 == 1)
                        $strRowClass = "OddRow";
                    else
                        $strRowClass = "EvenRow";
                    ?>
                <tr class="<?php echo $strRowClass; ?>">
                    <td>
                        <br />
                        <div style="float:left; font-style:italic;" id="CustomName_<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>"><?php echo $strRsSystemNodeSerial->cpe_child_name; ?></div>
                        <div class="clear"></div>
                    </td>
                    <td><div id="CustomID_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>"><?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?></div></td>
                    <td><?php echo Globals::DateFormat($strRsSystemNodeSerial->date_of_creation, 1); ?></td>
            <!--                        <td><div id="CustomID_Control_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no; ?>" style="float:left;padding: 0 4px;"><a href="javascript:EditNodeCPE('<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>','<?php echo $node_id ?>','<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>','<?= $parent_serial ?>')" style="padding-left:4px;">Edit</a></div><a href="javascript:DeleteNodeCpe('<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>','<?php echo $node_id ?>','<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>','<?= $parent_serial ?>')" style="padding-left:4px;">Delete</a></td>-->
                </tr>
                <?php }
            } ?>
        </table>


        <div class="clear"></div>




    </div>
<?php } ?>

<?php
if ($_POST['mode'] == "delete_entire_cpe") {

    //$sql = "delete from t_cpe_link where cpe_serial_no= '" . $_POST['CPEID'] . "' and node_id= '" . $_POST['node_id'] . "'";
    $sql = "update t_cpe_link set delete_flag=1 where cpe_serial_no= '" . $_POST['CPEID'] . "' and node_id= '" . $_POST['node_id'] . "'";
    $resultArr = $DB->Returns($sql);
    $strSQL = "delete from t_cpe_child_link where node_id= '" . $_POST['node_id'] . "' and parent_serial_no= '" . $_POST['CPEID'] . "'";
    $resultARR = $DB->Returns($strSQL);
}
?>



<?php
if ($_POST['Link'] == "link") {
    $node_id = $_POST['node_id'];
    $systemID = $_POST['systemID'];


    $strSQLCPEL1Type = "select * from t_cpe_child where cpe_level=1";
    $strCpeTypeArr = $DB->Returns($strSQLCPEL1Type);

   //echo $strSerialNumSQL = "Select count(*) as SerialNumbers from t_cpe_link where DATE_FORMAT(doc,'%y%m%d')=".date(ymd);
    $strSerialNumSQL = "Select count(*) as SerialNumbers from t_cpe_link";
    $strRsNodeSerialArr = $DB->Returns($strSerialNumSQL);

    // THN150001A to THN159999A then THN150001B and so on
    if ($strRsNodeSerial = mysql_fetch_object($strRsNodeSerialArr)) {
        $AvailableCount = $strRsNodeSerial->SerialNumbers;
        $AvailableCount++;

        if ($AvailableCount >= 0 and $AvailableCount < 10)
            $SerialNumberCpe = 'CPE' . date(mdy) . "A" . '00' . $AvailableCount;
        elseif ($AvailableCount >= 10 and $AvailableCount < 100)
            $SerialNumberCpe = 'CPE' . date(mdy) . "A" . '0' . $AvailableCount;
        elseif ($AvailableCount >= 100 and $AvailableCount < 1000)
            $SerialNumberCpe = 'CPE' . date(mdy) . "A"  . $AvailableCount;
//        elseif ($AvailableCount >= 1000 and $AvailableCount < 10000)
//            $SerialNumberCpe = 'CPE' . date(mdy) . "A" . $AvailableCount;
    }
    ?>

    <div class="Cpe-outerContainer" style="top:50px; height: 400px; width:680px;margin: 0 0 0 260px;">
        <a class="R-close" onclick="CpeClose()"style="cursor:pointer"></a>
        <div><h2>Add New CPE</h2></div> 
        <div style="float: left;">
            <input type="text" readonly="readonly" node_serial='<?php echo $node_id ?>' name="txtcpe_serial_number" id="cpe_serial_number" value="<?php echo $SerialNumberCpe; ?>" />
        </div>
        <div style="float: left;margin-top: 10px;">
            <select id="cpetype" onchange="showTable()">    	
                <option value="0">Attachment Type</option>
                <?php
                while ($strCpeType = mysql_fetch_object($strCpeTypeArr)) {
                    print '<option value="' . $strCpeType->cpe_id . '"'.'version="'.$strCpeType->version.'">' . $strCpeType->cpe_name . '</option>';
                }
                ?>
            </select>
            <div style="float:right;padding-left: 25px;">
                <input type="text" name="cpe_Description_name" id="cpe_Description_name" placeholder="CPE Description"/> 
            </div>
        </div>
        <div id="CpeChildDropdown"style="float:left;margin-top: 10px; display:none;">
           
            <span id="CpeSerialInputBox" style="display:none;">
                <input  style="margin-left: 20px;" type="text" id="CpeSerial" name="CpeSerial" placeholder="Attachment Serial NO"  style="width:130px;"/>
            </span>
        </div>
        <div style="margin-top:10px;float:left;">
            <input type="button" style="padding: 2px 5px; margin-left:10px;margin-bottom:1px;" value="Add New" name="btnAdd" id="LinkCpeNode" onclick="AddCpe('<?= $systemID ?>')">
        </div>
        <div class="clear"></div>

    <?php ?> 
        <style type="text/css">
            #NodeListTable td
            {
                border:1px solid #EFEFEF;
            }
        </style>
        <div id="tabledata"></div>

        <div style="margin-top:10px;float:right;">
            <input type="button" style="padding: 2px 5px; margin-left:10px;margin-bottom:1px;" value="Create CPE" name="btnCreate" id="CreateCPE" onclick="createCPE('<?= $systemID ?>')">
        </div>   
        <input type="hidden" name="txt_system_id" id="txt_system_id" value="<?php echo $strSystemID ?>" />
    <?php
    print "</div>";
    ?>

    </div>

    <?php
    }?>
    
    <?php if($_POST['mode']=="EditCPE"){
        $CPEID=$_POST['CPEID'];
        $node_id=$_POST['node_id']?>
        <div class="Cpe-outerContainer" style="top:50px; height: 400px; width:680px;margin: 0 0 0 260px;">
        <a class="R-close" onclick="CpeClose()"style="cursor:pointer"></a>
        <div><h2>Edit CPE</h2></div> 
        <div style="float: left;">
            <input type="text" readonly="readonly" node_serial='<?php echo $node_id ?>' name="txtcpe_serial_number" id="cpe_serial_number" value="<?php echo $CPEID; ?>" />
        </div>
        <div class="clear"></div>
        <?php 
        $strSQL = "select * from t_cpe_link where node_id='" . $node_id . "' and cpe_serial_no='" . $CPEID . "'";
        $strRsDescArr = $DB->Returns($strSQL);
        $iCtr = 0;
        if (mysql_num_rows($strRsDescArr) > 0) {
               while ($strRsDesc = mysql_fetch_object($strRsDescArr)) {
                   
        ?>
        <br><br>
        <div style="float:left;margin-top:11px">
            <h6>Description: <?= $strRsDesc->cpe_description;?></h6>
        </div>
        <?php }}?>
        <?php
        $strSQL = "select * from t_cpe_child_link where node_id='" . $node_id . "' and parent_serial_no='" . $CPEID . "'";
        $strRsSystemNodeSerialArr = $DB->Returns($strSQL);
        $iCtr = 0;

        if (mysql_num_rows($strRsSystemNodeSerialArr) > 0) {
            ?>
                 <div class="clear"></div>
            <br><br>
            <div style="margin-top:15px;margin-bottom:15px;"><b style="font-size:14px;">Available Linked Attachments</b></div>
            <table id="NodeListTable" width="100%" height="170px" border="0" cellspacing="1" cellpadding="2" style="font-size:12px;">
                <tr style="font-weight:bold; background-color:#CCCCCC; border:1px solid #EFEFEF;">
                    <td width="31%">Type</td>
                    <td width="19%">Serial</td>
                    <td width="22%">Date Created</td>
                    <td width="28">Action</td>
       
                </tr>
                <?php
                while ($strRsSystemNodeSerial = mysql_fetch_object($strRsSystemNodeSerialArr)) {
                    $iCtr ++;
                    if ($iCtr % 2 == 1)
                        $strRowClass = "OddRow";
                    else
                        $strRowClass = "EvenRow";
                    ?>
                <tr class="<?php echo $strRowClass; ?>">
                    <td>
                        <br />
                        <div style="float:left; font-style:italic;" id="CustomName_<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>"><?php echo $strRsSystemNodeSerial->cpe_child_name; ?></div>
                        <div class="clear"></div>
                    </td>
                    <td>
                        <div id="CustomID_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>">
                         <input style="width:150px;" type="text" name="CustomID_Edit_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>" id="CustomID_Edit_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>" value="<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>" />
                        </div>
                    </td>
                        
                    <td><?php echo Globals::DateFormat($strRsSystemNodeSerial->date_of_creation, 1); ?></td>
                    <td>
                      <div id=CustomID_Control_<?php echo $strRsSystemNodeSerial->cpe_child_serial_no; ?>"  style="float:left;padding: 0 4px;"><a href="javascript:EditNodeCPECustomID_Update('<?php echo $strRsSystemNodeSerial->cpe_child_serial_no; ?>','<?php echo $node_id ?>','<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>','<?php echo $CPEID?>')">Update</a>|<a href="javascript:DeleteNodeCpe('<?php echo $strRsSystemNodeSerial->cpe_child_serial_no ?>','<?php echo $node_id ?>','<?php echo $strRsSystemNodeSerial->cpe_child_id; ?>','<?= $CPEID ?>')" style="padding-left:4px;">Delete</a></div>
                    </td>
                </tr>
                
                <?php }
            } ?>
        </table>


        <div class="clear"></div>


    <?php }?>
       