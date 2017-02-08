<?php

ini_set('date.timezone',"America/New_York");

class Globals
{
	public $TimeZonesArr=array();
	
	function Globals()
	{
		
	}
	
	public static function Get($strVal)
	{
		return (array_key_exists($strVal, $_REQUEST) ? $_REQUEST[$strVal] : "");
		
	}
    
    public static function GetPortfolioUsername()
	{
        if(1){
            /* Live Account */
            $adminUsername = 'usenergyengineers';
        }else{
            /* Test Account */
            $adminUsername = 'krishan';
        }
		return $adminUsername;	
	}
    
    public static function GetPortfolioPassword()
	{
        if(1){
            /* Live Account */
            $adminPassword = 'mch2ftg1!';
        }else{
            /* Test Account */
            $adminPassword = 'PA$sw0r6';
        }
		return $adminPassword;	
	}
	
	public static function Pages($strRecordPerPage,$strTotal)
	{
		$strTemp= round($strTotal/$strRecordPerPage,0);
		if($strTemp<1)
			return 1;
		if($strTemp*$strRecordPerPage<$strTotal)
			$strTemp++;
		return $strTemp;
	}
	
	
	public static function SendURL($strURL)
	{		
		header('location:'.$strURL);
	}
	
	public  static function SendMail($ToEmail,$Subject,$HTML,$strFromEmail,$strFromName)
	{
		//header=$MainURL."/".$header;
		$ob = "----=_OuterBoundary_000";
		$ib = "----=_InnerBoundery_001";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";		
		$headers .= 'From:'.$strFromName.' <'.$strFromEmail.'>' . "\r\n";	
		$headers .= 'Reply-To: '.$strFromName.' <'.$strFromEmail.'>' . "\r\n";
		$headers .= "X-Priority: 1 (Highest)\n"; 
        $headers .= "X-MSMail-Priority: High\n"; 
        $headers .= "Importance: High\n";
		
		if(mail($ToEmail,$Subject,$HTML,$headers))
			return 1;
		else
			return 0;
	 }
	
		
	 static function RandomString($strLength)
	{
		$pattern="1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";		
		for($i=0;$i<$strLength;$i++)
		{
		   if(isset($key))
			 $key .= $pattern{rand(0,61)};
		   else
			 $key = $pattern{rand(0,61)};
		 }
		  return $key;				
	}
	
	public static function RandomString_1($strLength)
	{
		$pattern="ABCDEFGHIJKLMNOPQRSTUVWXYZ";		
		for($i=0;$i<$strLength;$i++)
		{
		   if(isset($key))
			 $key .= $pattern{rand(0,26)};
		   else
			 $key = $pattern{rand(0,26)};
		 }
		  return $key;				
	}
	
	public static function DateFormat($strDate, $Time=0)
	{
		if($strDate=='1900-01-01')
		{
			return "Unknown";
		}
		
		if($Time==0)
			return date("d-M-Y",strtotime($strDate));
		else
		   
			return date("n/j/y g:i A ",strtotime($strDate))." EST";
			
			
	}
	
	
	
	public static function GetField($args)
	{
		$DB=new DB;
		
		$strQueryGetField="Select ".$args['R']." From ". $args['T']." Where ".$args['C'];		
				
		$theRet=array();			
		if($rs=mysql_fetch_object($DB->Returns($strQueryGetField)))
		{			
			$myRet=split(",",$args['R']);		
			foreach($myRet as $myRetVal)
			{
				array_push($theRet,array($myRetVal=>$rs->$myRetVal));
			}			
			return $theRet;
		}
		else
		{
			return $theRet;
		}
	}
	
	public static function PrintArray($Arr)
	{
		print "<pre>";
		print_r($Arr);
		print "</pre>";
	}
	
