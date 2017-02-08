<?php
ob_start();
require_once('configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/category.class.php');
require_once(AbsPath.'classes/system.class.php');
require_once(AbsPath.'classes/gallery.class.php');
require_once(AbsPath."classes/customer.class.php");

$DB=new DB;

$folder = AbsPath."../meter/001EC6051601/";
if (file_exists($folder)) {
    
    $filenames = scandir($folder);
    foreach($filenames as $filename){
        $file_array = explode(".",$filename);
        if(strpos($file_array[0], "mb-") !== false){
            $number_array = explode("-",$file_array[0]);
            $number = $number_array[1];
            if(file_exists($folder.$filename)) {
                
                $file = fopen($folder.$filename,"r");
                $strSQL="Insert into t_lhnode_001EC6051601$number (`synctime`, `error`, `lowalarm`, `highalarm`, `kwhsystem`, `kwsystem`, `kw_demand_max`, `kw_demand`, `kw_system_max`, `kw_system_min`, `kvarh_system`, `kvar_system`, `kvah_system`, `kva_system`, `dpf`, `apf`, `amps_system_avg`, `volts_l_to_l_avg`, `volts_line_to_neutral_avg`, `volts_l1_to_l2`, `volts_l2_to_l3`, `volts_l1_to_l3`, `measured_line_frequency`, `kwh_l1`, `kwh_l2`, `kwh_l3`, `kw_l1`, `kw_l2`, `kw_l3`, `kvarh_l1`, `kvarh_l2`, `kvarh_l3`, `kvar_l1`, `kvar_l2`, `kvar_l3`, `kvah_l1`, `kvah_l2`, `kvah_l3`, `kva_l1`, `kva_l2`, `kva_l3`, `dpf_l1`, `dpf_l2`, `dpf_l3`, `apf_l1`, `apf_l2`, `apf_l3`, `amps_l1`, `amps_l2`, `amps_l3`, `volts_l1_to_neutral`, `volts_l2_to_neutral`, `volts_l3_to_neutral`, `kw_demand_system_avg`, `kw_demand_system_min`, `kva_demand_system_max`, `kva_demand_system_now`, `kvar_demand_system_max`, `kwh_system_positive`, `kw_system_positive`, `kW_Demand_System_Max_Positive`, `kW_Demand_System_Now_Positive`, `kW_System_Max_Positive`, `kW_System_Min_Positive`, `kvarh`, `kvar_system_positive`, `kvah_system_positive`, `kva_system_positive`, `dpf_system_positive`, `amps_system_avg_positive`, `volts_line_to_line_avg_positive`, `volts_line_to_neutral_avg_positive`, `volts_l1_to_l2_positive`, `volts_l2_to_l3_positive`, `volts_l1_to_l3_positive`, `measured_line_frequency_positive`, `kwh_l1_positive`, `kwh_l2_positive`, `kwh_l3_positive`, `kw_l1_positive`, `kw_l2_positive`, `kw_l3_positive`, `kwh_system_negative`, `kw_system_negative`, `kw_demand_system_max_negative`, `kw_demand_system_now_negative`, `kw_system_max_negative`, `kw_system_min_negative`, `kvarh_system_negative`, `kvar_system_negative`, `kvah_system_negative`, `kva_system_negative`, `dpf_system_negative`, `apparent_pf_system_negative`, `amps_system_avg_negative`, `volts_line_to_line_avg_negative`, `volts_line_to_neutral_avg_negative`, `volts_l1_to_l2_negative`, `volts_l2_to_l3_negative`, `volts_l1_to_l3_negative`, `measured_line_frequency_negative`, `kwh_l1_negative`, `kwh_l2_negative`, `kwh_l3_negative`, `kw_l1_negative`, `kw_l2_negative`, `kw_l3_negative`, `created_date`) values ";
                fgetcsv($file);
                while($data = fgetcsv($file)){
                    if($data[1] == 0){
                        $strSQL.= "('".implode("', '",$data)."', NOW()),";
                    }
                }
                $strSQL =  rtrim($strSQL, ",");
                //echo $strSQL;
                $DB->Execute($strSQL);
                fclose($file);
                
                if (!unlink($folder.$filename)){
                    echo ("Error deleting $folder$filename<br>");
                }else{
                    echo ("Deleted $folder$filename<br>");
                }
                
            } else {
                echo "<br>does not exist";
            }
        }
    }
    
} else {
    echo "folder does not exist";
}