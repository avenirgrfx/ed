<?php
include("connection.php");
$rtype = $_POST["rtype"];
$year = isset($_POST['year'])?$_POST['year']:0;
$project_id = isset($_POST['project_id'])?$_POST['project_id']:0;

function pmt($interest, $months, $loan) {
       $months = $months;
       $interest = $interest / 1200;
       $amount = $interest * -$loan * pow((1 + $interest), $months) / (1 - pow((1 + $interest), $months));
       //return number_format($amount, 2);
       return $amount;
    }
if($rtype == "adddata")
{
	
	$description = $_POST['description'];
	$undiscounted_value = $_POST['undiscounted'];
	$qurey = "INSERT INTO ed_additional_one_time_investment_outlays SET project_id='$project_id', year='$year',description='$description',undiscounted_value='$undiscounted_value'";
        //echo $qurey; die;
	$result = mysql_query($qurey);
	if($result)
	{
	echo "Add Successfully";
        }
}
elseif($rtype == "checkyear")
{
    $sql="SELECT * FROM ed_additional_one_time_investment_outlays where year ='$year' and project_id='$project_id'";
    $result = mysql_query($sql);
    
    if(mysql_num_rows($result)>0)
    {
        $row = mysql_fetch_array($result);
        $row["error"]=false;
     echo json_encode($row);
    }
    else
     echo json_encode(array("error"=>true));
}
elseif($rtype == "updatedata")
{
    $description = $_POST['description'];
    $undiscounted_value = $_POST['undiscounted'];
    $year = $_POST['year'];
    $sql="update ed_additional_one_time_investment_outlays SET description='$description',undiscounted_value='$undiscounted_value' where year='$year'";
    $result = mysql_query($sql);
    if($result)
    {
      echo "Updated Successfully";
    } 
}

elseif($rtype == "getyears")
{
   
    $sql="SELECT year FROM ed_additional_one_time_investment_outlays where project_id ='$project_id'";
    $result = mysql_query($sql);
    while($rows= mysql_fetch_array($result))
    {
      echo "<option  value='".$rows['year']."' >".$rows['year']."</option>";
     
    }
}

