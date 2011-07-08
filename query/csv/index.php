<?php
include("../config/config.php");
	
function count_results_file($file) {
	if(is_int($_GET['total'])) { 
	 	return $_GET['total'];
	} else {
		$data = file_get_contents(EXIST_URI.EXIST_DB."/".$file."?_query=count(//iati-activity)");
		return trim(strip_tags($data));
	}
}

function last_updated($file) {

	$xml = simplexml_load_file(EXIST_URI.EXIST_DB);
	$xml->registerXPathNamespace('exist', 'http://exist.sourceforge.net/NS/exist');
	$last_updated = $xml->xpath("//exist:resource[@name='".$file."']");

	foreach($last_updated as $node) {
		foreach($node->attributes() as $att => $value) { 
		    if ($att == "last-modified") { 
				return $value;
		    } 
		  }
	}
		return null;
}

//Hard coded defaults at the moment. 
function fetch_xsl($xsl=null,$elementName = "") {
	if(!$xsl) {
		$xsl = array("Activities CSV"=>"/db/xsl/iati-activities-xml-to-csv.xsl",
	"Transactions CSV"=>"/db/xsl/iati-transactions-xml-to-csv.xsl");
	}
	
	$return .="<select name=\"$elementName\" class=\"change_xsl\" id=\"{$elementName}_select\">";
	foreach($xsl as $name => $stylesheet) {
		$return .= "<option value=\"".str_replace(" ","_",strtolower($name))."\">$name</option>";
	}
	$return .= "</select>";
	
	$return .= "<div class=\"xsl_list\" id=\"{$elementName}_list\">";
	foreach($xsl as $name => $stylesheet) {
		$return .= "<span id=\"".str_replace(" ","_",strtolower($name))."\">$stylesheet</span>";
	}
	$return .= "</div>";
	
	return $return;
}
$default_xsl = "https://raw.github.com/aidinfolabs/IATI-XSLT/master/templates/csv/iati-activities-xml-to-csv.xsl";

