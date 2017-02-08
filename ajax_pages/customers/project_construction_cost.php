<a class="close-reveal-modal">&#215;</a>
                                            
<?php
session_start();
require_once('../../configure.php');
require_once(AbsPath."classes/all.php");

$DB=new DB;

$projectID = $_GET['project_id'];
include("connection.php");
$pcb = array();
$pcb[0]["name"]="Energy Analysis"; 
$pcb[0]["data"][0]["name"]="Energy Audit";
$pcb[0]["data"][0]["data"]=array("au_qty:","au_uc:","au_amt: ");
$pcb[0]["data"][1]["name"]="Energy Analysis - Mechanical";
$pcb[0]["data"][1]["data"]=array("eam_qty:","eam_uc:","eam_amt: ");
$pcb[0]["data"][2]["name"]="Energy Analysis - Electrical";
$pcb[0]["data"][2]["data"]=array("ele_qty:","ele_uc:","ele_amt: ");
$pcb[0]["data"][3]["name"]="Energy Analysis - Compressed Air";
$pcb[0]["data"][3]["data"]=array("com_air_qty:","com_air_uc:","com_air_amt: ");		
$pcb[0]["data"][4]["name"]="Energy Analysis - Other";
$pcb[0]["data"][4]["data"]=array("eao_qty:","eao_uc:","eao_amt: ");	
$pcb[0]["data"][5]["name"]="Building Management System";
$pcb[0]["data"][5]["data"]=array("bms_qty:","bms_uc:","bms_amt: ");
$pcb[0]["data"][6]["name"]="Energy Controls/Equipment";
$pcb[0]["data"][6]["data"]=array("ece_qty:","ece_uc:","ece_amt: ");	
$pcb[0]["data"][7]["name"]="Energy Equipment Installation";
$pcb[0]["data"][7]["data"]=array("eei_qty:","eei_uc:","eei_amt: ");
$pcb[0]["data"][8]["name"]="Energy Certifications";
$pcb[0]["data"][8]["data"]=array("ec_qty:","ec_uc:","ec_amt: ");
$pcb[0]["data"][9]["name"]="Incentives Preparation/Reporting";
$pcb[0]["data"][9]["data"]=array("ipr_qty:","ipr_uc:","ipr_amt: ");	
$pcb[0]["data"][10]["name"]="Travel & Accomodation";
$pcb[0]["data"][10]["data"]=array("tna_qty:","tna_uc:","tna_amt: ");
$pcb[0]["data"][11]["name"]="User-defined";
$pcb[0]["data"][11]["data"]=array("ud_qty:","ud_uc:","ud_amt: ");
$pcb[0]["data"][12]["name"]="Contingencies";
$pcb[0]["data"][12]["data"]=array("con_qty:","con_uc:","con_amt: ");
$pcb[0]["data"][13]["name"]="Interest during construction";
$pcb[0]["data"][13]["data"]=array("idc_qty:","idc_uc:","idc_amt: ");	


$pcb[1]["name"]="Feasibility Study"; 
$pcb[1]["data"][0]["name"]="Site investigation";
$pcb[1]["data"][0]["data"]=array("si_qty:","si_uc:","si_amt: ");
$pcb[1]["data"][1]["name"]="Resource assessment";
$pcb[1]["data"][1]["data"]=array("ra_qty:","ra_uc:","ra_amt: ");
$pcb[1]["data"][2]["name"]="Environmental assessment";
$pcb[1]["data"][2]["data"]=array("ea_qty:","ea_uc:","ea_amt: ");
$pcb[1]["data"][3]["name"]="Preliminary design";
$pcb[1]["data"][3]["data"]=array("pd_qty:","pd_uc:","pd_amt: ");
$pcb[1]["data"][4]["name"]="Detailed cost estimate";
$pcb[1]["data"][4]["data"]=array("dce_qty:","dce_uc:","dce_amt: ");
$pcb[1]["data"][5]["name"]="GHG baseline study & MP";
$pcb[1]["data"][5]["data"]=array("ghg_bsm_qty:","ghg_bsm_uc:","ghg_bsm_amt: ");
$pcb[1]["data"][6]["name"]="Report preparation";
$pcb[1]["data"][6]["data"]=array("rp_qty:","rp_uc:","rp_amt: ");
$pcb[1]["data"][7]["name"]="Project management";
$pcb[1]["data"][7]["data"]=array("pm_qty:","pm_uc:","pm_amt: ");
$pcb[1]["data"][8]["name"]="Travel & accommodation";
$pcb[1]["data"][8]["data"]=array("ta_qty:","ta_uc:","ta_amt: ");
$pcb[1]["data"][9]["name"]="User-defined";
$pcb[1]["data"][9]["data"]=array("ud_qty:","ud_uc:","ud_amt: ");

