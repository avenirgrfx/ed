<?php
ob_start();
session_start();
require_once('../../configure.php');
require_once(AbsPath.'classes/all.php');
require_once(AbsPath.'classes/building.class.php');

$DB=new DB;

$building_id = $_GET['building_id'];
$year = $_GET['year'];

$stationzip = '00000';
$strSQL="Select zip from t_building where building_id=".$building_id;
$strZipArr=$DB->Returns($strSQL);
while($strZip=mysql_fetch_object($strZipArr)){
    $stationzip = $strZip->zip;
}

//$stationzip = '02532';
//$year = 2015;

//print $stationzip;
//print "<br/>";

//Dataspecs
$dataspecs = '<DatedDataSpec key="monthlyHDD">
                <HeatingDegreeDaysCalculation>
                    <FahrenheitBaseTemperature>65</FahrenheitBaseTemperature>
                </HeatingDegreeDaysCalculation>
                <MonthlyBreakdown>
                    <DayRangePeriod>
                        <DayRange first="'.$year.'-01-01" last="'.$year.'-12-31"/>
                    </DayRangePeriod>
                </MonthlyBreakdown>
            </DatedDataSpec>
            <DatedDataSpec key="monthlyCDD">
                <CoolingDegreeDaysCalculation>
                    <FahrenheitBaseTemperature>65</FahrenheitBaseTemperature>
                </CoolingDegreeDaysCalculation>
                <MonthlyBreakdown>
                    <DayRangePeriod>
                        <DayRange first="'.$year.'-01-01" last="'.$year.'-12-31"/>
                    </DayRangePeriod>
                </MonthlyBreakdown>
            </DatedDataSpec>';

// The test API access keys are described at http://www.degreedays.net/api/test
// They'll let you access data from the Cape Cod area only.
// Enter your own API access keys to fetch data from locations worldwide.
$accountKey = 'test-test-test';
$securityKey = 'test-test-test-test-test-test-test-test-test-test-test-test-test';
$url = 'http://apiv1.degreedays.net/xml';
$timestamp = gmdate('c');
$random = uniqid();

// See http://www.degreedays.net/api/xml for more on the options you can specify
// in the XML request.  The example below is very basic; you can also:
//  - fetch data from a specific station ID, longitude/latitude location, or
//    postal code (for countries worldwide).
//  - fetch daily/weekly/monthly/yearly/average HDD and CDD covering a period
//    of your choice.
//  - fetch HDD and CDD, in multiple base temperatures, all in one request.
$requestXml = '
<RequestEnvelope>
    <SecurityInfo>
        <Endpoint>' . $url . '</Endpoint>
        <AccountKey>' . $accountKey . '</AccountKey>
        <Timestamp>' . $timestamp . '</Timestamp>
        <Random>' . $random . '</Random>
    </SecurityInfo>
    <LocationDataRequest>
        <PostalCodeLocation>
            <PostalCode>'.$stationzip.'</PostalCode>
            <CountryCode>US</CountryCode>
        </PostalCodeLocation>
		<DataSpecs>
        '.$dataspecs.'
		</DataSpecs>
    </LocationDataRequest>
</RequestEnvelope>';

//echo "<br/>";

//echo $requestXml;

// To get hash_hmac (below) to work on older versions of PHP (pre-5.1.2) you may
// need to install the PECL HASH extension.  On Windows we found we needed to
// enable it by adding the following line to the appropriate section of php.ini:
// extension=php_hash.dll
$signatureBytes = hash_hmac('sha256', $requestXml, $securityKey, true);

function base64url_encode($unencoded) { 
    return rtrim(strtr(base64_encode($unencoded), '+/', '-_'), '='); 
}

$requestParameters = array(
    'request_encoding' => 'base64url',
    'signature_method' => 'HmacSHA256',
    'signature_encoding' => 'base64url',
    'encoded_request' => base64url_encode($requestXml),
    'encoded_signature' => base64url_encode($signatureBytes)
);

// If curl_init() fails, you may need to add or uncomment the following line in
// the appropriate section of php.ini:
// extension=php_curl.dll
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestParameters));
if (defined('CURLOPT_ENCODING')) {
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$responseXml = curl_exec($ch);
curl_close($ch);

//echo $responseXml;

$xml = new SimpleXMLElement($responseXml);

$i = 0;
foreach($xml->LocationDataResponse->DataSets->DatedDataSet->Values->V as $val){
    //$date = new DateTime($val['d']);
    $dateDE = $val['d'];
    $dateUS = \DateTime::createFromFormat("Y-m-d", $dateDE)->format("M-y");
    $degreeData .='<tr>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">'.$dateUS.'</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">'.$val.'</td>
                    <td align="center" valign="middle" style="border:1px solid #CCCCCC;">'.$xml->LocationDataResponse->DataSets->DatedDataSet[1]->Values->V[$i].'</td>
                   </tr>';
    $csv_data .=$dateUS.",";
    $csv_data .=$val."\n";
    $i++;
}

$degreeTabData = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr style="background-color:#000000; font-weight:bold; color:#FFFFFF;">
                        <td colspan="3" align="center" valign="middle" style="border:1px solid #CCCCCC;">Degree days</td>
                    </tr>
                    <tr style="background-color:#EFEFEF; font-weight:bold;">
                        <td colspan="3" align="center" valign="middle" style="border:1px solid #CCCCCC;">Station - '.$xml->LocationDataResponse->Head->StationId.'</td>
                    </tr>
                    <tr style="background-color:#EFEFEF; font-weight:bold;">
                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">Month</td>
                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">HDD</td>
                        <td align="center" valign="middle" style="border:1px solid #CCCCCC;">CDD</td>
                    </tr>
                    '.$degreeData .'
                </table>';


echo $degreeTabData;
exit;
?>