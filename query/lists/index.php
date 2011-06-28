<?php
include ('../includes/eXist.php');
header ("Content-Type:text/xml");

$list = $_GET['list'];
$code = "ref";

$query = <<<EOF
	xquery version "1.0";
	declare option exist:serialize "method=xml media-type=text/xml omit-xml-declaration=no indent=yes";
	
	let \$data := collection(/db/iati)
	let \$distinct := distinct-values(\$data//iati-activity/$list/text())
	
	return 
	<list>
	{
	for \$item in \$distinct 
	return subsequence(\$data//iati-activity/{$list}[.=\$item],1,1)
	}
	</list>

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
		echo $result["XML"];

        $db->disconnect() or die ($db->getError());
  }
  catch( Exception $e )
  {
        die($e);
  }

?>