<?php
include ('../includes/eXist.php');
header ("Content-Type:text/xml");

$list = $_GET['list'];
$code = "ref";

$query = <<<EOF
	xquery version "1.0";
	declare option exist:serialize "method=xml media-type=text/xml omit-xml-declaration=no indent=yes";
	
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
			$out_list[(string)$node['ref']] = array("name"=>(string)$node);
		
		}
		
		header("Content-type: application/json");
		echo json_encode(array("codelist"=>array("items"=>$out_list)));

        $db->disconnect() or die ($db->getError());
  }
  catch( Exception $e )
  {
        die($e);
  }

?>