function load_datasets() {
    $xml = simplexml_load_file(EXIST_URI.EXIST_DB.""); 
	foreach($xml->xpath("//exist:resource") as $resource) {
		$return[] = (string)$resource->attributes()->name;
	}
	return $return;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>IATI Explorer Toolkit - CSV Tools</title>
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css" />
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" media="all" href="/css/style-ie.css" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" media="all" href="/css/custom.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script> 
	<script type="text/javascript" src="/js/iati-query-builder.js"></script> 
	<script type="text/javascript" src="/js/jquery.unescape.js"></script> 
	<link rel="stylesheet" type="text/css" media="all" href="/css/iati-toolkit.css" />	
	<style><!--	
		.xsl_list {
			display:none;
		}
		
	--></style>	
</head>
<body>
	<div id="wrapper" class="hfeed">
		<div id="header">
			<div id="masthead" class="clearfix">
				<div id="branding" role="banner">
	            	<img src="/images/iati-toolkit-logo-draft.png" border="0" />
	                <div id="site-description">a collection of tools for working with IATI Data</div>
	            </div><!-- #branding -->


				<div id="access" class="access clearfix" role="navigation">
				  				<div class="skip-link screen-reader-text"><a href="#content" title="Skip to content">Skip to content</a></div>
									<div class="menu"><?php include("../includes/menu.inc.php"); ?></div>			</div><!-- #access -->
			</div><!-- #masthead -->
		</div><!-- #header -->
	
	
		<div id="main" class="clearfix">

			<div id="container">
				<div id="content" role="main">
					<div class="post type-post status-publish format-standard">
						<h2 class="entry-title">View IATI Donor Files as CSV</h2>
						
							<div class="intro">
							<p>The authoritative version of any IATI data is always the XML direct from the donor. This service will apply your chosen approach to 'flatten' out the data into CSV form to open and explore it in a spreadsheet based on a cached copy of the data. This system checks for new data daily, and will fetch an updated copy of donors data whenever it detects a change.
							</p>
							<p>
								If you want a more flexible system to generate CSV from across different IATI files (e.g. by country, regardless of donor), please use the <a href="/query/">Query builder</a>.
							</p>
							</div>
							
<?php
if($file = $_GET["file"]) { 
	$total = count_results_file($file);
	$howmany = $_GET['howmany'] ? $_GET['howmany'] : 1000;
	if($howmany > 10000) { $howmany = 1000; }

	if($updated = last_updated($file)) {
	
		echo "<p>Our records for ".$file." were last updated on ". date("D M Y H:m",strtotime($updated))." </p>";
		
		echo "<div class='result-count'>This file contains ".$total." activities. It may contain more or less transactions.</div>";
		
		echo "<div class='select-format'>".fetch_xsl(null,"csvxsl")."</div>"; 

	
	} else {
		echo "<p>We do not have a record for the file you have requested. It is possible it has not yet been fetched, or is no longer available from the <a href=\"http://www.iatiregistry.org\">IATI Registry</a>. This service may be up to 24 hours behind the IATI Registry. ";
	
	}




	if($total < $howmany) { 
		$url = "fetch.php?file=".$_GET['file']."&howmany=$howmany&page={$pn}&xsl=".($_GET['xsl'] ? $_GET['xsl'] : $default_xsl)."";
		echo "<p>You can fetch your data in a single file. Click the link below to download.</p>";
		echo "<p><a href=\"$url\" class=\"link_with_xsl\">Download $total entries</a></p>";
	} else {
		$pages = round($total / $howmany,0,PHP_ROUND_HALF_UP);
		echo "<div class='result-pages'>";
		echo "<p>To avoid large files and queries causing difficulty we'll need to fetch this in $pages separate sections. Select from the links below on turn to fetch your data. You can then recombine this data in your local software</p>";
	
		for($pn = 1;$pn <= $pages;$pn++) {
			$page_array[] = "<a href=\"fetch.php?file=".$_GET['file']."&howmany=$howmany&page={$pn}&xsl=".($_GET['xsl'] ? $_GET['xsl'] : $default_xsl)."\" class=\"link_with_xsl\">Page $pn</a>";
		}
		echo "<div class='page-list'>".join($page_array,", ")."</div>";
		echo "</div>";							
	}?>

									<p></p>
									<div class="note">
										<h3>Notes</h3>
										<p>
										The data you are accessing may have been transformed and reformatted using third-party code. Make sure you understand any limitations of your chosen conversion before relying on the data.
										</p>
										<h3>Fetching direct</h3>
										<p>
											If you want to fetch this data direct and page through results in your own applications, you can use the following URL. Use the '_start' and '_howmany' parameters for paging, and the _xsl parameter to apply a style sheet to the results. Stylesheets can be loaded over the Internet, although output will be XML and your application will need to handle any content type selection required. Check the exist:hits and exist:count values in XML output to see how far you need to page through the results. 
										</p>
										<p>
											<input type="text" size="60" value="<?php echo EXIST_URI.EXIST_DB."?_query=".$_GET['query'].($_GET["xsl"] ? "&_xsl=".$_GET["xsl"] : null); ?>">
										</p>
									</div>	
									
									<?php
								} else {?>
									<h3>Select your file</h3>
									<div>
									This CSV service expects a package ID in the ?file= parameter. E.g. ?file=dfid-af for DFID's Afghanistan data.
									</div>
									<div>
										<form action="." method="get">
										<strong>Currently available files:</strong>  
										<select name="file">
											<?php foreach(load_datasets() as $dataset) {
												echo "<option value='$dataset'>$dataset</option>";
											}?>
										</select>
										<input type="submit" value="Fetch"/>
									</div>
									
									
							<?php	}
							?>

						
					</div><!--.post-->
					
				</div><!-- #content -->
			</div><!-- #container -->



			<div id="sidebar" class="widget-area" role="complementary">
				<ul class="xoxo">
					<li id="text-3" class="widget-container widget_text"><h3 class="widget-title">Using Query Builder</h3>
						<div class="widget-content">
							<p>
							Data is published part of the International Aid Transparency Initiative by individual donors, often in a different file for each country they work in, and is listed in the <a href="http://iatiregistry.org/">IATI Registry</a>. This tool uses data fetched from all those files, and aggregated together in an <a href="http://www.exist-db.org">XML database</a> to allow you to query the data and convert it into the format you want.
							</p>
							<p>
							Use the query builder to choose the sub-set of data you are interested in, and then choose the way you watch to explore it from the options below.
							</p>							
						</div>
					</li>
				</ul>

			</div><!-- #sidebar .widget-area -->
		</div><!-- #main -->

				<div id="footer" role="contentinfo"> 
					<div id="colophon"> 
 						<div id="site-generator" class="clearfix"> 
						</div><!-- #site-generator --> 
					</div><!-- #colophon --> 
				</div><!-- #footer --> 

			</div><!-- #wrapper -->
	
	




</body>
</html>