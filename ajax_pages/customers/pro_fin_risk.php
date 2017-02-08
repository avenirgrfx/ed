<?php
include("connection.php");
//$tblname = $_REQUEST['tbl'];
//	$uid = $_REQUEST['uid'];
//	$qurey = "SELECT * FROM ed_$tblname where userid = $uid";
//	$result = mysql_query($qurey);
?>
<?php
function pmt($interest, $months, $loan) {
       $months = $months;
       $interest = $interest / 1200;
       $amount = $interest * -$loan * pow((1 + $interest), $months) / (1 - pow((1 + $interest), $months));
       //return number_format($amount, 2);
       return $amount;
    }
function irr ($investment, $flow) {

    for ($n = 0; $n < 100; $n += 0.0001) {

        $pv = 0;
        for ($i = 0; $i < count($flow); $i++) {
            $pv = $pv + $flow[$i] / pow(1 + $n, $i + 1);
        }

        if ($pv <= $investment) {
            return round($n * 10000) / 100;
        }

    }

}
switch($_REQUEST['type'])
{
	case 'spb':
		ob_start();
	?>
    <!--Simple Payback-->

<div class="spb-div">
	<header>
         <div class="border">
            <h2>&nbsp;</h2>
            <h3 style="width: 100%;">YEAR</h3>  
        </div>
    	<div class="border">
            <h2>UNDISCOUNTED OUTLAY</h2>
            <h3>ANNUAL</h3>  
            <h3>CUMULATIVE</h3>
        </div>        
    	<div class="border">
            <h2>ANNUAL EXPENSES</h2>
            <h3>ENERGY</h3>  
            <h3>O&M </h3>
        </div>        
    	<div class="border">
            <h2>UNDISCOUNTED SAVINGS</h2>
            <h3>ANNUAL</h3>  
            <h3>CUMULATIVE</h3>
        </div>        
        <div class="clearfix"></div>
    </header>
  
      <?php
	  //var_dump($_REQUEST);
      $cons_bugdet = $_REQUEST["consbugdet"]; //390500;
      $years = $_REQUEST["years"]; //7;
	 // echo $_REQUEST["escfactor"];
      $esc_factor = $_REQUEST["escfactor"]/100; //2.5/100;
      $salvage = $_REQUEST["salvage"]; //10000;
      $oti = explode(",",$_REQUEST["oti_amts_json"]);
$oti_new = array();
if(is_array($oti))
{
foreach($oti as $otival)
{
$oti_new[] = (float)$otival;	
}

}
else
{
$oti_new = array(0);	
}
$oti=$oti_new;
	  //var_dump($oti);
      $cur_labour = $_REQUEST["cur_labour"]; // 20000;
      $cur_maintenance =$_REQUEST["cur_maintenance"]; // 10000;
      $cur_other =$_REQUEST["cur_other"]; // 5000;
      $pro_labour = $_REQUEST["pro_labour"]; //15000;
      $pro_maintenance =$_REQUEST["pro_maintenance"]; // 7000;
      $pro_other =$_REQUEST["pro_other"]; // 4000;
      
      $net_benefit_cost = ($cur_labour- $pro_labour)+($cur_maintenance - $pro_maintenance)+($cur_other - $pro_other);
      
      
      
      $tot_pro_an_energy = 4576;
      $tot_an_energy_savings = 533812;
      
      $row_year = 0;
      $uo_annual = $cons_bugdet;
      $uo_cumulative = $cons_bugdet;
      $us_annual_sum =0;
      $us_annual_first = 0;
      
	while($row_year <= $years)
	{
		
		if($row_year == $years)
		{
			$uo_annual  = ($oti[$row_year-1] - $salvage);
			$uo_cumulative += $uo_annual;
			$uo_cumulative_final = $uo_cumulative;
		}
		elseif($row_year == 0)
		{
			
		}
		else
		{
			$uo_annual  = $oti[$row_year-1];
			
			$uo_cumulative += $uo_annual;
			
				
			
		}
	if($row_year != 0){
		//echo $esc_factor ."<br/>";
		//echo (1+$esc_factor);
		//echo pow((float)(1+$esc_factor),$row_year);
		$ae_energy = $tot_pro_an_energy * pow((1+$esc_factor),($row_year-1));
		$ae_onm = ($pro_labour + $pro_maintenance + $pro_other)* pow((1+$esc_factor),($row_year-1));
		$us_annual = ($tot_an_energy_savings + $net_benefit_cost) *pow((1+$esc_factor),($row_year-1));
		
		$us_annual_sum += $us_annual; 
		$us_cumulative = ($us_annual_sum)-($uo_cumulative);
	}
	else
	{
		$ae_energy = '';
		$ae_onm = '';
		$us_annual = '';
		$us_cumulative = '';
	}
	if($row_year == 1)
		{
			$us_annual_first = $us_annual;
		}
	
		
	?>
    <div class="row-data">
        <div class="content"><ul style="width:100%;" class="full"><li><?php echo $row_year;?></li> </ul></div>
        <div class="content"><ul><li><?php if($uo_annual < 0){echo "-";$uo_annual = -1*($uo_annual);} ?>$<?php echo number_format(round($uo_annual));?></li></ul><ul><li>$<?php echo number_format(round($uo_cumulative));?></li> </ul></div>      
        <div class="content"><ul><li>$<?php echo number_format(round($ae_energy));?></li></ul><ul><li>$<?php echo number_format(round($ae_onm));?></li></ul></div>     
        <div class="content"><ul><li>$<?php echo number_format(round($us_annual));?></li></ul><ul><li>$<?php echo number_format(round($us_cumulative));?></li></ul></div>      
               
    </div>
	<?php $row_year++; } ?> 
    </div>
     <!--Return on Investment (ROI)-->
     <?php
     $res["tbl"] = ob_get_clean();
     $res["params"]["uo_cumulative_final"]=$uo_cumulative_final;
     $res["params"]["us_cumulative_first"]=$us_annual_first;
     $res["result"] = round($uo_cumulative_final/$us_annual_first,1);
     echo json_encode($res);
	 break; 
	case 'roi':
	ob_start();
	?> 
    
    <div class="spb-div">
	<header>
    	<div class="border">
         <h2>&nbsp;</h2>
            <h3>YEAR</h3> 
         </div>
         <div class="border">
             <h2>UNDISCOUNTED OUTLAY</h2>
            <h3>ANNUAL</h3>  
            <h3>CUMULATIVE</h3>
        </div>               
    	<div class="border">
            <h2>UNDISCOUNTED SAVINGS</h2>
            <h3>ANNUAL</h3>  
            <h3>CUMULATIVE</h3>
        </div> 
        <div class="border">
            <h2>ROI</h2>
            <h3>ANNUAL</h3>
            <h3>CUMULATIVE</h3>
        </div> 
                     
        <div class="clearfix"></div>
    </header>
  
  <?php
  
    $cons_bugdet = $_REQUEST["consbugdet"]; //390500;
    $years = (int)$_REQUEST["years"]; //7;
    //var_dump($years);
	$esc_factor = $_REQUEST["escfactor"]/100; //2.5/100;
	$oti = explode(",",$_REQUEST["oti_amts_json"]);
$oti_new = array();
if(is_array($oti))
{
foreach($oti as $otival)
{
$oti_new[] = (float)$otival;	
}

}
else
{
$oti_new = array(0);	
}
$oti=$oti_new;
	//var_dump($oti);
	$salvage = $_REQUEST["salvage"]; //10000;
	
	$cur_labour = $_REQUEST["cur_labour"]; // 20000;
      $cur_maintenance =$_REQUEST["cur_maintenance"]; // 10000;
      $cur_other =$_REQUEST["cur_other"]; // 5000;
      $pro_labour = $_REQUEST["pro_labour"]; //15000;
      $pro_maintenance =$_REQUEST["pro_maintenance"]; // 7000;
      $pro_other =$_REQUEST["pro_other"]; // 4000;
      
      $net_benefit_cost = ($cur_labour- $pro_labour)+($cur_maintenance - $pro_maintenance)+($cur_other - $pro_other);
      
      
      
      $tot_pro_an_energy = 4576;
      $tot_an_energy_savings = 533812;
	
	$row_year = 0;
    $uo_annual = $cons_bugdet;
     $uo_cumulative = $cons_bugdet;
      $us_annual_sum =0;
      $uo_cumulative_final = $cons_bugdet;
	while($row_year <= $years)
	{
		
		if($row_year == $years)
		{
			$uo_annual  = (float)($oti[$row_year-1] - $salvage);
			$uo_cumulative += $uo_annual;
			$uo_cumulative_final = $uo_cumulative;
		}
		elseif($row_year == 0)
		{
			
		}
		else
		{
			$uo_annual  = (float)$oti[$row_year-1];
			
			$uo_cumulative += $uo_annual;
				
			
		}
	if($row_year != 0){
		
		//$ae_energy = $tot_pro_an_energy * pow((1+$esc_factor),($row_year-1));
		//$ae_onm = ($pro_labour + $pro_maintenance + $pro_other)* pow((1+$esc_factor),($row_year-1));
		$us_annual = ($tot_an_energy_savings + $net_benefit_cost) *pow((1+$esc_factor),($row_year-1));
		
		$us_annual_sum += $us_annual; 
		$us_cumulative = ($us_annual_sum)-($uo_cumulative);
		
		$roi_annual = $us_annual/$uo_cumulative;
		$roi_cumulative = $us_annual_sum/$uo_cumulative;
	}
	else
	{
		//$ae_energy = '';
		//$ae_onm = '';
		$us_annual = '';
		$us_cumulative = '';
		$roi_annual = '';
		$roi_cumulative = '';
	}
	if($row_year == 1)
		{
			//$us_annual_first = $us_annual;
		}
	
	?>
    
    <div class="row-data">
        <div class="content"><ul style="width:100%;" class="full"><li><?php echo $row_year;?></li> </ul></div>
        <div class="content"><ul><li><?php if($uo_annual < 0){echo "-";$uo_annual = -1*($uo_annual);} ?>$<?php echo number_format(round($uo_annual));?></li></ul><ul><li>$<?php echo number_format(round($uo_cumulative));?></li> </ul></div>
    	<div class="content"><ul><li>$<?php echo number_format(round($us_annual));?></li></ul><ul> <li>$<?php echo number_format(round($us_cumulative));?></li> </ul></div>
        <div class="content"><ul><li><?php  echo number_format(round($roi_annual*100));?>%</li></ul><ul> <li><?php echo number_format(round($roi_cumulative*100));?>% </li> </ul></div>
        
    </div>
    
	<?php $row_year++; } ?> 
  
    </div>
    
    
   
    <?php
	$res["tbl"] = ob_get_clean();
     
     $res["params"]["us_annual_avg"]=round($us_annual_sum/$years);
     $res["params"]["uo_cumulative_final"]=$uo_cumulative_final;
     $res["result"] = round($res["params"]["us_annual_avg"]*100/$uo_cumulative_final,2);
     echo json_encode($res);
	
	 break; 
	 ?>
      <!--LCC-->
     <?php
	 case 'lcc':
	 ob_start();
$cons_bugdet = $_REQUEST["consbugdet"]; //390500;
$years = isset($_REQUEST["years"])?$_REQUEST["years"]:1; //7;
$esc_factor = $_REQUEST["escfactor"]/100; //2.5/100;
$salvage = $_REQUEST["salvage"]; //10000;
$oti = explode(",",$_REQUEST["oti_amts_json"]);
//var_dump($oti);
$oti_new = array();
if(is_array($oti))
{
foreach($oti as $otival)
{
$oti_new[] = (float)$otival;	
}

}
else
{
$oti_new = array(0);	
}
$oti=$oti_new;
//var_dump($oti);
$cur_labour = $_REQUEST["cur_labour"]; // 20000;
$cur_maintenance =$_REQUEST["cur_maintenance"]; // 10000;
$cur_other =$_REQUEST["cur_other"]; // 5000;
$pro_labour = $_REQUEST["pro_labour"]; //15000;
$pro_maintenance =$_REQUEST["pro_maintenance"]; // 7000;
$pro_other =$_REQUEST["pro_other"]; // 4000;

$net_benefit_cost = ($cur_labour- $pro_labour)+($cur_maintenance - $pro_maintenance)+($cur_other - $pro_other);
$in_invest = $_REQUEST["in_invest"];
$an_cc = $_REQUEST["an_cc"];
$an_ror = $_REQUEST["an_ror"];


$tot_pro_an_energy = 4576;
$tot_an_energy_savings = 533812;

$row_year = 0;
$uo_annual = $cons_bugdet;
$uo_cumulative = $cons_bugdet;
$us_annual_sum =0;
$ae_energy_sum = 0; 
	  $ae_onm_sum = 0; 
	while($row_year <= $years)
	{
		
		if($row_year == $years)
		{
			$uo_annual  = ($oti[$row_year-1] - $salvage);
			$uo_cumulative += $uo_annual;
			$uo_cumulative_final = $uo_cumulative;
		}
		elseif($row_year == 0)
		{}
		else
		{
			$uo_annual  = $oti[$row_year-1];
			$uo_cumulative += $uo_annual;
		}
	if($row_year != 0){
		
		$ae_energy = $tot_pro_an_energy * pow((1+$esc_factor),($row_year-1));
		$ae_onm = ($pro_labour + $pro_maintenance + $pro_other)* pow((1+$esc_factor),($row_year-1));
		$us_annual = ($tot_an_energy_savings + $net_benefit_cost) *pow((1+$esc_factor),($row_year-1));
		$us_annual_sum += $us_annual; 
		$us_cumulative = ($us_annual_sum)-($uo_cumulative);
		$ae_energy_sum += $ae_energy;
		$ae_onm_sum += $ae_onm;
	}
	else
	{
		$ae_energy = '';
		$ae_onm = '';
		$us_annual = '';
		$us_cumulative = '';
	}
	if($row_year == 1)
		{
			$us_annual_first = $us_annual;
		}
	
		
	?>
    
   
    
	<?php $row_year++; } ?> 
  <?php
  //var_dump($oti);
  $col[0] = $in_invest;
  $an_cc;
  $years;
   $col[1] = $an_cc*$years - $in_invest;
  $col[2] = array_sum($oti) - ($salvage*pow((1+$an_ror/100),$years));
  $col[3] = $ae_onm_sum;
  $col[4] = $ae_energy_sum;
  $tlc = array_sum($col);
  ?>
  <div class="spb-result lcc-small-font">
    <div class="lcc-row">
					<div class="lcc-result-content-first">Initial capital investment</div>
					<div class="lcc-result-content-second"><?php if($col[0]<0){ echo "-$".-1*round($col[0]);}else{echo "$".round($col[0]);}?></div>
					<div class="lcc-result-content-third"><?php echo round($col[0]*100/$tlc);  ?>%</div>
				  </div>

				  <div class="lcc-row">
					<div class="lcc-result-content-first">Interest costs</div>
					<div class="lcc-result-content-second"><?php if($col[1]<0){ echo "-$".-1*round($col[1]);}else{echo "$".round($col[1]);} ?></div>
					<div class="lcc-result-content-third"><?php echo round($col[1]*100/$tlc);  ?>%</div>
				  </div>

				  <div class="lcc-row">
					<div class="lcc-result-content-first">Additional investments, minus the new asset's discounted salvage value</div>
					<div class="lcc-result-content-second"><?php if($col[2]<0){ echo "-$".-1*round($col[2]);}else{echo "$".round($col[2]);} ?></div>
					<div class="lcc-result-content-third"><?php echo round($col[2]*100/$tlc);  ?>%</div>
				  </div>

				  <div class="lcc-row">
					<div class="lcc-result-content-first">Sum of annual O&M costs over 7 years</div>
					<div class="lcc-result-content-second"><?php if($col[3]<0){ echo "-$".-1*round($col[3]);}else{echo "$".round($col[3]);} ?></div>
					<div class="lcc-result-content-third"><?php echo round($col[3]*100/$tlc);  ?>%</div>
				  </div>				  
				  
				  <div class="lcc-row">
					<div class="lcc-result-content-first">Sum of annual energy costs over 7 years</div>
					<div class="lcc-result-content-second"><?php if($col[4]<0){echo "-$".-1*round($col[4]);}else{echo "$".round($col[4]);} ?></div>
					<div class="lcc-result-content-third"><?php echo round($col[4]*100/$tlc);  ?>%</div>
				  </div>
				  <div class="lcc-row">
					<div class="lcc-result-content-first">TOTAL LIFE-CYCLE COST:</div>
					<div class="lcc-result-content-second"><strong><?php if($tlc<0){ echo "-$".-1*round($tlc);}else{echo "$".round($tlc);}?></strong></div>
					<div class="lcc-result-content-third"><strong><?php echo round($tlc*100/$tlc);?>%</strong></div>
				  </div>
				  </div>
				  <script>
					$("#pieChart").drawPieChart([
					{ title: "Initical capital investment", value : <?php echo round($col[0]*100/$tlc); ?>,  color: "#5b9bd5" },
					 { title: "Interest costs", value: <?php echo round($col[1]*100/$tlc); ?>, color: "#ed7d31" },
					 { title: "Additional investments", value : <?php echo round($col[2]*100/$tlc); ?>, color: "#a5a5a5" },
					 { title: "Sum of annual O &amp; M", value:  <?php echo round($col[3]*100/$tlc); ?>, color: "#ffc000" },
					{ title: "Sum of annual energy costs over 7 years", value : <?php echo round($col[4]*100/$tlc); ?>, color: "#4472c4" },
				       ]);
				  </script>
				 
				  <div class="w59 fl">
                                  <div id="pieChart" class="chart"></div>
				</div>
                                    <div class="w40 fl pie-txt-pad">
                                    	<div class="row-data">
                                            <div class="lcc-pie"> 
                                            <svg >
  						<rect width="15" height="15" style="fill:#5b9bd5;stroke-width:1;stroke:rgb(0,0,0)"></rect>
					   </svg> 
                                            </div>
                                            <div class="lcc-pie"><p>initial capital investment</p></div>
                                        </div>
                                    	<div class="row-data">
                                            <div class="lcc-pie"> 
                                            <svg>
  						<rect width="15" height="15" style="fill:#ed7d31;stroke-width:1;stroke:rgb(0,0,0)"></rect>
						</svg> 
                                            </div>
                                            <div class="lcc-pie"><p>Intertst costs</p></div>
                                        </div>      
                                    	<div class="row-data">
                                            <div class="lcc-pie"> 
                                            <svg>
  						<rect width="15" height="15" style="fill:#a5a5a5;stroke-width:1;stroke:rgb(0,0,0)"></rect>
						</svg> 
                                            </div>
                                            <div class="lcc-pie"><p>Additional investment, minus the view asset's discounted salvage value</p></div>
                                        </div>      
                                    	<div class="row-data">
                                            <div class="lcc-pie"> 
                                            <svg>
  						<rect width="15" height="15" style="fill:#ffc000;stroke-width:1;stroke:rgb(0,0,0)"></rect>
						</svg> 
                                            </div>
                                            <div class="lcc-pie"><p>Some of annual O&amp;M costs over 7 years</p></div>
                                        </div>                                    	
                                        <div class="row-data">
                                            <div class="lcc-pie"> 
                                            <svg>
  						<rect width="15" height="15" style="fill:#4472c4;stroke-width:1;stroke:rgb(0,0,0)"></rect>
						</svg> 
                                            </div>
                                            <div class="lcc-pie"><p>"Sum of annual energy costs over 7 years</p></div>
                                        </div>                                                                                                                 
                                    </div>
                                   
                               
    
    
   
    <?php
     $res["params"]["ini_cap_inves"]=200;
     //$res["params"]["intertst_cost"]=$col[1];
     //$res["params"]["add_inves_value"]=$col[2];
     //$res["params"]["anu_om_cost"]=$col[3];
     //$res["params"]["anu_en-cost"]=$col[4];
     
     
     
     $res["params"]["lcc_result"]=round($tlc);
     $res["tbl"] = ob_get_clean();
     
     
     echo json_encode($res);
     break;
     ?>
     <!--Net Present Value  (NPV)-->
     <?php
	case 'npv':
	ob_start();	
	?>
	<header>
	     <div class="border">
		<h2>&nbsp;</h2>
		<h3 style="width: 100%;">&nbsp</h3> 
		<h3 style="width: 100%;">YEAR</h3>  
	    </div>
	    <div class="border">
		<h2>UNDISCOUNTED CASH FLOWS</h2>
		<h3>CAPITAL  <br />OUTLAYS</h3>  
		<h3>ANNUAL  <br />RETURNS</h3>
		<h3>NET ANNUAL <br />CASH FLOW</h3>
	    </div>        
	    <div class="border">
		<h2>DISCOUNTED CASH FLOWS</h2>
		<h3>CAPITAL OUTLAYS <br /></h3>   
		<h3>ANNUAL  <br />RETURNS</h3>
		<h3>NET ANNUAL <br />CASH FLOW</h3>
		
	    </div>
	    <div class="border">
		    <h2>&nbsp;</h2>
		    <h3>NPV THRU <br />YEAR t</h3>
	    </div>						
	    <div class="clearfix"></div>
	</header>
	<?php
  
        $cons_bugdet = $_REQUEST["consbugdet"]; //390500;
        $years = $_REQUEST["years"]; //7;
	$esc_factor = $_REQUEST["escfactor"]/100; //2.5/100;
	$oti = explode(",",$_REQUEST["oti_amts_json"]);
$oti_new = array();
if(is_array($oti))
{
foreach($oti as $otival)
{
$oti_new[] = (float)$otival;	
}

}
else
{
$oti_new = array(0);	
}
$oti=$oti_new;
	//var_dump($oti);
	$salvage = $_REQUEST["salvage"]; //10000;
	
	$cur_labour = $_REQUEST["cur_labour"]; // 20000;
        $cur_maintenance =$_REQUEST["cur_maintenance"]; // 10000;
        $cur_other =$_REQUEST["cur_other"]; // 5000;
        $pro_labour = $_REQUEST["pro_labour"]; //15000;
        $pro_maintenance =$_REQUEST["pro_maintenance"]; // 7000;
        $pro_other =$_REQUEST["pro_other"]; // 4000;
        $an_ror = $_REQUEST["an_ror"];
        $net_benefit_cost = ($cur_labour- $pro_labour)+($cur_maintenance - $pro_maintenance)+($cur_other - $pro_other);
      
      
      
        $tot_pro_an_energy = 4576;
        $tot_an_energy_savings = 533812;
	
	$row_year = 0;
        $uc_co = $cons_bugdet;
        $dc_cf_sum = 0;
        $uc_cf_summation =0;
        $uc_cf_zero=0;
	while($row_year <= $years)
	{
		if($row_year == $years)
		{
			$uc_co  = (float)($oti[$row_year-1] - $salvage);
		}
		elseif($row_year == 0)
		{
			
		}
		else
		{
			$uc_co  = (float)$oti[$row_year-1];
		}
	if($row_year != 0){
		
		$uc_ar = ($tot_an_energy_savings + $net_benefit_cost) *pow((1+$esc_factor),($row_year-1));
		$dc_ar = $uc_ar/pow((1+$an_ror/100),$row_year);
		
	}
	else
	{	
		$uc_ar='';
		$dc_ar = '';
	}
	
	$uc_cf = $uc_ar-$uc_co;
	$dc_co = $uc_co/pow((1+$an_ror/100),$row_year);
	
	$dc_cf = $uc_cf/pow((1+$an_ror/100),$row_year);
	$dc_cf_sum +=$dc_cf;
	if($row_year == 0)
	{
		$uc_cf_zero = $uc_cf;
	}
	if($row_year != 0)
	{
		$uc_cf_summation += $uc_cf/pow((1+$an_ror/100),$row_year);
	}
	
	?>
    
	<div class="row-data" >
	    <div class="content plus-npv-content"><ul style="width:100%;" class="full"><li><?php echo $row_year; ?></li> </ul></div>
	    <div class="content plus-npv-content"><ul><li><?php if($uc_co < 0){echo '-$'.number_format(-1*round($uc_co));}else{echo '$'.number_format(round($uc_co));} ?></li></ul><ul><li><?php if($uc_ar < 0){echo '-$'.number_format(-1*round($uc_ar));}else{echo '$'.number_format(round($uc_ar));} ?></li> </ul><ul><li><?php if($uc_cf < 0){echo '-$'.number_format(-1*round($uc_cf));}else{echo '$'.number_format(round($uc_cf));} ?></li> </ul></div>      
	    <div class="content plus-npv-content"><ul><li><?php if($dc_co < 0){echo '-$'.number_format(-1*round($dc_co));}else{echo '$'.number_format(round($dc_co));} ?></li></ul><ul><li><?php if($dc_ar < 0){echo '-$'.number_format(-1*round($dc_ar));}else{echo '$'.number_format(round($dc_ar));} ?></li></ul><ul><li><?php if($dc_cf < 0){echo '-$'.number_format(-1*round($dc_cf));}else{echo '$'.number_format(round($dc_cf));} ?></li></ul></div>
	    <div class="content plus-npv-content"><ul><li><?php if($dc_cf_sum < 0){echo '-$'.number_format(-1*round($dc_cf_sum));}else{echo '$'.number_format(round($dc_cf_sum));} ?></li></ul></div>
	</div>
        
	
	<?php $row_year++; } ?> 
    
     <?php
     $res["tbl"] = ob_get_clean();
     $res["params"]["uc_cf_summation"]=$uc_cf_summation;
     $res["params"]["uc_cf_zero"]=$uc_cf_zero;
     
     $res["result"] = round($uc_cf_summation+$uc_cf_zero);
     echo json_encode($res);
     ?>
 
 
   
    <?php
	 break;
	?>
	
	 <!--Internal Rate of Return  (IRR))-->

	<?php
	case 'irr':
	ob_start();	
	?>
	
    <div class="irr-div">
	<header>
    	<div class="irr-full">
        	<h2>CASH FLOW SUMMARY</h2>
            <h2>FUEL AND O&M COSTS ESCALATED 2.5% PER ANNUM</h2>
        </div>
    	<div class="irr-border">
            <h3>&nbsp;</h3> 
            <h3>YEAR t</h3>             
        </div>               
    	<div class="irr-border">
            <h3>CAPITAL</h3> 
            <h3>OUTLAYS</h3>  
        </div> 
        <div class="irr-border">
            <h3>UNDISCOUNTED</h3> 
            <h3>RETURNS</h3>  
    
        </div> 
        <div class="irr-border">
			<h3>UNDISCOUNTED</h3>
            <h3>NET CASH FLOW</h3>             
        </div>                
        <div class="clearfix"></div>
    </header>
  
  <?php
  
        $cons_bugdet = $_REQUEST["consbugdet"]; //390500;
        $years = $_REQUEST["years"]; //7;
	$esc_factor = $_REQUEST["escfactor"]/100; //2.5/100;
	$oti = explode(",",$_REQUEST["oti_amts_json"]);
