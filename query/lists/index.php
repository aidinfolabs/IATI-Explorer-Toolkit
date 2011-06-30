<?php
include ('../config/config.php');
include ('../includes/eXist.php');
header ("Content-Type:text/xml");

$list = $_GET['list'];
$code = "ref";

//Get Ref
function getRef($node) {
	if(strlen(trim($node['ref'])) > 0) {
		return (string)$node['ref'];
	} else {
		return "tmp_".strtolower(str_replace(array("."),"",str_replace(array(" ",","),"_",(string)$node)));
	}
}

//Fetch Distinct
function fetchDistinct($list) {

$query = <<<EOF
		xquery version "1.0";
		declare option exist:serialize "method=xml media-type=text/xml omit-xml-declaration=no";

		let \$data := collection(/db/iati)
		let \$items := \$data//iati-activity/$list

		return 
		<results>
		{for \$item in \$items
			return \$item}
		</results>

EOF;

	try
	  {
	        $db = new eXist();

	        # Connect
	        $db->connect() or die ($db->getError());

	        # XQuery execution
	        //$db->setDebug(TRUE);
	        $db->setHighlight(FALSE);
	        $result = $db->xquery($query) or die ($db->getError());

			$xml = simplexml_load_string($result['XML']);
			foreach($xml->children() as $node) {
				$out_list[getRef($node)] = array("code" =>(string)$node['ref'], "name"=>(string)$node);	
			}
			return $out_list;
	}
	catch( Exception $e )
	{
	     die($e);
	}
}

//Caching functions
$filename = CACHE_FOLDER."list_cache_$list.cache";
$age = CACHE_LIFETIME ? CACHE_LIFETIME : 86400;

if (file_exists($filename)) {
  $mtime = filemtime($filename);
  $fileage = time() - $mtime;
  if ($fileage>$age) {
    $out_list = unserialize(file_get_contents($filename));
  } else {
    $out_list = fetchDistinct($list);
    file_put_contents($filename,serialize($out_list));
  }
} else {
    $out_list = fetchDistinct($list);
    file_put_contents($filename,serialize($out_list));
}


if($_GET['format']=="csv") {
	header("Content-type: text/csv");			
	echo "code,name\n";
	foreach($out_list as $code => $name) {
		echo $code.",\"".$name['name']."\"\n";
	}
} else {
	header("Content-type: application/json");
	echo json_encode(array("codelist"=>array("items"=>$out_list)));			
}

?>