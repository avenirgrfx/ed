<?php
require_once(AbsPath."classes/all.php");
require_once(AbsPath."configure.php");

class Widgets
{
	public $strQuery;
	
	# For All Nodes
	public $system_node_id, $system_id, $year_of_creation, $node_serial, $custom_name, $client_id, $site_id, 
	$building_id, $room_id, $project_id, $doc, $created_by, $date_linked, $linked_by, $delete_flag;
	
	
	# For THN Widgets
	public $thn_widget_id, $widget_serial_number, $temperature_flag, $temperature_type, $temperature_alarm_low, $temperature_alarm_high, 
	$temperature_1, $temperature_color_1, $temperature_2, $temperature_color_2, $temperature_3, $temperature_color_3, $humidity_flag, $humidity_low,
	$humidity_high, $humidity_1, $humidity_color_1, $humidity_2, $humidity_color_2, $humidity_3, $humidity_color_3;
	
	
	public function Widgets()
	{
		
		$this->strQuery='';
		
		#All Nodes
		$this->system_node_id=0;
		$this->system_id=0;
		$this->year_of_creation=date("Y");
		$this->node_serial='';
		$this->custom_name='';
		$this->client_id=0;
		$this->site_id=0; 
		$this->building_id=0;
		$this->room_id=0;
		$this->project_id=0;
		$this->doc=date("Y-m-d");
		$this->created_by=0;
		$this->date_linked=0;
		$this->linked_by=0;
		$this->delete_flag=0;
		
		
		#THN
		$this->thn_widget_id=0;
		$this->widget_serial_number='';
		$this->temperature_flag=1;
		$this->temperature_type=1;
		$this->temperature_alarm_low=0;
		$this->temperature_alarm_high=0; 
		$this->temperature_1=45;
		$this->temperature_color_1='#000000';
		$this->temperature_2=65;
		$this->temperature_color_2='#009900';
		$this->temperature_3=95;
		$this->temperature_color_3='#FF0000';
		$this->humidity_flag=1;
		$this->humidity_low=0;
		$this->humidity_high=0;
		$this->humidity_1=45;
		$this->humidity_color_1='#000000';
		$this->humidity_2=65;
		$this->humidity_color_2='#009900';
		$this->humidity_3=95;
		$this->humidity_color_3='#FF0000';
		
		
	}
	
	
	
	function Add_THN()
	{
		$DB=new DB;
		
		$this->strQuery="Select * from t_system_node where system_node_id=".$this->system_node_id;
		$strRsNodeDetailsArr=$DB->Returns($this->strQuery);
		if($strRsNodeDetails=mysql_fetch_object($strRsNodeDetailsArr))
		{
			# Create #XXXXXX T Widget
			$this->system_id=$strRsNodeDetails->system_id;
			$this->widget_serial_number="W".$strRsNodeDetails->node_serial."T";
			$this->temperature_flag=1;
			$this->humidity_flag=0;
			
			$this->strQuery="Insert into  t_thn_widget(system_id, system_node_id, widget_serial_number, temperature_flag, temperature_type, temperature_alarm_low, temperature_alarm_high, 
			temperature_1, temperature_color_1, temperature_2, temperature_color_2, temperature_3, temperature_color_3, 
			humidity_flag, humidity_low, humidity_high, humidity_1, humidity_color_1, humidity_2, humidity_color_2, humidity_3, humidity_color_3)
			
			Values(".$this->system_id.",".$this->system_node_id.",'".$this->widget_serial_number."',". $this->temperature_flag.",". $this->temperature_type.",". $this->temperature_alarm_low.",". $this->temperature_alarm_high.",". 
			$this->temperature_1.", '".$this->temperature_color_1."',". $this->temperature_2.", '".$this->temperature_color_2."',". $this->temperature_3.", '".$this->temperature_color_3."',". 
			$this->humidity_flag.",0,0,0,'',0,'',0,'')";
			
			$DB->Execute($this->strQuery);
			
			
			
			# Create #XXXXXX H Widget
			$this->system_id=$strRsNodeDetails->system_id;
			$this->widget_serial_number="W".$strRsNodeDetails->node_serial."H";
			$this->temperature_flag=0;
			$this->humidity_flag=1;
			
			$this->strQuery="Insert into  t_thn_widget(system_id, system_node_id, widget_serial_number, temperature_flag, temperature_type, temperature_alarm_low, temperature_alarm_high, 
			temperature_1, temperature_color_1, temperature_2, temperature_color_2, temperature_3, temperature_color_3, 
			humidity_flag, humidity_low, humidity_high, humidity_1, humidity_color_1, humidity_2, humidity_color_2, humidity_3, humidity_color_3)
			
			Values(".$this->system_id.",".$this->system_node_id.",'".$this->widget_serial_number."',". $this->temperature_flag.",0,0,0,0,'',0, '',0, '',". 
			$this->humidity_flag.",". $this->humidity_low.",". $this->humidity_high.",". $this->humidity_1.", '".$this->humidity_color_1."',". $this->humidity_2.", '".$this->humidity_color_2."',". $this->humidity_3.", '".$this->humidity_color_3."')";
			
			$DB->Execute($this->strQuery);
			
		}
		
		
		# Create #XXXXXX TEX Widget
		# Create #XXXXXX HEX Widget
	}
	
	
}
?>