$oti_new = array();
if(is_array($oti))
{
foreach($oti as $otival)
{
$oti_new[] = (float)$otival;	
}

}
else
{
$oti_new = array(0);	
}
$oti=$oti_new;
	//var_dump($oti);
	$salvage = $_REQUEST["salvage"]; //10000;
	
	$cur_labour = $_REQUEST["cur_labour"]; // 20000;
        $cur_maintenance =$_REQUEST["cur_maintenance"]; // 10000;
        $cur_other =$_REQUEST["cur_other"]; // 5000;
        $pro_labour = $_REQUEST["pro_labour"]; //15000;
        $pro_maintenance =$_REQUEST["pro_maintenance"]; // 7000;
        $pro_other =$_REQUEST["pro_other"]; // 4000;
        $an_ror = $_REQUEST["an_ror"];
        $net_benefit_cost = ($cur_labour- $pro_labour)+($cur_maintenance - $pro_maintenance)+($cur_other - $pro_other);
      
      
      
        $tot_pro_an_energy = 4576;
        $tot_an_energy_savings = 533812;
	
	$row_year = 0;
        $capital_outlays = $cons_bugdet;
       $ncf_summation =0;
	while($row_year <= $years)
	{
		if($row_year == $years)
		{
			$capital_outlays  = (float)($oti[$row_year-1] - $salvage);
		}
		elseif($row_year == 0)
		{
			
		}
		else
		{
			$capital_outlays  = (float)$oti[$row_year-1];
		}
	if($row_year != 0){
		
		$un_returns = ($tot_an_energy_savings + $net_benefit_cost) *pow((1+$esc_factor),($row_year-1));
		
		
		
		
	}
	else
	{	
		$un_returns='';
	}
	$un_netcf = (float)$un_returns - (float)$capital_outlays;
	if($row_year != 0)
	{
		$un_netcf_array[]= $un_netcf;
	}
	
	
	?>
    
    <div class="row-data">
        <div class="irr-content"><ul><li><?php echo $row_year;?></li></ul></div>
	
	
    	<div class="irr-content"><ul><li><?php if($capital_outlays<0){echo '-$'.number_format(-1*round($capital_outlays));}else{echo '$'.number_format(round($capital_outlays));} ?></li></ul></div>
        <div class="irr-content"><ul><li><?php if($un_returns<0){echo '-$'.number_format(-1*round($un_returns));}else{echo '$'.number_format(round($un_returns));}?></li> </ul></div>
        <div class="irr-content"><ul><li><?php if($un_netcf<0){echo '-$'.number_format(-1*round($un_netcf));}else{echo '$'.number_format(round($un_netcf));}?></li></ul></div>
    </div>
    
     <?php $row_year++; } ?> 
    
     <?php
     
     $res["tbl"] = ob_get_clean();
   // $res["params"]["uc_cf_summation"]=$uc_cf_summation;
  //  $res["params"]["uc_cf_zero"]=$uc_cf_zero;
     
     $res["result"] = round(irr($cons_bugdet, $un_netcf_array),1);
     echo json_encode($res);
     
     ?>