$pcb[2]["name"]="Development"; 
$pcb[2]["data"][0]["name"]="Contract negotiations";
$pcb[2]["data"][0]["data"]=array("cn_qty:","cn_uc:","cn_amt: ");
$pcb[2]["data"][1]["name"]="Permits &amp; approvals";
$pcb[2]["data"][1]["data"]=array("pa_qty:","pa_uc:","pa_amt: ");
$pcb[2]["data"][2]["name"]="Site survey &amp; land rights";
$pcb[2]["data"][2]["data"]=array("ss_lr_qty:","ss_lr_uc:","ss_lr_amt: ");
$pcb[2]["data"][3]["name"]="GHG validation & registration";
$pcb[2]["data"][3]["data"]=array("ghg_vr_qty:","ghg_vr_uc:","ghg_vr_amt: ");
$pcb[2]["data"][4]["name"]="Project financing";
$pcb[2]["data"][4]["data"]=array("pf_qty:","pf_uc:","pf_amt: ");
$pcb[2]["data"][5]["name"]="Legal & accounting";
$pcb[2]["data"][5]["data"]=array("la_qty:","la_uc:","la_amt: ");
$pcb[2]["data"][6]["name"]="Project management";
$pcb[2]["data"][6]["data"]=array("pm_qty:","pm_uc:","pm_amt: ");
$pcb[2]["data"][7]["name"]="Travel & accommodation";
$pcb[2]["data"][7]["data"]=array("ta_qty:","ta_uc:","ta_amt: ");
$pcb[2]["data"][8]["name"]="User-defined";
$pcb[2]["data"][8]["data"]=array("ud_qty:","ud_uc:","ud_amt: ");


$pcb[3]["name"]="Engineering"; 
$pcb[3]["data"][0]["name"]="Site &amp; building design";
$pcb[3]["data"][0]["data"]=array("sbd_qty:","sbd_uc:","sbd_amt: ");
$pcb[3]["data"][1]["name"]="Mechanical design";
$pcb[3]["data"][1]["data"]=array("md_qty:","md_uc:","md_amt: ");
$pcb[3]["data"][2]["name"]="Electrical design";
$pcb[3]["data"][2]["data"]=array("ed_qty:","ed_uc:","ed_amt: ");
$pcb[3]["data"][3]["name"]="Civil design";
$pcb[3]["data"][3]["data"]=array("cd_qty:","cd_uc:","cd_amt: ");
$pcb[3]["data"][4]["name"]="Tenders & contracting";
$pcb[3]["data"][4]["data"]=array("tc_qty:","tc_uc:","tc_amt: ");
$pcb[3]["data"][5]["name"]="Construction supervision";
$pcb[3]["data"][5]["data"]=array("cs_qty:","cs_uc:","cs_amt: ");
$pcb[3]["data"][6]["name"]="User-defined";
$pcb[3]["data"][6]["data"]=array("ud_qty:","ud_uc:","ud_amt: ");
$pcb2 = $pcb;
$pcb3 = $pcb;
//echo "<pre>";
//var_dump($pcb);
//echo "</pre>";
$pcb_result= mysql_query("select * from ed_project_construction_budget where project_id = $projectID and cost_type='initial'");
$pcb_rs=mysql_fetch_array($pcb_result);
$pcbdata = json_decode($pcb_rs["data"],true);

