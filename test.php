<?php
#test page
if($_SERVER['REMOTE_ADDR'] == "192.168.0.1" || $_SERVER['REMOTE_ADDR'] == "206.19.233.128"){ // limit IP access
}else{
	exit("Please use a whitelisted IP");
}

include('assets/functions.php'); // load main bootstrapper
__loadasset('config');
__loadasset('functions');
__loadclass('main');
__loadclass('trunkimport');
__loadclass('id');
__loadclass('call');
__loadclass('query');

/*
$class = new trunkimport();
$class->purge();
$class->sync();

echo "<textarea rows='20' cols='200'>";
echo $class->verbose;
echo "</textarea>";
*/
/*
$id = new id;
$id->getAllIDs();
$id->setSortField("TAG");
*/

$call = new call;
$call->getCallByTimePeriod("1429580000","1429583599"); // also try not using strings

echo "<textarea rows='50' cols='200'>";
var_dump($call->displayResults(TRUE));
echo "</textarea>";

/*
$queryHandle = new query;
echo "<textarea rows='50' cols='200'>";
echo microtime();
var_dump($queryHandle->getDateIDS("today"));
echo microtime();
echo "</textarea>";
*/
?>