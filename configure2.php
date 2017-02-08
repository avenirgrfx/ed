<?php

error_reporting(0);

#Database Settings

if($_SERVER['HTTP_HOST']=='54.201.91.181'){
    define('Database_Host','localhost');
    define('Database_User','root');
    define('Database_Password','K$[=Sv6/*H82+qt&');
    define('Database_Name','db_energydas');
}else{
    define('Database_Host','localhost');
    define('Database_User','root');
    define('Database_Password','123456');
    define('Database_Name','db_energydas');
}


#Site Settings
define('Website_Name','Element Designer');
define('Website_Title', Website_Name);
define('Business_Name', '');
define('TitleConnector',' | ');
define('Keywords','');
define('Description','');

define('AdminURL','/myadmin/');

//define('URL','http://localhost/Elance/editor/final/');
//$dir_path = str_replace( $_SERVER['DOCUMENT_ROOT'], "", dirname(realpath(__FILE__)) ) . DIRECTORY_SEPARATOR; 
if($_SERVER['HTTP_HOST']=='www.energydas.com' || $_SERVER['HTTP_HOST']=='energydas.com'){
    define('URL','http://www.energydas.com/');
}else{
    define('URL','http://'.$_SERVER['HTTP_HOST'].'/EnergyDAS-/');
}
$dir_path = dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR; 
define('AbsPath',$dir_path);

define("From_Name","Administrator");
define("From_Email","abc@yahoo.com");
define("AdminEmail","abc@yahoo.com");
date_default_timezone_set('EST');

define('SOM',date('Y-m-01'));
define('EOM',date('Y-m-d'));

?>
