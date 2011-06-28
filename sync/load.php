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

/**
 * Load our full list of eXist datasets...
 */
function load_exist_datasets() {
	log_message("Loading current list of datasets");
        //We should probably be able to do this directly against exist.
        $xml = simplexml_load_file(EXIST_URI."rest//db/".EXIST_DATASET."/"); 
	foreach($xml->xpath("//exist:resource") as $resource) {
		$return[(string)$resource->attributes()->name] = strtotime((string)$resource->attributes()->{"last-modified"});
	}
	log_message("List of datasets loaded. " . count($return) . " datasets available");
	return $return;
}

/**
 *
 */
function check_package($package,$db) {
}

/**
 * Check when file last changed
 */
function file_last_changed($url) {
	$filename = "cache/".md5($url);
	if(file_exists($filename)) {
		$mtime = filemtime($filename);
		$fileage = time() - $mtime;
		if ($fileage < CACHE_LIFETIME) {
			log_message("We've checked last updated time of $url in the last ".round(CACHE_LIFETIME/60/60)." hours");
  			return file_get_contents($filename);
		}
	}
	
	log_message("Checking last updated time of $url");
	$ch = curl_init($url); 
	curl_setopt($ch, CURLOPT_HEADER, 1); 
	curl_setopt($ch, CURLOPT_NOBODY, 1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$c = curl_exec($ch); 
	
	$header_lines = explode("\n",$c);
	array_shift($header_lines);
	foreach($header_lines as $line) {
		$line = explode(": ", $line);
		$headers[strtolower($line[0])] = $line[1];
	}

	if($headers['last-modified']) {
		$return = strtotime($headers['last-modified']);
	} else {
		$return = mktime(time());
	}
	file_put_contents($filename,$return);
	
	return $return;
	
}

/**
 * Fetch file
 */
function load_file($package,$exist_datasets,$db=null) {
	if(!$db) {
		$db = exist_connect();
	}
    $resource = $package->resources[0]->url;
	$name = (string)$package->name;

	log_message("Exist version last updated ".date("d M Y H:i:s",$exist_datasets[$name]).". Time difference ".round(((time() - (int)$exist_datasets[$name]))/60)." minutes.");
	
	if((time() - (int)$exist_datasets[$name]) < CACHE_LIFETIME) {
		log_message($name ." is already up to date in eXist: only ".round((time() - (int)$exist_datasets[$name])/60/60)." hours old.");
	} elseif((int)$exist_datasets[$name] > file_last_changed($resource)) {
		log_message($name ." has no newer version available.");
	} else {
		$iati_data = file_get_contents(trim($resource));
		$iati_data = preg_replace("#[\x5]#","",$iati_data); //We do this to fix some current malformed XML files in the registry
		$db->debug = TRUE;
		$db->store($iati_data,"UTF-8",EXIST_DATASET."/".$name, true);
		log_message("Inserting/Updating $name ".$db->getError());
	}
}


/**
 * Fetch package - using cached copy if available
 */

function cache_package($package,$ckan_client) {
	$filename = "cache/$package";

	if (file_exists($filename)) {
		$mtime = filemtime($filename);
		$fileage = time() - $mtime;
		$fileagemin = round($fileage/60);
		if ($fileage < CACHE_LIFETIME) {
			log_message("$package cached. Age $fileagemin minutes. Using cached version.");
	  		$return = unserialize(file_get_contents($filename));
		} else {
			log_message("$package cached. Age $fileagemin minutes. Fetching new version.");
	  		$return = $ckan_client->get_package_entity($package);
	  		file_put_contents($filename,serialize($return));
		}
	} else {
		$return = $ckan_client->get_package_entity($package);
		file_put_contents($filename,serialize($return));
	}

	return $return;
}



/**
 * Fetch Packages
 */
function fetch_packages($query = null,$db = null) {
 	if(!$db) {
                $db = exist_connect();
        }
	$exist_datasets = load_exist_datasets();


	$ckan = new Ckan_client();
	log_message("Fetching package list from the registry");
	$data = $ckan->get_package_register();

	log_message("Packages fetched");

	foreach($data as $package) {
		log_message("Fetching package details");
		$detail = cache_package($package,$ckan); 
		load_file($detail,$exist_datasets,$db);
		unset($exist_datasets[$detail->name]);
	}
	
	foreach($exist_datasets as $key => $value) {
		log_message("DELETION: $key is no longer found in the registry. Removing...");
		$db->removeDocument(EXIST_DATASET."/".$key);
		log_message(EXIST_DATASET."/".$key. " REMOVED.");
	}


}


fetch_packages();
