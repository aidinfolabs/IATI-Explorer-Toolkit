<?php
include ('include/eXist.php');

try
{
	$db = new eXist();

	# Connect
	$db->connect() or die ($db->getError());

	$query = 'for $line in //SPEECH[SPEAKER = "BERNARDO"]/LINE return $line';

	print "<p><b>XQuery:</b></p><pre>$query</pre>";

	# XQuery execution
	//$db->setDebug(TRUE);
	$db->setHighlight(FALSE);
	$result = $db->xquery($query) or die ($db->getError());
	# Get results
	$hits = $result["HITS"];
	$queryTime = $result["QUERY_TIME"];
	$collections = $result["COLLECTIONS"];

	print "<p>found $hits hits in $queryTime ms.</p>";

	# Show results
	print "<p><b>Result of the XQuery:</b></p>";
	print "<pre>";
	if ( !empty($result["XML"]) )
		foreach ( $result["XML"] as $xml)
			print htmlspecialchars($xml) . "<br />";
	print "</pre>";

	$db->disconnect() or die ($db->getError());
}
catch( Exception $e )
{
	die($e);
}
?>
