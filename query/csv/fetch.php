<?php

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=iati_download_".date("Ymd")."_".$_GET['format']."_page".$_GET['page']."_".$_GET['format']);
header("Content-type: text/csv");		

$start = ((($_GET['page'] == 1 || !$_GET['page']) ? 1 : ($_GET['page']-1) * $_GET['howmany']));

$url = "http://".$_SERVER['HTTP_HOST']."/exist/rest//db/iati/".$_GET['file']."?_query=//iati-activity"
		."&_howmany=".$_GET['howmany']
		."&_start=".$start
		.($_GET['xsl'] ? "&_xsl=".$_GET['xsl'] : null);


echo file_get_contents($url);

?>