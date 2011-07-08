<?php
date_default_timezone_set("Europe/London");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include("../query-eXist/include/eXist.php");
define("EXIST_URI","http://127.0.0.1:8080/exist/");
define("EXIST_DATASET","iati");
define("EXIST_USER","iati");
define("EXIST_PASS","op3na1ddata");
define("CACHE_LIFETIME",86400); //Cache files this long

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
	log_message("Database connection established to eXist");
	
	return $db;
}

/**
 * Log - output to screen or file
 */
function log_message($message,$code=null) {
	echo $message."\n";
	file_put_contents("exist_sync.log",date("d M y H:i:s")." - ".$message."\n",FILE_APPEND);
}


function create_collection() {
 if(!$db) {
	$db = exist_connect();
 }	
	$db->createCollection("xsl");
}

function load_xsl($file_name,$name_on_store) {
 if(!$db) {
	$db = exist_connect();
 }
	$file = file_get_contents($file_name);
	if($file) {
		$db->store($file,"UTF-8","xsl/".$name_on_store,true);
		echo "View at /exist/rest//db/xsl/".$name_on_store;
	} else {
		echo "File not found";
	}

}

function reset_db() {
 if(!$db) {
	$db = exist_connect();
 }
	$file = "<xml>Look at /exist/rest//db/iati/ for IATI Data</xml>";
	if($file) {
		$db->store($file,"UTF-8","/db/",true);
		echo "Reset";
	} else {
		echo "File not found";
	}

}

switch($argv[1]) {
	case "create_collection": //Only needed to call once if the collection doesn't exist
		create_collection();
	break;
	case "reset_db": //Only needed to call once if the collection doesn't exist
		reset_db();
	break;
	case "upload": //Upload with file and username
		if($argv[2] && $argv[3]) {
			load_xsl($argv[2],$argv[3]);
		} else {
			echo "Please provide a filename and transform-name.";
		}
	break;
	default:
		echo "Call with 'upload [filename] [transform-name] to add to the store";
}