$pcb_result2= mysql_query("select * from ed_project_construction_budget where project_id = $projectID and cost_type='annual'");
$pcb_rs2=mysql_fetch_array($pcb_result2);
$pcbdata2 = json_decode($pcb_rs2["data"],true);
$pcb_result3= mysql_query("select * from ed_project_construction_budget where project_id = $projectID and cost_type='periodic'");
$pcb_rs3=mysql_fetch_array($pcb_result3);
$pcbdata3 = json_decode($pcb_rs3["data"],true);


    $pcbinitial=$pcbdata;
    $pcbannual=$pcbdata2;
    $pcbperiodic=$pcbdata3;
    //$pcbannual=$pcbdata[1];
    //$pcbperiodic=$pcbdata[1];
   for($i=0;$i<count($pcbinitial);$i++ )
   {
   $pcbdetails2 = $pcbinitial[$i]["data"];
    for($j=0;$j<count($pcbdetails2);$j++ )
    {
        $pcbdetails3 = $pcbinitial[$i]["data"][$j]["data"];
        for($k=0;$k<count($pcbdetails3);$k++)
        {
        
          $pcb[$i]["data"][$j]["data"][$k]= $pcbinitial[$i]["data"][$j]["data"][$k];
          $pcb2[$i]["data"][$j]["data"][$k]= $pcbannual[$i]["data"][$j]["data"][$k];
          $pcb3[$i]["data"][$j]["data"][$k]= $pcbperiodic[$i]["data"][$j]["data"][$k];
          
          //$pcbannual[$i]["data"][$j]["data"][$k]= $pcbannual[$i]["data"][$j]["data"][$k].":".$_POST["an_ea_".$pcbannual[$i]["data"][$j]["data"][$k]];
          // $pcbperiodic[$i]["data"][$j]["data"][$k]= $pcbperiodic[$i]["data"][$j]["data"][$k].":".$_POST["pe_ea_".$pcbperiodic[$i]["data"][$j]["data"][$k]];
        
        }
    }
   }

         ?>                                      
                                            
                                            <form action="<?php echo URL; ?>ajax_pages/customers/add_onetime_invest_outlays.php" method="post" id="pcb_form" name="pcb_form" onkeypress="return isNumberKey(event)">
                                                <input type="hidden" name="rtype" id="rtype" value="addpcb">
                                                        
                                             	<fieldset>
                                                
                                                
                                            
                                        <!-- Accordion -->
                                        
                                        <h2 class="demoHeaders" style="color: #666666;">PROJECT CONSTRUCTION BUDGET</h2>
         
                                        <div id="accordion">
                                        
  <!-- first tab -->
   <div>
         <h3><a href="javascript:void(0)"><img src="http://localhost:88/EnergyDAS-/images/accordian.jpg" alt="" style="width: 98%;" /></a></h3>
         <div class="myscroll" id="Container_SystemsByBuilding1" style=" padding-top:3px; max-height:270px;">
                                                         
                                                         
<?php 
$expand_id = 296;$fieldnames= array();$totalpcbcost=0;$totall =0;
foreach($pcb as $pcbkey=>$pcbe)
{
if($pcbkey == 0)
{
$suf ="ea";
}
elseif($pcbkey == 1)
{
$suf ="fs";
}
elseif($pcbkey == 2)
{
$suf ="de";
}
elseif($pcbkey == 3)
{
$suf ="en";
}
?>
    <div onclick="Expand_Collapse_System_Node_For_Building(<?php echo $expand_id; ?>)" style="margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold;">
        <span class="System_ID_Expand_<?php echo $expand_id; ?>">+</span><?php echo $pcbe["name"]; ?>
    </div>
    <div class="System_ID_<?php echo $expand_id; ?>" style="margin-left:30px; display:none; color:#0088cc;">
        <div class="table-took">
        <!-- ***********************  Energy Analysis ************************ -->
        <?php $subtotall =0;
        foreach($pcbe["data"] as $pcbd){
        ?>
            <ul>
                <li><?php echo $pcbd["name"]; ?></li>
                <li class="lite-yellow">Per Project</li>
                <?php
                foreach($pcbd["data"] as $key=>$pcbdd){
                if($key == 0) $summ =1;
                $pcbdda= explode(":",$pcbdd);
                if($key != 2) $summ *=$pcbdda[1];
                $fieldnames[]= "in_ea_".$pcbdd;
               
                ?>
                <li><?php if($key != 0){?><span>$</span><?php } ?><input  type="text" name="in_<?php echo $suf; ?>_<?php echo $pcbdda[0]; ?>" id="in_<?php echo $suf; ?>_<?php echo $pcbdda[0] ?>" <?php if($key == 2){echo " readonly ";} ?> value="<?php if($key == 0){echo $pcbdda[1];}elseif($key ==2){$subtotall +=$summ;echo $summ;}else{echo $pcbdda[1];}?>" /></li>
                <?php }?>
            </ul>
        <?php }?>
        <ul>
                                <li>Subtotal</li>
                                        <li class="white-bg">&nbsp;</li>           
                                        <li class="white-bg"></li>
                                        <li class="white-bg"></li>
                                        <li class="bt2"><span>$</span><input readonly  type="text" name="in_<?php echo $suf; ?>_subt_amt" id="in_<?php echo $suf; ?>_subt_amt"  value="<?php echo $subtotall; $totall +=$subtotall;?>" /></li>                                                                                                    
                                </ul>                                                    
                                                                                    
                                                                                                                                      
                                                                                   
        </div>
    </div>
    <?php $expand_id++; }?>
    <div class="table-took total-cost mt10">
                        <ul>
                                <li>Total initial costs</li>
                                <li class="white-bg">&nbsp;</li>
                                <li class="white-bg">&nbsp;</li>
                                <li class="white-bg">&nbsp;</li>
                                <li><span>$</span><input type="text" readonly name="in_tc_amt" id="in_tc_amt"  value="<?php echo $totall;$totalpcbcost +=$totall; ?>" /></li>                                                                                            
                                    
                            </ul>
        </div>  
     </div>            
