<?php
ob_start();
session_start();
session_destroy();
require_once("configure.php");
//header('location:http://khwab.net/energydas/');
header('location:'.URL);
?>