<?php break;?>
<!--Con-->
 
 <?php
	case 'con':
	     ob_start();	
	?>
<?php
$cons_bugdet = $_REQUEST["consbugdet"]; //390500;
$years = $_REQUEST["years"]; //7;
$esc_factor = $_REQUEST["escfactor"]/100; //2.5/100;
$oti = explode(",",$_REQUEST["oti_amts_json"]);
$oti_new = array();
if(is_array($oti))
{
foreach($oti as $otival)
{
$oti_new[] = (float)$otival;	
}

}
else
{
$oti_new = array(0);	
}
$oti=$oti_new;
$salvage = $_REQUEST["salvage"]; //10000;
//echo ($cons_bugdet+$_REQUEST["oti_amts_json"]-$salvage);
$cur_labour = $_REQUEST["cur_labour"]; // 20000;
$cur_maintenance =$_REQUEST["cur_maintenance"]; // 10000;
$cur_other =$_REQUEST["cur_other"]; // 5000;
$pro_labour = $_REQUEST["pro_labour"]; //15000;
$pro_maintenance =$_REQUEST["pro_maintenance"]; // 7000;
$pro_other =$_REQUEST["pro_other"]; // 4000;
$an_ror = $_REQUEST["an_ror"];
$net_benefit_cost = ($cur_labour- $pro_labour)+($cur_maintenance - $pro_maintenance)+($cur_other - $pro_other);
$in_invest = $_REQUEST["in_invest"];
$an_cc = $_REQUEST["an_cc"];
$an_ror = $_REQUEST["an_ror"];
?>
<?php
if($an_ror == 0)
{
	$crf =0;
}
else
{
     $crf = pmt($an_ror, $years*12, 1)*12 ;
     // $crf = (($an_ror/1200)*pow((1+$an_ror/1200),$years*12)/(pow((1+$an_ror/1200),$years*12)-1))*12;
}
    
     $annualized_tpi = (float)$in_invest * $crf;
     $an_mmbtu_saved = 23351;
     $first_price_per_mmbtu = 22.8605;
     $res["tbl"] =  ob_get_clean();
     $res["params"]["crf"]=round($crf,4);
     $res["params"]["annualized_tpi"]=round($annualized_tpi);
     $res["params"]["an_mmbtu_saved"]=$an_mmbtu_saved;
     $res["params"]["an_cost_to_save_one_mmbtu"]=round($annualized_tpi/$an_mmbtu_saved,2);
     $res["params"]["first_price_per_mmbtu"]=round($first_price_per_mmbtu,2);
     $res["params"]["cob_ratio"]=$res["params"]["an_cost_to_save_one_mmbtu"]/round($first_price_per_mmbtu);
     $res["params"]["con_extra"] = round($an_mmbtu_saved*$first_price_per_mmbtu);
     $res["params"]["con_extra2"] =round(($first_price_per_mmbtu-$res["params"]["an_cost_to_save_one_mmbtu"])*$an_mmbtu_saved);
  echo json_encode($res);
 ?>
 <?php break;?>
 <!--break even analysis-->
  <?php
	case 'bea':
	     ob_start();	
	?>
