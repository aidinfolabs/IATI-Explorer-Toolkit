<?php
include_once("../config/config.php");

$format = $_GET['format'];

switch($format) {
	case "csv": 
		header("Content-type: text/csv");		
	break;
	case "json":
		header("Content-type: text/json");
	break;
	default:
		header("Content-type: text/xml");
	break;
}

//Make sure we always return simple plain text for a ?format=plain request.

$query = ($_GET['query'] ? str_replace(array("\\","\\\\","\\\\\\"),"",$_GET['query']) : "//iati-activity");

$url = EXIST_URI.EXIST_DB."?_query=".urlencode("count(".$query.")");

echo trim(strip_tags(file_get_contents($url,'UTF-8')));


?>