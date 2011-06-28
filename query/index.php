<?php
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>IATI Explorer Toolkit</title>
	<link rel="stylesheet" type="text/css" media="all" href="/css/style.css" />
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" media="all" href="/css/style-ie.css" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" media="all" href="/css/custom.css" />
	<link rel="stylesheet" type="text/css" media="all" href="/css/iati-toolkit.css" />	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script> 	
	<script type="text/javascript" src="/js/display.js"></script> 

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
									<div class="menu"><?php include("includes/menu.inc.php"); ?></div>			</div><!-- #access -->
			</div><!-- #masthead -->
		</div><!-- #header -->
	
	
		<div id="main" class="clearfix">

			<div id="container">
				<div id="content" role="main">
					<div class="post type-post status-publish format-standard">
						<h2 class="entry-title">IATI Explorer Toolkit</h2>
						
						<p>
							The International Aid Transparency Initiative has created an XML standard and processes for donors to publish information on aid projects, budgets and spending. A log of all published IATI data is kept on the <a href="http://www.iatiregistry.org">IATI Registry</a> where you can download XML files directly from donors and other information sources. 
						</p>
						
						<p>
							This toolkit provides a set of web services and tools to help you work with this data. It hosts a copy of data from the IATI Registry, updated daily, and makes it available to query - allowing you to extract and reformat information from across all the reported aid projects, budgets and spending. 
						</p>
						<h4>Available Tools</h4>

					
						<div class="tool">
						<h3 class="tool-details">Data Explorer</h3> 
						<span class="tool-information">
							The IATI Data Explorer makes use of the Exhibit framework to provide a simple browser-based tool that allows you to explore a sub-set of IATI data. By selecting different facets you can drill-down to explore a range of projects. 
							<span class="tool-link"><a href="/explorer/">Visit the Explorer</a></span>
						</span>
						</div>
						
						<div class="tool">
						<h3 class="tool-details">CSV Transformations</h3> 
						<span class="tool-information">
							The CSV service makes use of a number of the toolkit services below to allow you to fetch various CSV (spreadsheet) versions of the IATI data, based on original IATI data files. 
							<span class="tool-link"><a href="/csv/">Fetch CSV</a></span>
						</span>
						</div>
						
						<div class="tool">
						<h3 class="tool-details">Query Builder</h3> 
						<span class="tool-information">
							The Query Builder is a javascript application that helps you create XPATH queries to filter and fetch only the IATI data you are interested in. Filter by recipient country, funding organisation, policy markers and other factors. 							
							<span class="tool-link"><a href="/csv/">Visit the Query Builder</a></span>
						</span>
						</div>
						
						<div class="tool">						
						<h3 class="tool-details">XPath Endpoint</h3>
						<span class="tool-information">
							You can run your own XPATH queries over the IATI data using the RESTFUL XPATH endpoint. This allows you application to fetch only the data it needs, rather than whole XML files. You can page through the results with the <b>&amp;_howmany</b> and <b>&amp;_start</b> parameters. The data is indexed to allow for fast queries. The underlying platform is <a href="http://www.exist-db.org" target="_blank">an eXist XML Database</a>.
							<span class="tool-link"><a href="/exist/rest//db/iati?_query=//iati-activity&_howmany=10&_start=1">Visit the Endpoint</a></span>
						</span>
						</div>
						
						<div class="tool">
						<h3 class="tool-details">XSLT Transformations</h3>
						<span class="tool-information">
							As well as running XPATH queries over IATI data, you can use the RESTFUL Endpoint to run XSLT (XML Stylesheet Transforms) against the IATI XML - reformatting it into new forms. If your applications require data in a particular format, you can write and host your own XSLT, and the endpoint will fetch and process it on demand. The underlying platform is <a href="http://www.exist-db.org" target="_blank">an eXist XML Database</a>.
							<span class="tool-link"><a href="/exist/rest//db/iati?_query=//iati-activity&_howmany=10&_start=1&_xsl=YOUR_XSLT_URL_HERE">Visit the Endpoint</a></span>
						</span>
						</div>
						
						<div class="tool">
						<h3 class="tool-details">Lists Service</h3>
						<span class="tool-information">
							Generates a list of all the <a href="/list/?list=participating-org[@role='Funding']" target="_blank">funding</a>, <a href="/list/?list=participating-org" target="_blank">participating</a> or <a href="/list/?list=reporting-org" target="_blank">reporting organisations</a> currently known in the dataset as a JSON file.
						</span>
						</div>
						
						
						<div class="tool">
						<h3 class="tool-details">XQuery Service</h3>
						<span class="tool-information">
							You can post XQUERY code to the data endpoint to run XQueries over the data. 
						</span>
						</div>
						
					</div><!--.post-->
					
				</div><!-- #content -->
			</div><!-- #container -->



			<div id="sidebar" class="widget-area" role="complementary">
				<ul class="xoxo">
					<li id="text-3" class="widget-container widget_text"><h3 class="widget-title">Beta</h3>
							<div class="widget-content">
								<p>This is an early prototype and is a work-in-progress. </p>
								<p>Please drop comments/questions/bug reports/feedback to <a href="mailto:aidinfo@practicalparticipation.co.uk">aidinfo@practicalparticipation.co.uk</a> or discuss on the <a href="http://aidinfolabs.org/archives/422">AidInfoLabs site</a>.
								</p>
							</div>
						</li>	

						<li id="text-3" class="widget-container widget_text"><h3 class="widget-title">About the Data</h3>					
						<div class="widget-content">
							<p>
							Data is published part of the International Aid Transparency Initiative by individual donors, often in a different file for each country they work in, and is listed in the <a href="http://iatiregistry.org/">IATI Registry</a>. This tool uses data fetched from all those files, and aggregated together in an <a href="http://www.exist-db.org">XML database</a> to allow you to query the data and convert it into the format you want.
							</p>
							
							
						</div>
					</li>
					
					<li id="text-3" class="widget-container widget_text"><h3 class="widget-title">Useful links</h3>					
					<div class="widget-content">
						<p><a href="http://www.iatiregistry.org" target="_blank">IATI Registry</a> (IATI Data)</p>
						<p><a href="http://www.aidtransparency.net" target="_blank">Aid Tranparency </a> (IATI Project Site)</p>
						<p><a href="http://www.aidinfolabs.org" target="_blank">Aid Info Labs</a> (Ideas and tools)</p>
						<p><a href="http://www.github.com/aidinfolabs" target="_blank">Aid Info Labs on GitHub</a> (Shared source code)</p>
						<p><a href="http://www.aidinfo.org" target="_blank">AidInfo</a> (Aid information news and update)</p>
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