<?php
$cons_bugdet = $_REQUEST["consbugdet"]; //390500;
$years = $_REQUEST["years"]; //7;
$esc_factor = $_REQUEST["escfactor"]/100; //2.5/100;
$oti = explode(",",$_REQUEST["oti_amts_json"]);
$oti_new = array();
if(is_array($oti))
{
foreach($oti as $otival)
{
$oti_new[] = (float)$otival;	
}

}
else
{
$oti_new = array(0);	
}
$oti=$oti_new;
$salvage = $_REQUEST["salvage"]; //10000;
//echo ($cons_bugdet+$_REQUEST["oti_amts_json"]-$salvage);
$cur_labour = $_REQUEST["cur_labour"]; // 20000;
$cur_maintenance =$_REQUEST["cur_maintenance"]; // 10000;
$cur_other =$_REQUEST["cur_other"]; // 5000;
$pro_labour = $_REQUEST["pro_labour"]; //15000;
$pro_maintenance =$_REQUEST["pro_maintenance"]; // 7000;
$pro_other =$_REQUEST["pro_other"]; // 4000;
$an_ror = $_REQUEST["an_ror"];
$net_benefit_cost = ($cur_labour- $pro_labour)+($cur_maintenance - $pro_maintenance)+($cur_other - $pro_other);
$in_invest = $_REQUEST["in_invest"];
$an_cc = $_REQUEST["an_cc"];
$an_ror = $_REQUEST["an_ror"];
?>
<?php

     $an_mmbtu_saved = 23351;
     $price_per_mmbtu = 22.8605;
     $res["tbl"] =''; // ob_get_clean();
     
     $res["params"]["an_mmbtu_saved"] = round($an_mmbtu_saved);
     $res["params"]["price_per_mmbtu"] = round($price_per_mmbtu,4);
     $res["params"]["max_acc_an_pc"]= round($price_per_mmbtu * $an_mmbtu_saved);
     
     $res["params"]["total_savings"]= $res["params"]["max_acc_an_pc"]+ $net_benefit_cost;
     if($an_ror == 0)
{
	$crf =0;
	$res["params"]["e_mai"] = '...';
	$res["params"]["eno_mai"] = '...';
}
else
{
        $crf = pmt($an_ror, $years*12, 1)*12 ;
     // $crf = (($an_ror/1200)*pow((1+$an_ror/1200),$years*12)/(pow((1+$an_ror/1200),$years*12)-1))*12;
     $res["params"]["e_mai"] = round($res["params"]["max_acc_an_pc"]/$crf);
$res["params"]["eno_mai"] = round($res["params"]["total_savings"]/$crf);
}
$res["params"]["crf"]=round($crf,4);

     
     
