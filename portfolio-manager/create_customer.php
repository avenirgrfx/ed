<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/xml; charset=utf-8" />
<title>REST</title>
</head>

<body>
<?php
require_once("function.php");
# Creating a Test Account

$username='usenergyengineers1';
$password='PA$sw0r6';
$email='fgoto@usenergyengineers.com';


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
	<email>fgoto@zibako1.com</email>
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
print CallAPI("POST","https://portfoliomanager.energystar.gov/wstest/customer", $CreateCustomer);
?>
</body>
</html>