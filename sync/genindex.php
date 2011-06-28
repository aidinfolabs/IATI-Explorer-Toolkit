<?php
date_default_timezone_set("Europe/London");
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include("ckan-client/Ckan_client.php");
include("query-eXist/include/eXist.php");
define("EXIST_URI","http://127.0.0.1:8080/exist/");
define("EXIST_DATASET","iati");
define("EXIST_USER","iati");
define("EXIST_PASS","op3na1ddata");
define("CACHE_LIFETIME",86400); //Cache files this long

global $exist_datasets;

/**
 * Connect to Exist
 */
function exist_connect() {
	try {
		$db = new eXistAdmin(EXIST_USER, EXIST_PASS, EXIST_URI."services/Admin?wsdl");
		$db->connect() or die ($db->getError());
	}  catch( Exception $e )
	{
		die($e);
	}
	echo "Database connection established to eXist\n";
	
	return $db;
}

if(!$db) {
	$db = exist_connect();
}

$index_data = file_get_contents('collection.xconf');
$db->store($index_data,"UTF-8","/db/system/config/db/iati/collection.xconf", true);
echo "Loading Index Configuration\n".$db->getError();