echo json_encode($res);
 ?>
 <?php break;?>
 
  <!--RISK-->
 <?php
     case 'risk':
     ob_start();	
    ?>
<?php
$cons_bugdet = $_REQUEST["consbugdet"]; //390500;
$years = $_REQUEST["years"]; //7;
$esc_factor = $_REQUEST["escfactor"]/100; //2.5/100;
$oti = explode(",",$_REQUEST["oti_amts_json"]);
$oti_new = array();
if(is_array($oti))
{
foreach($oti as $otival)
{
$oti_new[] = (float)$otival;	
}

}
else
{
$oti_new = array(0);	
}
$oti=$oti_new;
$salvage = $_REQUEST["salvage"]; //10000;
//echo ($cons_bugdet+$_REQUEST["oti_amts_json"]-$salvage);
$cur_labour = $_REQUEST["cur_labour"]; // 20000;
$cur_maintenance =$_REQUEST["cur_maintenance"]; // 10000;
$cur_other =$_REQUEST["cur_other"]; // 5000;
$pro_labour = $_REQUEST["pro_labour"]; //15000;
$pro_maintenance =$_REQUEST["pro_maintenance"]; // 7000;
$pro_other =$_REQUEST["pro_other"]; // 4000;
$an_ror = $_REQUEST["an_ror"];
$net_benefit_cost = ($cur_labour - $pro_labour)+($cur_maintenance - $pro_maintenance)+($cur_other - $pro_other);
$in_invest = $_REQUEST["in_invest"];
$an_cc = $_REQUEST["an_cc"];
$an_ror = $_REQUEST["an_ror"];
?>
<?php
$pro_an_energy = 4576;
$total_energy_saving = 533812;
$omit_exp= $pro_an_energy + $pro_labour + $pro_maintenance + $pro_other;
if($an_ror ==0)
{
	$an_pc = 0;
}
else
{
$an_pc = pmt(7.4,$years*12,$in_invest)*12;
}
$value_risk = $total_energy_saving + $net_benefit_cost - $an_pc;

