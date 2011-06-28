<?php
include ('include/eXist.php');

try
{
	$db = new eXistAdmin('guest', 'guest', 'http://127.0.0.1:8080/exist/services/Admin?wsdl');
	$db->connect() or die ($db->getError());

	// Store Document
	echo $db->store('<simple><fxp>françois</fxp></simple>', 
				'UTF-8',
				'/db/test.xml', true);
	echo $db->store('<simple><fxp>françois</fxp></simple>', 
				'UTF-8',
				'/db/test2suppr.xml', true);
	// Remove Document
	echo $db->removeDocument('/db/test2suppr.xml');

	// Create collection
	echo $db->createCollection('/db/existAdminDemo');
	echo $db->createCollection('/db/existAdminDemo2supp');
	
	// Remove collection
	echo $db->removeCollection('/db/existAdminDemo2supp');

	/*	
	// XupdateResource
	$xupdate = "<xupdate:modifications version='1.0' xmlns:xupdate='http://www.xmldb.org/xupdate'>".
		"<xupdate:update select='/simple/fxp'>TITI</xupdate:update></xupdate:modifications>";

	echo $db->xupdateResource('/db/test.xml', $xupdate);
	*/

	$db->disconnect() or die ($db->getError());
}
catch( Exception $e )
{
	die($e);
}
?>
