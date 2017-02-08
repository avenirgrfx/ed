<?php

ob_start();
require_once('configure.php');
require_once(AbsPath . 'classes/all.php');

date_default_timezone_set('GMT');

 $start_time = strtotime("-5 minutes");
$end_time = strtotime(date('Y-m-d H:i:s'));

//echo $start_time;
//echo '<br>';
//echo $end_time;
//echo '<br><br>';

$DB = new DB;

$strSQL = "select node_serial, available_system_node_serial from t_system_node where node_serial <> ''";
//echo $strSQL;
$nodesArr = $DB->Lists(array("Query" => $strSQL));
//print_r($nodesArr);exit;

foreach ($nodesArr as $node) {
echo "http://54.200.209.3:8080/consumer/$node->node_serial/$start_time/$end_time";
    //print_r($node->available_system_node_serial);
//var_dump(extension_loaded('curl'));exit;
    if ($node->available_system_node_serial != '') {
        $ch = curl_init();
		//var_dump($ch);
        curl_setopt($ch, CURLOPT_URL, "http://54.200.209.3:8080/consumer/$node->node_serial/$start_time/$end_time");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/xml'));
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result;
        $result = json_decode($result);

      //  var_dump($result);

        if (sizeof($result)) {
            $selectSQL = "SELECT kwhsystem FROM `t_$node->available_system_node_serial` order by synctime desc limit 1";
            $result_kwh = $DB->Returns($selectSQL);
            if ($resultARR = mysql_fetch_object($result_kwh)) {
                $last_kwh = floatval($resultARR->kwhsystem);
            } else {
                $last_kwh = 0;
            }
          //  if("t_".$node->available_system_node_serial=="t_lhnode_EDAB160002A"){
          $strSQL = "INSERT IGNORE INTO t_$node->available_system_node_serial (`synctime`, `kwhsystem`, `phase1_volts`, `phase2_volts`, `phase3_volts`, `phase1_amps`, `phase2_amps`, `phase3_amps`, `phase1_kw`, `phase2_kw`, `phase3_kw`, `average_volts`, `average_amps`, `sum_amps`, `frequency`, `total_kw`, `max_total_kw`, `neutral_amps_demand`, `max_neutral_amps_demand`, `phase1_amps_demand`, `phase2_amps_demand`, `phase3_amps_demand`, `max_phase1_amps_demand`, `max_phase2_amps_demand`, `max_phase3_amps_demand`,`temperature`,`humidity`, `created_date`,`kwh_diff`) VALUES ";
            
            foreach ($result as $reading) {
                 
                $sdm = $reading->node_data->sdm630;

                $dht = $reading->node_data->dht22;
                if ($last_kwh == 0) {
                     if( $sdm->kwh!=""){
                           $last_kwh = $sdm->kwh;
                     }           
                     else{
                         $last_kwh=0;
                         $sdm->kwh=0;
                     }
                }
               
                $reading->timestamp;
                $diff = $sdm->kwh-$last_kwh;
                $strSQL.= "('" . $reading->timestamp . "', '" . $sdm->kwh. "', '" . $sdm->phase1_volts . "', '" . $sdm->phase2_volts . "', '" . $sdm->phase3_volts . "', '" . $sdm->phase1_amps . "', '" . $sdm->phase2_amps . "', '" . $sdm->phase3_amps . "', '" . $sdm->phase1_kw . "', '" . $sdm->phase2_kw . "', '" . $sdm->phase3_kw . "', '" . $sdm->average_volts . "', '" . $sdm->average_amps . "', '" . $sdm->sum_amps . "', '" . $sdm->frequency . "', '" . $sdm->total_kw . "', '" . $sdm->max_total_kw . "', '" . $sdm->neutral_amps_demand . "', '" . $sdm->max_neutral_amps_demand . "', '" . $sdm->phase1_amps_demand . "', '" . $sdm->phase2_amps_demand . "', '" . $sdm->phase3_amps_demand . "', '" . $sdm->max_phase1_amps_demand . "', '" . $sdm->max_phase2_amps_demand . "', '" . $sdm->max_phase3_amps_demand . "', '" . $dht->temperature . "', '" . $dht->humidity . "', NOW(),"."$diff),";
                $last_kwh = $sdm->kwh;
            }

          // print_r($result);

          echo $strSQL = rtrim($strSQL, ",");
           // }       
            $DB->Execute($strSQL);
        }
    }
}