</div>
                                            
 <!-- second tab -->
  <div>
         <h3><a href="javascript:void(0)"><img src="http://localhost:88/EnergyDAS-/images/annual.jpg" alt="" style="width: 98%;" /></a></h3>
         <div class="myscroll" id="Container_SystemsByBuilding2" style=" padding-top:3px; max-height:270px;">		
 <?php $expand_id = 300;$fieldnames= array();$totall =0;
foreach($pcb2 as $pcbkey2=>$pcbe)
{
if($pcbkey2 == 0)
{
$suf ="ea";
}
elseif($pcbkey2 == 1)
{
$suf ="fs";
}
elseif($pcbkey2 == 2)
{
$suf ="de";
}
elseif($pcbkey2 == 3)
{
$suf ="en";
}
?>
    <div onclick="Expand_Collapse_System_Node_For_Building(<?php echo $expand_id; ?>)" style="margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold;">
        <span class="System_ID_Expand_<?php echo $expand_id; ?>">+</span><?php echo $pcbe["name"]; ?>
    </div>
    <div class="System_ID_<?php echo $expand_id; ?>" style="margin-left:30px; display:none; color:#0088cc;">
        <div class="table-took">
        <!-- ***********************  Energy Analysis ************************ -->
        <?php $subtotall =0;
        foreach($pcbe["data"] as $pcbd){ 
        ?>
            <ul>
                <li><?php echo $pcbd["name"]; ?></li>
                <li class="lite-yellow">Per Project</li>
                <?php
                foreach($pcbd["data"] as $key=>$pcbdd){
                if($key == 0) $summ =1;
                $pcbdda= explode(":",$pcbdd);
                if($key != 2) $summ *=$pcbdda[1];
                $fieldnames[]= "in_ea_".$pcbdd;
                ?>
                <li><?php if($key != 0){?><span>$</span><?php } ?><input type="text" name="an_<?php echo $suf; ?>_<?php echo $pcbdda[0]; ?>" id="an_<?php echo $suf; ?>_<?php echo $pcbdda[0] ?>" <?php if($key == 2){echo " readonly ";} ?> value="<?php if($key == 0){echo $pcbdda[1];}elseif($key ==2){ $subtotall +=$summ;echo $summ;}else{echo $pcbdda[1];}?>" /></li>
                <?php }?>
            </ul>
        <?php }?>
                                                                                    <ul>
                                                                                        <li>Subtotal</li>
                                                                                        <li class="white-bg">&nbsp;</li>           
                                                                                        <li class="white-bg"></li>
                                                                                        <li class="white-bg"></li>
                                                                                        <li class="bt2"><span>$</span><input readonly type="text" name="an_<?php echo $suf; ?>_subt_amt" id="an_<?php echo $suf; ?>_subt_amt"  value="<?php echo $subtotall;$totall +=$subtotall; ?>" /></li>                                                                                                    
                                                                                    </ul>                                                    
                                                                                    
                                                                                                                                      
                                                                                    
        
        </div>
    </div>
    <?php $expand_id++; }?>
                                                                        <div class="table-took total-cost mt10">
                                                                                    <ul>
                                                                                        <li>Total Annual costs</li>
                                                                                        <li class="white-bg">&nbsp;</li>
                                                                                        <li class="white-bg"></li>
                                                                                        <li class="white-bg"></li>
                                                                                        <li><span>$</span><input type="text" readonly name="an_tc_amt" id="an_tc_amt"  value="<?php echo $totall;$totalpcbcost +=$totall; ?>" /></li>                                                                                            
                                                                                            
                                                                                    </ul>
                                                                        </div>
                                                                      </div>  
                                
                                            </div>
                                            
  <!-- third tab -->
     <div>
          <h3><a href="javascript:void(0)"><img src="http://localhost:88/EnergyDAS-/images/periodic.jpg" alt="" style="width: 98%;" /></a></h3>
          <div class="myscroll" id="Container_SystemsByBuilding3" style=" padding-top:3px; max-height:270px;">	
          <?php $expand_id = 304;$fieldnames= array();$totall=0;