elseif($rtype == "getyearsdetails")
{
    
    
    $noy = $_POST["noy"];
    $sql="SELECT * FROM ed_additional_one_time_investment_outlays where project_id='$project_id' limit $noy";
    $result = mysql_query($sql);
    
    
    if(mysql_num_rows($result)>0)
    {
        $option_str = "";
        $total_amt = array();
        while($row = mysql_fetch_array($result))
        {
            $total_amt[]= $row['undiscounted_value'];
            
            $option_str .='<div class="additional-row">
                <div class="additional-content" id="additional-one-time-investment">'.$row['year'].'</div>
                <div class="additional-content" id="additional-content_description">'.$row['description'].'</div>
                <div class="additional-content" id="additional-content_undiscounted_value">'.$row['undiscounted_value'].'</div>
            </div>';
             
           
            
        }
        $data["option_str"] = $option_str;
        $data["total_amt"] = array_sum($total_amt);
	    $data["total_amt_cs"] = implode(',',$total_amt);
        $data["error"] = false;
        
        
    }
    else
    {
      $data["error"] = true;  
    }
    echo json_encode($data);
}
elseif($rtype == "addpcb")
{
    $pcbdata = json_decode($_POST["pcb_fields"],true);
    $pcbinitial=$pcbdata[1];
    $pcbannual=$pcbdata[1];
    $pcbperiodic=$pcbdata[1];
   for($i=0;$i<count($pcbinitial);$i++ )
   {
    if($i == 0)
{
$suf ="ea";
}
elseif($i == 1)
{
$suf ="fs";
}
elseif($i == 2)
{
$suf ="de";
}
elseif($i == 3)
{
$suf ="en";
}
   $pcbdetails2 = $pcbinitial[$i]["data"];
    for($j=0;$j<count($pcbdetails2);$j++ )
    {
        $pcbdetails3 = $pcbinitial[$i]["data"][$j]["data"];
        for($k=0;$k<count($pcbdetails3);$k++)
        {
            $pcbinitial[$i]["data"][$j]["data"][$k] = explode(":",$pcbinitial[$i]["data"][$j]["data"][$k]);
            $pcbannual[$i]["data"][$j]["data"][$k] = explode(":",$pcbannual[$i]["data"][$j]["data"][$k]);
            $pcbperiodic[$i]["data"][$j]["data"][$k] = explode(":",$pcbperiodic[$i]["data"][$j]["data"][$k]);
            $pcbinitial[$i]["data"][$j]["data"][$k]= $pcbinitial[$i]["data"][$j]["data"][$k][0].":".str_replace("$ - ","",$_POST["in_".$suf."_".$pcbinitial[$i]["data"][$j]["data"][$k][0]]);
            $pcbannual[$i]["data"][$j]["data"][$k]= $pcbannual[$i]["data"][$j]["data"][$k][0].":".str_replace("$ - ","",$_POST["an_".$suf."_".$pcbannual[$i]["data"][$j]["data"][$k][0]]);
            $pcbperiodic[$i]["data"][$j]["data"][$k]= $pcbperiodic[$i]["data"][$j]["data"][$k][0].":".str_replace("$ - ","",$_POST["pe_".$suf."_".$pcbperiodic[$i]["data"][$j]["data"][$k][0]]);
        
        }
    }
   }
   
   
   //echo "<pre>";
    //var_dump($pcbdetails);
    //echo "</pre>";
    $pcbinitialjson = json_encode($pcbinitial);
    $pcbannualjson = json_encode($pcbannual);
    $pcbperiodicjson = json_encode($pcbperiodic);
    //var_dump($pcbinitialjson);
    $res = mysql_query("select id from ed_project_construction_budget where project_id = $project_id and cost_type='initial'");
    if(mysql_num_rows($res)>0)
    {
       $sql1="update ed_project_construction_budget set data='$pcbinitialjson' where project_id=$project_id and cost_type='initial'";
       $result1 = mysql_query($sql1) or die(mysql_error());
        $sql2="update ed_project_construction_budget set data='$pcbannualjson' where project_id=$project_id and cost_type='annual'";
       $result2 = mysql_query($sql2) or die(mysql_error());
        $sql3="update ed_project_construction_budget set data='$pcbperiodicjson' where project_id=$project_id and cost_type='periodic'";
       $result3 = mysql_query($sql3) or die(mysql_error());
       if($result1 && $result2 && $result3)
    {
        echo "Successfully updated";
    }
    }
    else{
    $sql = "INSERT INTO ed_project_construction_budget SET data='$pcbinitialjson', project_id=$project_id,cost_type='initial'";
    $result1 = mysql_query($sql);
    $sql = "INSERT INTO ed_project_construction_budget SET data='$pcbannualjson', project_id=$project_id,cost_type='annual'";
    $result2 = mysql_query($sql);
    $sql = "INSERT INTO ed_project_construction_budget SET data='$pcbperiodicjson', project_id=$project_id,cost_type='periodic'";
    $result3 = mysql_query($sql);
    
    if($result1 && $result2 && $result3)
    {
        echo "Successfully Submitted";
    }
    }

   
}
elseif($rtype== "getconstructionsummary")
{
$pcb_result= mysql_query("select * from ed_project_construction_budget where project_id = $project_id and cost_type='initial'");
$pcb_rs=mysql_fetch_array($pcb_result);
$pcbdata = json_decode($pcb_rs["data"],true);

$pcb_result2= mysql_query("select * from ed_project_construction_budget where project_id = $project_id and cost_type='annual'");
$pcb_rs2=mysql_fetch_array($pcb_result2);
$pcbdata2 = json_decode($pcb_rs2["data"],true);

$pcb_result3= mysql_query("select * from ed_project_construction_budget where project_id = $project_id and cost_type='periodic'");
$pcb_rs3=mysql_fetch_array($pcb_result3);
$pcbdata3 = json_decode($pcb_rs3["data"],true);

  
    $pcbinitial=$pcbdata;
    $pcbannual=$pcbdata2;
    $pcbperiodic=$pcbdata3;
    $cost[0]=0;
    $cost[1] = 0;
    $cost[2] =0;
    $cost[3]=0;
    
   for($i=0;$i<count($pcbinitial);$i++ )
   {
   $pcbdetails2 = $pcbinitial[$i]["data"];
   $subcost=0;
    for($j=0;$j<count($pcbdetails2);$j++ )
    {
       
        $pcbdetails3 = $pcbinitial[$i]["data"][$j]["data"];
       // for($k=0;$k<count($pcbdetails3);$k++)
       // {
            $pcbinitial[$i]["data"][$j]["data"][0] = explode(":",$pcbinitial[$i]["data"][$j]["data"][0]);
            $pcbinitial[$i]["data"][$j]["data"][1] = explode(":",$pcbinitial[$i]["data"][$j]["data"][1]);
            $pcbannual[$i]["data"][$j]["data"][0] = explode(":",$pcbannual[$i]["data"][$j]["data"][0]);
            $pcbannual[$i]["data"][$j]["data"][1] = explode(":",$pcbannual[$i]["data"][$j]["data"][1]);
            $pcbperiodic[$i]["data"][$j]["data"][0] = explode(":",$pcbperiodic[$i]["data"][$j]["data"][0]);
            $pcbperiodic[$i]["data"][$j]["data"][1] = explode(":",$pcbperiodic[$i]["data"][$j]["data"][1]);
            
            $initial = $pcbinitial[$i]["data"][$j]["data"][0][1]*$pcbinitial[$i]["data"][$j]["data"][1][1];
            $annual = $pcbannual[$i]["data"][$j]["data"][0][1]*$pcbannual[$i]["data"][$j]["data"][1][1];
            $periodic = $pcbperiodic[$i]["data"][$j]["data"][0][1]* $pcbperiodic[$i]["data"][$j]["data"][1][1];
            //$pcbinitial[$i]["data"][$j]["data"][$k]= $pcbinitial[$i]["data"][$j]["data"][$k][1];
            //$pcbannual[$i]["data"][$j]["data"][$k]= $pcbannual[$i]["data"][$j]["data"][$k][1];
            //$pcbperiodic[$i]["data"][$j]["data"][$k]= $pcbperiodic[$i]["data"][$j]["data"][$k][1];
            $subcost += $initial+$annual+$periodic;
       
       // }
    }
      $cost[$i]=$subcost;
   }
  $cost2["ea"]=$cost[0];
  $cost2["fs"]=$cost[1];
  $cost2["de"]=$cost[2];
  $cost2["en"]=$cost[3];
  $cost2["pcbtotalcost"] = $cost[0]+ $cost[1]+ $cost[2]+ $cost[3];
  echo json_encode($cost2);
    
}
elseif($rtype == "calculatepmt")
{
$ror= ($_POST["ror"] == '')?0:$_POST["ror"];
$nop= $_POST["years"]*12;
$pamt= $_POST["un_tpi"];
if($ror == 0)
{
  echo "0";  
}else{
echo round(pmt($ror,$nop,$pamt)*12);
}
}
elseif($rtype == "annualrecurring"){
$project_id = $_POST['project_id'];
$labor_exi_app = $_POST['lexapp'];
$labor_Pro_app = $_POST['lproapp'];
$main_exis_app = $_POST['mexapp'];
$main_Pro_app = $_POST['mproapp'];
$oth_cc_exis_app = $_POST['occexapp'];
$oth_cc_pro_app = $_POST['occproapp'];

$data[0][0]=$labor_exi_app;
$data[0][1]=$main_exis_app;
$data[0][2]=$oth_cc_exis_app;

$data[1][0]=$labor_Pro_app;
$data[1][1]=$main_Pro_app;
$data[1][2]=$oth_cc_pro_app;
$project_id = $_POST['project_id'];
$datajson = json_encode($data);
$sql1 = "SELECT * FROM ed_an_rec_non_en_operating_costs_and_benefit WHERE project_id='$project_id'";
$result1=mysql_query($sql1);
if(mysql_num_rows($result1)>0)
{
       $sql = "UPDATE ed_an_rec_non_en_operating_costs_and_benefit SET data='$datajson' where project_id='$project_id'";
       
       $result = mysql_query($sql);
       if($result)
       {
	   echo "Update Successfully";    
       }
}
else{
$sql = "INSERT INTO ed_an_rec_non_en_operating_costs_and_benefit SET data='$datajson', project_id='$project_id'";

$result = mysql_query($sql);

if($result)
{
    echo "Add Successfully";
}
}
}

elseif($rtype == "fetchAnnualnetBenefit")
{
    $project_id = $_POST['project_id'];   
    $sql="SELECT * FROM ed_an_rec_non_en_operating_costs_and_benefit where project_id='$project_id'";
    $result = mysql_query($sql);
    
    if(mysql_num_rows($result)>0)
    {
        $row = mysql_fetch_array($result);
	
	$rowarray = json_decode($row["data"],true);
	$data["cur"]["lab"] =$rowarray[0][0];
	$data["cur"]["mai"] =$rowarray[0][1];
	$data["cur"]["oth"] =$rowarray[0][2];
	
	$data["pro"]["lab"] =$rowarray[1][0];
	$data["pro"]["mai"] =$rowarray[1][1];
	$data["pro"]["oth"] =$rowarray[1][2];
     echo json_encode($data);
    }
}
?>


