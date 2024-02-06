<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Create connection to Oracle, change HOST IP and SID string!
//$db = "(DESCRIPTION=(ADDRESS = (PROTOCOL = TCP)(HOST = ellipse.ptpjb.com)(PORT = 47154))(CONNECT_DATA=(SID=ellprd)))";
//$db = "(DESCRIPTION=(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.1.197)(PORT = 1521))(CONNECT_DATA=(SID=ellprd)))";
$db = "(DESCRIPTION=(ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.3.205)(PORT = 1521))(CONNECT_DATA=(SID=ellprd)))";
// Enter here your username (DBUSER) and password!
$conn = oci_connect("mimsoe", "mims",$db);
if (!$conn) {
   $m = oci_error();
   echo $m['message']. PHP_EOL;
   exit;
}
else {
   print "Oracle database connection online". PHP_EOL . '. alhamdulillah';
}

?>
