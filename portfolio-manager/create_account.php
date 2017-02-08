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


$CreateAccount='
<account>
<username>'.$username.'</username>
<password>'.$password.'</password>
<webserviceUser>true</webserviceUser>
<searchable>true</searchable>
<contact>
<firstName>Web</firstName>
<lastName>Corner</lastName>
<address address1="5088 Corporate Exchange" city="Grand Rapids"
state="MI" postalCode="49512" country="US"/>
<email>'.$email.'</email>
<phone>6165547271</phone>
<jobTitle>Building Administrator Data Exchange User</jobTitle>
</contact>
<organization name="US Energy Engineers">
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
print CallAPI("POST","https://portfoliomanager.energystar.gov/wstest/account", $CreateAccount);
?>
</body>
</html>