	public static function FileUpload($SourceFile, $DestinationPath, $SourceFileName, $UploadFileName)
	{		
		$arrAllowed=array('jpg', 'jpeg', 'png','gif','bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'swf','SWF', 'PDF', 'pdf', 'XLS', 'xls', 'doc', 'DOC');
		
		if($SourceFile && $DestinationPath && $UploadFileName)	
		{
			if(strstr($SourceFileName,"."))
			{
				$Extensions=explode(".",$SourceFileName);			
				$Extension=$Extensions[count($Extensions)-1];
			}
			
			if(in_array($Extension,$arrAllowed))
			{			
				if(move_uploaded_file($SourceFile,$DestinationPath."/".$UploadFileName.".".$Extension))
				{
					return $UploadFileName.".".$Extension;
				}
				else
				{
					return 0;
				}
			}
			else
			{
				return 0;
			}
		}
		return "0";		
	}
	
	
	public static function Resize($SourceImage, $toWidth, $toHeight, $Title='',$style='', $align='')
	{	
		if($SourceImage && $toWidth && $toHeight)
		{	
			$originalImage=$SourceImage;
			list($width, $height) = getimagesize($SourceImage);
			
			$xscale=$width/$toWidth;
			$yscale=$height/$toHeight;
			
			if($xscale==0) $xscale=1;
			if($yscale==0) $yscale=1;
			
			
			
			// Recalculate new size with default ratio
			if ($yscale>$xscale){
				$new_width = round($width * (1/$yscale));
				$new_height = round($height * (1/$yscale));
			}
			else {
				$new_width = round($width * (1/$xscale));
				$new_height = round($height * (1/$xscale));
			}
			
			if($width<$new_width && $height<$new_height)
			{
				$new_width=$width;
				$new_height=$height;
			}
			
			if($Title<>'')
				$strTitle="alt='".$Title."' title='".$Title."'";
			
			if($style<>'')
				$strStyle="Style='".$style."'";
			
			if($align<>'')
				$strAlign='align="'.$align.'"';
				
			return "<img src='".$SourceImage."' width='".$new_width."' height='".$new_height."' ".$strTitle." ". $strStyle. " " . $strAlign ." />";
			
			return "<img src='".$SourceImage."' width='".$toWidth."' height='".$toHeight."' />";
			
		}
		return "";
	}
	
	public static function Money($amount)
	{
		return "$".number_format($amount,2);
	}
	
	
	
	
	public static function PrintDescription($str, $cut_upto=0)
	{
		
		if(strlen($str)>$cut_upto)
		{
			$str_print = substr($str,0,$cut_upto);
			if($str_print{strlen($str_print)-1} == ' ' ||$str_print{strlen($str_print)-1} == '.')
				return stripslashes($str_print.'...');
			else {
				$cut_upto_bspace = strpos($str, ' ',  $cut_upto+1);//at first try with space
				$cut_upto_stop = strpos($str, '.',  $cut_upto+1);
				if ($cut_upto_bspace !== false && $cut_upto_stop !== false) {
						//now let us compare which position comes first
						$cut_upto = $cut_upto_bspace > $cut_upto_stop ? $cut_upto_stop : $cut_upto_bspace ;
				}
				elseif($cut_upto_bspace !== false) {
						$cut_upto = $cut_upto_bspace;
				}
				elseif($cut_upto_stop !== false) {
						$cut_upto = $cut_upto_stop;
				}
				else {
					
					$cut_upto_bspace = strrpos($str_print, ' ');//at first try with space
					$cut_upto_stop = strrpos($str_print, '.');
					if ($cut_upto_bspace !== false && $cut_upto_stop !== false) {
						//now let us compare which position comes first
							$cut_upto = $cut_upto_bspace < $cut_upto_stop ? $cut_upto_bspace : $cut_upto_stop ;
					}
					elseif($cut_upto_bspace !== false) {
							$cut_upto = $cut_upto_bspace;
					}
					elseif($cut_upto_stop !== false) {
							$cut_upto = $cut_upto_stop;
					}
					else {
						
					}
					
				}
		
				return stripslashes(substr($str,0,$cut_upto).'...');
			}
			
		} 
		else {
			//less than $cut_upto characters. No need to cut it.
			return stripslashes($str.'...' );
	
		}
	}
	
	
	
	public static function PrintDescription_1($str, $cut_upto=0)
	{
		if(strlen($str)<=$cut_upto)
			return $str;
		
		return substr($str,0,$cut_upto)."...";
		
	}
	
	
	 public static function AddDate($givendate,$day=0,$mth=0,$yr=0) {
			  $cd = strtotime($givendate);
			  $newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
											date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
											date('d',$cd)+$day, date('Y',$cd)+$yr));
      return $newdate;
    }
	
	
	
	public function Days($iDay)
	{
		print '<option value="">Day</option>';
		for($iCtr=1; $iCtr<=31; $iCtr++)
		{
			if($iDay==$iCtr)
				$selected='Selected="Selected"';
			else
				$selected="";
			print '<option value="'.$iCtr.'" '.$selected.'>'.($iCtr>9 ? $iCtr : '0'.$iCtr).'</option>';
		}
	}
	
	public function Month($iMonth)
	{
		print '<option value="">Month</option>';
		for($iCtr=1; $iCtr<=12; $iCtr++)
		{
			if($iMonth==$iCtr)
				$selected='Selected="Selected"';
			else
				$selected="";
			print '<option value="'.$iCtr.'" '.$selected.'>'.($iCtr>9 ? $iCtr : '0'.$iCtr).'</option>';
		}
	}
	
	
	public function Year($iYear, $StartYear=2013, $EndYear=2016)
	{
		print '<option value="">Select Year</option>';
		for($iCtr=$StartYear; $iCtr<=$EndYear; $iCtr++)
		{
			if($iYear==$iCtr)
				$selected='Selected="Selected"';
			else
				$selected="";
			print '<option value="'.$iCtr.'" '.$selected.'>'.$iCtr.'</option>';
		}
	}
	
	
	public static function TimeZoneList($time_zone)
	{
		
		$TimeZonesArr= array (
		'(UTC-11:00) Midway Island' => 'Pacific/Midway',
		'(UTC-11:00) Samoa' => 'Pacific/Samoa',
		'(UTC-10:00) Hawaii' => 'Pacific/Honolulu',
		'(UTC-09:00) Alaska' => 'US/Alaska',
		'(UTC-08:00) Pacific Time (US &amp; Canada)' => 'America/Los_Angeles',
		'(UTC-08:00) Tijuana' => 'America/Tijuana',
		'(UTC-07:00) Arizona' => 'US/Arizona',
		'(UTC-07:00) Chihuahua' => 'America/Chihuahua',
		'(UTC-07:00) La Paz' => 'America/Chihuahua',
		'(UTC-07:00) Mazatlan' => 'America/Mazatlan',
		'(UTC-07:00) Mountain Time (US &amp; Canada)' => 'US/Mountain',
		'(UTC-06:00) Central America' => 'America/Managua',
		'(UTC-06:00) Central Time (US &amp; Canada)' => 'US/Central',
		'(UTC-06:00) Guadalajara' => 'America/Mexico_City',
		'(UTC-06:00) Mexico City' => 'America/Mexico_City',
		'(UTC-06:00) Monterrey' => 'America/Monterrey',
		'(UTC-06:00) Saskatchewan' => 'Canada/Saskatchewan',
		'(UTC-05:00) Bogota' => 'America/Bogota',
		'(UTC-05:00) Eastern Time (US &amp; Canada)' => 'US/Eastern',
		'(UTC-05:00) Indiana (East)' => 'US/East-Indiana',
		'(UTC-05:00) Lima' => 'America/Lima',
		'(UTC-05:00) Quito' => 'America/Bogota',
		'(UTC-04:00) Atlantic Time (Canada)' => 'Canada/Atlantic',
		'(UTC-04:30) Caracas' => 'America/Caracas',
		'(UTC-04:00) La Paz' => 'America/La_Paz',
		'(UTC-04:00) Santiago' => 'America/Santiago',
		'(UTC-03:30) Newfoundland' => 'Canada/Newfoundland',
		'(UTC-03:00) Brasilia' => 'America/Sao_Paulo',
		'(UTC-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
		'(UTC-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
		'(UTC-03:00) Greenland' => 'America/Godthab',
		'(UTC-02:00) Mid-Atlantic' => 'America/Noronha',
		'(UTC-01:00) Azores' => 'Atlantic/Azores',
		'(UTC-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
		'(UTC+00:00) Casablanca' => 'Africa/Casablanca',
		'(UTC+00:00) Edinburgh' => 'Europe/London',
		'(UTC+00:00) Greenwich Mean Time : Dublin' => 'Etc/Greenwich',
		'(UTC+00:00) Lisbon' => 'Europe/Lisbon',
		'(UTC+00:00) London' => 'Europe/London',
		'(UTC+00:00) Monrovia' => 'Africa/Monrovia',
		'(UTC+00:00) UTC' => 'UTC',
		'(UTC+01:00) Amsterdam' => 'Europe/Amsterdam',
		'(UTC+01:00) Belgrade' => 'Europe/Belgrade',
		'(UTC+01:00) Berlin' => 'Europe/Berlin',
		'(UTC+01:00) Bern' => 'Europe/Berlin',
		'(UTC+01:00) Bratislava' => 'Europe/Bratislava',
		'(UTC+01:00) Brussels' => 'Europe/Brussels',
		'(UTC+01:00) Budapest' => 'Europe/Budapest',
		'(UTC+01:00) Copenhagen' => 'Europe/Copenhagen',
		'(UTC+01:00) Ljubljana' => 'Europe/Ljubljana',
		'(UTC+01:00) Madrid' => 'Europe/Madrid',
		'(UTC+01:00) Paris' => 'Europe/Paris',
		'(UTC+01:00) Prague' => 'Europe/Prague',
		'(UTC+01:00) Rome' => 'Europe/Rome',
		'(UTC+01:00) Sarajevo' => 'Europe/Sarajevo',
		'(UTC+01:00) Skopje' => 'Europe/Skopje',
		'(UTC+01:00) Stockholm' => 'Europe/Stockholm',
		'(UTC+01:00) Vienna' => 'Europe/Vienna',
		'(UTC+01:00) Warsaw' => 'Europe/Warsaw',
		'(UTC+01:00) West Central Africa' => 'Africa/Lagos',
		'(UTC+01:00) Zagreb' => 'Europe/Zagreb',
		'(UTC+02:00) Athens' => 'Europe/Athens',
		'(UTC+02:00) Bucharest' => 'Europe/Bucharest',
		'(UTC+02:00) Cairo' => 'Africa/Cairo',
		'(UTC+02:00) Harare' => 'Africa/Harare',
		'(UTC+02:00) Helsinki' => 'Europe/Helsinki',
		'(UTC+02:00) Istanbul' => 'Europe/Istanbul',
		'(UTC+02:00) Jerusalem' => 'Asia/Jerusalem',
		'(UTC+02:00) Kyiv' => 'Europe/Helsinki',
		'(UTC+02:00) Pretoria' => 'Africa/Johannesburg',
		'(UTC+02:00) Riga' => 'Europe/Riga',
		'(UTC+02:00) Sofia' => 'Europe/Sofia',
		'(UTC+02:00) Tallinn' => 'Europe/Tallinn',
		'(UTC+02:00) Vilnius' => 'Europe/Vilnius',
		'(UTC+03:00) Baghdad' => 'Asia/Baghdad',
		'(UTC+03:00) Kuwait' => 'Asia/Kuwait',
		'(UTC+03:00) Minsk' => 'Europe/Minsk',
		'(UTC+03:00) Nairobi' => 'Africa/Nairobi',
		'(UTC+03:00) Riyadh' => 'Asia/Riyadh',
		'(UTC+03:00) Volgograd' => 'Europe/Volgograd',
		'(UTC+03:30) Tehran' => 'Asia/Tehran',
		'(UTC+04:00) Abu Dhabi' => 'Asia/Muscat',
		'(UTC+04:00) Baku' => 'Asia/Baku',
		'(UTC+04:00) Moscow' => 'Europe/Moscow',
		'(UTC+04:00) Muscat' => 'Asia/Muscat',
		'(UTC+04:00) St. Petersburg' => 'Europe/Moscow',
		'(UTC+04:00) Tbilisi' => 'Asia/Tbilisi',
		'(UTC+04:00) Yerevan' => 'Asia/Yerevan',
		'(UTC+04:30) Kabul' => 'Asia/Kabul',
		'(UTC+05:00) Islamabad' => 'Asia/Karachi',
		'(UTC+05:00) Karachi' => 'Asia/Karachi',
		'(UTC+05:00) Tashkent' => 'Asia/Tashkent',
		'(UTC+05:30) Chennai' => 'Asia/Calcutta',
		'(UTC+05:30) Kolkata' => 'Asia/Kolkata',
		'(UTC+05:30) Mumbai' => 'Asia/Calcutta',
		'(UTC+05:30) New Delhi' => 'Asia/Calcutta',
		'(UTC+05:30) Sri Jayawardenepura' => 'Asia/Calcutta',
		'(UTC+05:45) Kathmandu' => 'Asia/Katmandu',
		'(UTC+06:00) Almaty' => 'Asia/Almaty',
		'(UTC+06:00) Astana' => 'Asia/Dhaka',
		'(UTC+06:00) Dhaka' => 'Asia/Dhaka',
		'(UTC+06:00) Ekaterinburg' => 'Asia/Yekaterinburg',
		'(UTC+06:30) Rangoon' => 'Asia/Rangoon',
		'(UTC+07:00) Bangkok' => 'Asia/Bangkok',
		'(UTC+07:00) Hanoi' => 'Asia/Bangkok',
		'(UTC+07:00) Jakarta' => 'Asia/Jakarta',
		'(UTC+07:00) Novosibirsk' => 'Asia/Novosibirsk',
		'(UTC+08:00) Beijing' => 'Asia/Hong_Kong',
		'(UTC+08:00) Chongqing' => 'Asia/Chongqing',
		'(UTC+08:00) Hong Kong' => 'Asia/Hong_Kong',
		'(UTC+08:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
		'(UTC+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
		'(UTC+08:00) Perth' => 'Australia/Perth',
		'(UTC+08:00) Singapore' => 'Asia/Singapore',
		'(UTC+08:00) Taipei' => 'Asia/Taipei',
		'(UTC+08:00) Ulaan Bataar' => 'Asia/Ulan_Bator',
		'(UTC+08:00) Urumqi' => 'Asia/Urumqi',
		'(UTC+09:00) Irkutsk' => 'Asia/Irkutsk',
		'(UTC+09:00) Osaka' => 'Asia/Tokyo',
		'(UTC+09:00) Sapporo' => 'Asia/Tokyo',
		'(UTC+09:00) Seoul' => 'Asia/Seoul',
		'(UTC+09:00) Tokyo' => 'Asia/Tokyo',
		'(UTC+09:30) Adelaide' => 'Australia/Adelaide',
		'(UTC+09:30) Darwin' => 'Australia/Darwin',
		'(UTC+10:00) Brisbane' => 'Australia/Brisbane',
		'(UTC+10:00) Canberra' => 'Australia/Canberra',
		'(UTC+10:00) Guam' => 'Pacific/Guam',
		'(UTC+10:00) Hobart' => 'Australia/Hobart',
		'(UTC+10:00) Melbourne' => 'Australia/Melbourne',
		'(UTC+10:00) Port Moresby' => 'Pacific/Port_Moresby',
		'(UTC+10:00) Sydney' => 'Australia/Sydney',
		'(UTC+10:00) Yakutsk' => 'Asia/Yakutsk',
		'(UTC+11:00) Vladivostok' => 'Asia/Vladivostok',
		'(UTC+12:00) Auckland' => 'Pacific/Auckland',
		'(UTC+12:00) Fiji' => 'Pacific/Fiji',
		'(UTC+12:00) International Date Line West' => 'Pacific/Kwajalein',
		'(UTC+12:00) Kamchatka' => 'Asia/Kamchatka',
		'(UTC+12:00) Magadan' => 'Asia/Magadan',
		'(UTC+12:00) Marshall Is.' => 'Pacific/Fiji',
		'(UTC+12:00) New Caledonia' => 'Asia/Magadan',
		'(UTC+12:00) Solomon Is.' => 'Asia/Magadan',
		'(UTC+12:00) Wellington' => 'Pacific/Auckland',
		'(UTC+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
		);
		
		if(is_array($TimeZonesArr) && count($TimeZonesArr)>0)
		{
			print "<option value=''>Select Timezone</option>";
			foreach($TimeZonesArr as $key=>$Val)
			{
				if($time_zone==$Val)
					$Selected="selected='selected'";
				else
					$Selected="";
				print "<option value='".$Val."' ".$Selected.">".$key."</option>";
			}
		}
	}
	
	
	public static function TimeConvert($strTime)
	{
		# AM to PM
		if(strstr($strTime, 'pm'))
		{
			$strTime=str_replace('pm','',$strTime);
			$strTimeArr=explode(":",$strTime);
			$strTime=(12+$strTimeArr[0]).":".$strTimeArr[1];
			return $strTime;
		}
		else
		{
			$strTime=str_replace('am','',$strTime);
			return $strTime;
		}
	}
	
	
	
	public function GenWidgetSerial($strWidgetType, $strNodeSerialNumber)
	{
		$DB=New DB;
		$strWidgetID='W';
		if($strWidgetType=='thn')
		{
			# For Temperature and Humidity
			$strWidgetID.='THN';
			//$strWidgetID.=date('y');
			$strWidgetID.=$strNodeSerialNumber;
			
			$strSQL="Select count(*) as Total from t_thn_widget where widget_serial_number like '$strWidgetID%'";
			$strRsWidgetNumber=$DB->Returns($strSQL);
			
			if(mysql_num_rows($strRsWidgetNumber)>0)
			{
				if($strWidgetNumber=mysql_fetch_object($strRsWidgetNumber))
				{
					$strTempSerial=$strWidgetNumber->Total+1;
					if($strTempSerial<10)
						$strTempSerial="0".$strTempSerial;
					$strWidgetID.=$strTempSerial;
				}
			}			
			else
			{
				$strWidgetID.='01';
			}
			return $strWidgetID;
		}
	}
	
	
	
	
	public function ShowWidgetSerialBeforeLinked($strWidgetType)
	{
		$DB=New DB;
		$strWidgetID='W';
		if($strWidgetType=='thn')
		{
			# For Temperature and Humidity
			$strWidgetID.='THN';
			$strWidgetID.=date('y')."0000";
			return $strWidgetID;
		}
	}
	
	
	public function ConvertTemp($strTemp, $strType=1)
	{
		# If F then no conversion
		if($strType==1)
			return $strTemp;
		
		# F to C
		$strTemp=($strTemp-32)*(5/9);
		$strTemp=round($strTemp, 2);
		return $strTemp;
	}
	
	public function ConvertTempToSave($strTemp, $strType=1)
	{
		# If F then no conversion
		if($strType==1)
			return $strTemp;
		
		# C to F
		$strTemp=$strTemp+32;
		//$strTemp=($strTemp*(9/5))+32;
		$strTemp=round($strTemp, 2);		
		return $strTemp;
	}
	
	public function GetSystemIDs($strParentID, $Level=1)
	{
		$DB=new DB;
		$arr=array();
		$strSQL="Select system_id, parent_id from t_system where parent_id=$strParentID";
		$strRsSystemIDsArr=$DB->Returns($strSQL);
		while($strRsSystemIDs=mysql_fetch_object($strRsSystemIDsArr))
		{
			$arr[]= $strRsSystemIDs->system_id;
			if($Level>1)
			{
				$strParentID=$strRsSystemIDs->system_id;
				
				$strSQL="Select system_id, parent_id from t_system where parent_id=$strParentID";
				$strRsSystemIDsArr1=$DB->Returns($strSQL);
				while($strRsSystemIDs1=mysql_fetch_object($strRsSystemIDsArr1))
				{
					$arr[]= $strRsSystemIDs1->system_id;
					if($Level>2)
					{
						$strParentID=$strRsSystemIDs1->system_id;
						
						$strSQL="Select system_id, parent_id from t_system where parent_id=$strParentID";
						$strRsSystemIDsArr2=$DB->Returns($strSQL);
						while($strRsSystemIDs2=mysql_fetch_object($strRsSystemIDsArr2))
						{
							$arr[]= $strRsSystemIDs2->system_id;
							
							if($Level>3)
							{
								$strParentID=$strRsSystemIDs2->system_id;
								$strSQL="Select system_id, parent_id from t_system where parent_id=$strParentID";
								$strRsSystemIDsArr3=$DB->Returns($strSQL);
								while($strRsSystemIDs3=mysql_fetch_object($strRsSystemIDsArr3))
								{
									$arr[]= $strRsSystemIDs3->system_id;
								}
							}
							
						}
					}
				}
			}
		}
		return $arr;
	}
	
	
	
	public function MarginalValueCalcBySystem($strSystemSerial, $FiledToCalculate, $StartDate, $EndDate, $DateComparisonField='synctime')
	{
		$DB = new DB;
        $strSQL="Select min($FiledToCalculate) as StartVal, max($FiledToCalculate) as EndVal from $strSystemSerial where $DateComparisonField >='$StartDate' and $DateComparisonField <='$EndDate'";
		$strRsValueArr=$DB->Returns($strSQL);
		if($strRsValue=mysql_fetch_object($strRsValueArr))
		{
			$StartVal=$strRsValue->StartVal;
            $EndVal=$strRsValue->EndVal;
            return ($EndVal-$StartVal);
        }else{
            return 0;
        }
//		$strSQL1="Select $FiledToCalculate as StartVal from $strSystemSerial where $DateComparisonField >='$StartDate' Order By $DateComparisonField ASC  LIMIT 1";
//		$strRsStartValueArr=$DB->Returns($strSQL1);
//		if($strRsStartValue=mysql_fetch_object($strRsStartValueArr))
//		{
//			$StartVal=$strRsStartValue->StartVal;
//		}
//		
//		$strSQL2="Select $FiledToCalculate as EndVal from $strSystemSerial where $DateComparisonField <='$EndDate' Order By $DateComparisonField DESC  LIMIT 1";
//		$strRsEndValueArr=$DB->Returns($strSQL2);
//		if($strRsEndValue=mysql_fetch_object($strRsEndValueArr))
//		{
//			$EndVal=$strRsEndValue->EndVal;
//		}
		
		//return ($EndVal-$StartVal);
		
	}
    
    public function CallAPI($method, $url, $data = false)
    {	
        if(1){
            $url = str_replace('/wstest', '/ws', $url);
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/xml'));  
        
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
    
    public function GetTimezoneCode($timezone)
	{
        $TimeZonesCodeArr=array(
            'AST' => 'America/Halifax',
            'EST' => 'EST5EDT',
            'CST' => 'CST6CDT',
            'MST' => 'MST7MDT',
            'PST' => 'PST8PDT',
            'AKST' => 'America/Anchorage',
            'HAST' => 'Pacific/Honolulu',
            'SST' => 'US/Samoa',
            'ChST' => 'Pacific/Guam'
        );
        
        if($timezone == "")
            $timezone = 'AST';
        return $TimeZonesCodeArr[$timezone];
	}
    
    public function GetTimezoneOffset($timezone, $dst)
	{
        $TimeZonesOffsetArr=array(
            'AST' => -4,
            'EST' => -5,
            'CST' => -6,
            'MST' => -7,
            'PST' => -8,
            'AKST' => -9,
            'HAST' => -10,
            'SST' => -11,
            'ChST' => 10
        );

        $TimeZonesOffsetDSArr=array(
            'AST' => -3,
            'EST' => -4,
            'CST' => -5,
            'MST' => -6,
            'PST' => -7,
            'AKST' => -8,
            'HAST' => -9,
            'SST' => -11,
            'ChST' => 10
        );
        
        if($timezone == "")
            $timezone = 'AST';
        if($dst == 1)
            return $TimeZonesOffsetDSArr[$timezone];
        else
            return $TimeZonesOffsetArr[$timezone];
	}
	
	
}
?>
