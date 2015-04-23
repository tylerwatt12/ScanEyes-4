<?php
	date_default_timezone_set('America/New_York');
	
	$GLOBALS['http'] = "http://";
	$GLOBALS['domain'] = "www.sdrscan.com";

	$GLOBALS['scaneyes_db_config'] = array(
		'database_type' => 'mysql',
		'database_name' => 'scaneyes315',
		'server' => 'localhost',
		'username' => '',
		'password' => '',
		'charset' => 'utf8'
	);

    $GLOBALS['system']['sysid'] = "BEE0049F"; // SystemID in unitrunker without any dashes (e.g. BEE0049F)
    $GLOBALS['system']['utxmlloc'] = "E:\scaneyes\UniTrunker\Unitrunker.xml"; // location to unitrunker.xml

    $GLOBALS['system']['localfilelocation'] = 'C:\xampp\htdocs\calls\\';
    $GLOBALS['system']['publicfilelocation'] = $GLOBALS['http'].$GLOBALS['domain'].'/calls/';
    $GLOBALS['system']['fileext'] = ".mp3";
?>