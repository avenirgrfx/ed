<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/xml; charset=utf-8" />
<title>REST</title>
</head>

<body>


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

# Creating a Test Account
$CreateAccount='
<account>
<username>w3corner</username>
<password>PA$sw0r6</password>
<webserviceUser>true</webserviceUser>
<searchable>true</searchable>
<contact>
<firstName>Web</firstName>
<lastName>Corner</lastName>
<address address1="5088 Corporate Exchange" city="Grand Rapids"
state="MI" postalCode="49512" country="US"/>
<email>w3corner@gmail.com</email>
<phone>6165547271</phone>
<jobTitle>Building Administrator Data Exchange User</jobTitle>
</contact>
<organization name="ACME Corporation">
<primaryBusiness>Other</primaryBusiness>
<otherBusinessDescription>other</otherBusinessDescription>
<energyStarPartner>true</energyStarPartner>
<energyStarPartnerType>Service and Product Providers</energyStarPartnerType>
</organization>
<securityAnswers>
<securityAnswer>
<question id="-1"/>
<answer>New York</answer>
</securityAnswer>
<securityAnswer>
<question id="-2"/>
<answer>Main St</answer>
</securityAnswer>
</securityAnswers>

</account>';
//print CallAPI("POST","https://portfoliomanager.energystar.gov/wstest/account", $CreateAccount);



# Adding a Customer
$CreateCustomer='
<account>
<username>w3customer</username>
<password>PA$sw0r6</password>
<webserviceUser>true</webserviceUser>
<searchable>true</searchable>

<contact>
	<address country="US" postalCode="22201" state="VA" city="Arlington" address1="123 Main St"/>
	<firstName>Customer FirstName</firstName>
	<email>fgoto@zibako.com</email>
	<lastName>Customer LastName</lastName>
	<jobTitle>Building Administrator</jobTitle>
	<phone>703-555-2121</phone>
</contact>

<organization name="Test Corporation">
	<primaryBusiness>Other</primaryBusiness>
	<otherBusinessDescription>other</otherBusinessDescription>
	<energyStarPartner>true</energyStarPartner>
	<energyStarPartnerType>Small Businesses</energyStarPartnerType>
</organization>

<securityAnswers>
	<securityAnswer>
		<question id="-1"/>
		<answer>New York</answer>
	</securityAnswer>
	<securityAnswer>
		<question id="-2"/>
		<answer>Main St</answer>
		</securityAnswer>
</securityAnswers>

</account>';
//print CallAPI("POST","https://portfoliomanager.energystar.gov/wstest/customer", $CreateCustomer);

# Customer ID = 96650 for w3customer




# Adding a Property
$CreateProperty='
<property>
<name>Broadway School</name>
<constructionStatus>Existing</constructionStatus>
<primaryFunction>K-12 School</primaryFunction>
<grossFloorArea temporary="true" units="Square Feet">
<value>10000</value>
</grossFloorArea>
<yearBuilt>2000</yearBuilt>
<address postalCode="22201" address1="12321 Main Street" city="Arlington"
state="VA" country="US"/>
<numberOfBuildings>5</numberOfBuildings>
<isFederalProperty>false</isFederalProperty>
<occupancyPercentage>55</occupancyPercentage>
</property>
';
echo  CallAPI("POST","https://portfoliomanager.energystar.gov/wstest/account/100/property", $CreateProperty);

?>
</body>
</html>
