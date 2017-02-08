<?php
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
?>
<div style="text-transform:uppercase; padding:3px; font-weight:bold;">
	<div style="float:left;">System Messages</div>
    <div style="float:right;"><img src="<?php echo URL?>images/compose_email.png" title="New Message" alt="New Message" /></div>
    <div class="clear"></div>
</div>
    
<div style="background-color:#999999; color:#FFFFFF; padding:3px;">
    <div style="float:left; width:20px;"><input type="checkbox" name="" id="" value="1" /></div>
    <div style="float:left; width:150px; font-weight:bold;">From</div>
    <div style="float:left; width:230px; font-weight:bold;">Subject</div>
    <div style="float:left; width:80px; font-weight:bold;">Date</div>
    <div style="float:left; width:70px; font-weight:bold;">Action</div>
    <div class="clear"></div>
</div>
    
<div class="myscroll" style="height:250px; overflow-y:scroll;">   
    
    <div style="background-color:#EFEFEF; color:#999999; padding:3px;">
        <div style="text-align: center;">There is no available message</div>
    </div>
    
</div>