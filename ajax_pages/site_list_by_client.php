<?php
require_once('../configure.php');
require_once(AbsPath . 'classes/all.php');
require_once(AbsPath . 'classes/category.class.php');
require_once(AbsPath . 'classes/gallery.class.php');

$DB = new DB;

$client_id = $_GET['client_id'];

$strSQL="Select * from t_sites where client_id = $client_id order by site_name";
$strRsSitesArr=$DB->Returns($strSQL);

echo "<option value=''>Select Site</option>";

while($strRsSites=mysql_fetch_object($strRsSitesArr)){
    echo "<option value='$strRsSites->site_id'>$strRsSites->site_name</option>";
}