foreach($pcb3 as $pcbkey3=>$pcbe)
{
if($pcbkey3 == 0)
{
$suf ="ea";
}
elseif($pcbkey3 == 1)
{
$suf ="fs";
}
elseif($pcbkey3 == 2)
{
$suf ="de";
}
elseif($pcbkey3 == 3)
{
$suf ="en";
}
?>
    <div onclick="Expand_Collapse_System_Node_For_Building(<?php echo $expand_id; ?>)" style="margin-left:15px; cursor:pointer; color:#0088cc; font-weight:bold;">
        <span class="System_ID_Expand_<?php echo $expand_id; ?>">+</span><?php echo $pcbe["name"]; ?>
    </div>
    <div class="System_ID_<?php echo $expand_id; ?>" style="margin-left:30px; display:none; color:#0088cc;">
        <div class="table-took">
        <!-- ***********************  Energy Analysis ************************ -->
        <?php $subtotall =0;
        foreach($pcbe["data"] as $pcbd){ 
        ?>
            <ul>
                <li><?php echo $pcbd["name"]; ?></li>
                <li class="lite-yellow">Per Project</li>
                <?php
                foreach($pcbd["data"] as $key=>$pcbdd){
                if($key == 0) $summ =1;
                $pcbdda= explode(":",$pcbdd);
                if($key != 2) $summ *=$pcbdda[1];
                $fieldnames[]= "in_ea_".$pcbdd;
                ?>
<li><?php if($key != 0){?><span>$</span><?php } ?><input type="text" name="pe_<?php echo $suf; ?>_<?php echo $pcbdda[0]; ?>" id="pe_<?php echo $suf; ?>_<?php echo $pcbdda[0] ?>" <?php if($key == 2){echo " readonly ";} ?> value="<?php if($key == 0){echo $pcbdda[1];}elseif($key ==2){ $subtotall +=$summ;echo $summ;}else{echo $pcbdda[1];}?>" /></li>
                <?php }?>
            </ul>
        <?php }?>
         <ul>
                                                                                        <li>Subtotal</li>
                                                                                        <li class="white-bg">&nbsp;</li>           
                                                                                        <li class="white-bg"></li>
                                                                                        <li class="white-bg"></li>
                                                <li class="bt2"><span>$</span><input readonly type="text" name="pe_<?php echo $suf; ?>_subt_amt" id="pe_<?php echo $suf; ?>_subt_amt"  value="<?php echo $subtotall;$totall +=$subtotall; ?>" /></li>                                                                                                    
                                                                                    </ul>                                                    
                                                                                    
                                                                                                                                      
                                                                                   
        
        </div>
    </div>
    <?php $expand_id++; }?>
     <div class="table-took total-cost mt10">
                                                                                    <ul>
                                                                                        <li>Total Periodic costs</li>
                                                                                        <li class="white-bg">&nbsp;</li>
                                                                                        <li class="white-bg"></li>
                                                                                        <li class="white-bg"></li>
                                                                                        <li><span>$</span><input type="text" readonly name="pe_tc_amt" id="pe_tc_amt"  value="<?php echo $totall;$totalpcbcost +=$totall; ?>" /></li>                                                                                            
                                                                                            
                                                                                    </ul>
                                                                                </div>
     </div>
</div>
                                            
                                        </div>
                    
        
                                                        
 									<div class="pl56 mt10">
                                    <h2 class="budget">CONSTRUCTION BUDGET</h2>
                                    <input class="output-box" type="text" name="budget" id="budget"  value="$<?php echo $totalpcbcost; ?>" />
                                    <div class="clearfix"></div>
                                    <!--<button style="margin: 10px 10px 10px 0px; padding: 8px;" class="grey-btn">Submit</button>-->
                                    <input type="hidden" name="project_id" id="project_id" value="<?php echo $projectID ?>" />
                                    <input type="hidden" name="pcb_fields" id="pcb_fields" value='<?php echo json_encode(array($fieldnames,$pcb)); ?>' >
                                    <input type="button" style="margin: 10px 10px 10px 0px; padding: 8px;" class="grey-btn" name="pcbsubmit" id="pcbsubmit" value="Submit">
                                    <div id="output2"></div>
                                    </div>
                                            
                                            	</fieldset>                                                  
                                            </form>
                                            
                                                   