<?php
function CallAPI($method, $url, $data = false)
{	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/xml'));  
   

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}
?>