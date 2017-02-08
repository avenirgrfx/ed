<?php
require_once('../configure.php');
require_once(AbsPath.'classes/all.php');

$DB=new DB;
?>
<link rel="stylesheet" type="text/css" href="<?php echo URL?>css/master.css">


<div id="Customer_Menu_Section" style="margin:0px;">
    <div class="TopMenu_Customer TopMenu_Customer_active" style="font-size:14px; padding:10px;" id="">New Account</div>
    <div class="TopMenu_Customer" style="font-size:14px; padding:10px;">Update Electricity</div>
    <div class="TopMenu_Customer" style="font-size:14px; padding:10px;" id="">Update Natural Gas</div>
    <div class="clear"></div>
</div>

<div style="background-color:#FFFFFF;">
	
    <div style="padding:10px;">
    	<div style="float:left; width:150px; font-size:16px;">ADD NEW ACCOUNT</div>
        <div style="float:left;">
        	<select name="" id="" style="font-size:16px; width:300px; font-weight:bold; color:#666666; font-family: UsEnergyEngineers;">
            	<option value="">ELECTRICITY</option>
                <option value="">NATURAL GAS</option>
            </select>
         </div>        
        <div class="clear" style="height:10px;"></div>
        
        <div style="float:left; width:150px; font-size:16px;">
        	UTILITY NAME
        </div>
        <div style="float:left;">
        	<input type="text" name="" id=""  value="" style="width:300px;">
        </div>        
        <div class="clear" style="height:10px;"></div>
        
        <div style="float:left; width:150px; font-size:16px;">
        	ACCOUNT #
        </div>
        <div style="float:left;">
        	<input type="text" name="" id=""  value="" style="width:300px;">
        </div>        
        <div class="clear" style="height:10px;"></div>
        
        <div style="float:left; width:150px; font-size:16px;">
        	ELECTRIC METER 1
        </div>
        <div style="float:left;">
        	<input type="text" name="" id=""  value="" style="width:300px;">
        </div>
        <div style="float:right; text-align:center; cursor:pointer; width:50px; background-color:#CCCCCC; border-radius:3px; font-weight:bold;">Add</div>
        <div class="clear" style="height:10px;"></div>
        
        <div style="background-color:#CCCCCC; padding:3px 6px; text-transform:uppercase; border-radius:5px; cursor:pointer; border:1px solid #666666; width:120px; font-weight:bold; text-align:center; float:right;">Create Account</div>
        <div class="clear" style="height:10px; border-bottom:1px solid #cccccc;"></div>
        
        <div style="font-size:16px; text-decoration:underline; font-weight:bold;">EXISTING ELECTRIC ACCOUNTS</div>
        
        <div style="float:left; width:50%;">
       		ACCOUNT 1
        </div>
    </div>
    

</div>