$res["params"]["omit_exp"]= round($omit_exp);
$res["params"]["an_pc"]= round($an_pc);
$res["params"]["value_risk"]= round($value_risk);
$res["params"]["red"]=  0.000283*$value_risk;
$res["params"]["gray"]= 0.000283*$an_pc;
$res["params"]["dark"]= 0.000283*$omit_exp;


$res["params"]["oav_sum"] = round($omit_exp+ $an_pc+$value_risk);
$res["params"]["av_sum"] = round($an_pc+$value_risk);
$res["params"]["omit_exp_per"]= round($omit_exp*100/$res["params"]["oav_sum"]);
$res["params"]["an_pc_per"]= round($an_pc*100/$res["params"]["oav_sum"]);
$res["params"]["value_risk_per"]= round($value_risk*100/$res["params"]["oav_sum"]);
$res["params"]["av_sum_per"] = round(($an_pc+$value_risk)*100/$res["params"]["oav_sum"]);
$res["params"]["oav_sum_per"] = round(($omit_exp+ $an_pc+$value_risk)*100/$res["params"]["oav_sum"]);
$res["params"]["an_gain"]= round($res["params"]["av_sum"]-$an_pc);

  echo json_encode($res);
 ?>
<?php
	 break;
	default: break;	
}
?>






                       
                        
                        
                        
                           
                           