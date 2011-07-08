<?php
include_once("../config/config.php"); 
define("CACHE_LIFETIME",3600);
include_once("../functions/cache.php"); 
include_once("query_functions.php"); 


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
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script> 	
	<script type="text/javascript" src="/js/iati-query-builder.js"></script> 
	<script type="text/javascript" src="/js/jquery.unescape.js"></script> 
	<link rel="stylesheet" type="text/css" media="all" href="/css/iati-toolkit.css" />	
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
						<h2 class="entry-title">Query IATI Data</h2>
						
							<div class="intro">
								Choose from the <a href="#query-options">query options below</a> to create an XPath expression.
							</div>
							

							<h3>Query</h3>
							<form id="query-builder">
								<input name="query" id="query" type="text" size="50" value="//iati-activities"/>
								<p>
								<span id="querylink"><a href="/exist/rest//db/iati?_query=//iati-activities" target="_blank">Run query</a></span>
								</p>
								
								<h3>Current Query</h3>
								<p>
									<span class="human_query">Any project</span>
								</p>
								
								<h3>Actions</h3>
									<div class="intro">
										Run your filtered XML query using the link above, or select from the actions to explore the data in different ways.	
									</div>
									<div class="action url">
										<span class="action url" id="spending-summary"><a href="#" class="target" target="_blank">Activity details as CSV</a></span>
										<span class="config">/process/?query=QUERY&amp;xsl=https://raw.github.com/aidinfolabs/IATI-XSLT/master/templates/csv/iati-activities-xml-to-csv.xsl&amp;format=csv</span>
										Fetch CSV based on <a href="https://raw.github.com/aidinfolabs/IATI-XSLT/master/templates/csv/iati-transactions-xml-to-csv.xsl">a transformation from the IATI XSLT Library</a>
									</div>

									<div class="action url">
										<span class="action url" id="spending-summary"><a href="#" class="target" target="_blank">Transactions as CSV</a></span>
										<span class="config">/process/?query=QUERY&amp;xsl=https://raw.github.com/aidinfolabs/IATI-XSLT/master/templates/csv/iati-transactions-xml-to-csv.xsl&amp;format=csv</span>
										Fetch CSV based on <a href="https://raw.github.com/aidinfolabs/IATI-XSLT/master/templates/csv/iati-transactions-xml-to-csv.xsl">a transformation from the IATI XSLT Library</a>
									</div>

									<div class="action url">
										<span class="action url" id=""><a href="#" class="target" target="_blank">Download XML</a></span>
										<span class="config">/process/?query=QUERY</span>
										Download the XML page-by-page
									</div>

									<div class="action url explorer">
										<span class="action url" id=""><a href="#" class="target" target="_blank">IATI Explorer</a></span>
										<span class="config">/explorer/?query=QUERY</span>
										Explore the data in an interactive interface. Includes features to export as Tab-Separated Values (TSV).
									</div>
								
								<a name="query-options"><h3>Query Options</h3></a>
								<input type="reset" value="Reset query" class="reset-button">
								
								<div class="facet wide"><h4>Funding Org</h4>
								<?php echo generateSelect("participating-org/@ref",1,null,"participating-org[@role='Funding']"); ?>
								</div>
								
								<div class="facet"><h4>Recipient Country</h4>
								<?php echo generateSelect("recipient-country/@code",1,"Country"); ?>
								</div>
								
								<div class="facet"><h4>Recipient Region</h4>
								<?php echo generateSelect("recipient-region/@code",1,"Region"); ?>
								</div>
								
								<div class="facet"><h4>Activity Status</h4>
								<?php echo generateSelect("activity-status/@code",1,"ActivityStatus"); ?>
								</div>
								
								<div class="facet"><h4>Default flow type</h4>
								<?php echo generateSelect("default-flow-type/@code",1,"FlowType"); ?>
								</div>
								
								<div class="facet"><h4>Collaboration Type</h4>
								<?php echo generateSelect("collaboration-type/@code",1,"CollaborationType"); ?>
								</div>
								
								<div class="facet wide"><h4>Sector Targetted</h4>
								<?php echo generateSelect("sector/@code",1,null,"sector"); ?>
								</div>
								
								<div class="facet wide"><h4>Default Aid Type</h4>
								<?php echo generateSelect("default-aid-type/@code",1,"AidType"); ?>
								</div>
								
								<div class="facet wide"><h4>Default finance type</h4>
								<?php echo generateSelect("default-finance-type/@code",1,"FinanceType"); ?>
								</div>
								
								<div class="facet wide"><h4>Policy Marker</h4>
								<?php echo generateSelect("policy-marker/@code",1,"PolicyMarker"); ?>
								<span class="note">Note: this only checks for the existence of a policy marker field with the relevant code-list value. As some IATI data providers (e.g. DFID) currently include all DAC policy-markers in their files and use a significance value to show where they apply, it does not narrow down searches heavily. If you want to check for vocabulary, marker, and that the significance value is > 0 you would need a custom query of the form: <a href="/exist/rest//db/iati/?_query=//policy-marker[@vocabulary='DAC'][@code=1][@significance > 0]/parent::iati-activity" target="_blank">//policy-marker[@vocabulary='DAC'][@code=N][@significance > 0]/parent::iati-activity</a> </span>
								</div>
								
								<h4>Create your own query</h4>
								You can run your own arbitrary queries against our eXist database using XPATH. 
							</form>
					
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

						<li id="text-3" class="widget-container widget_text"><h3 class="widget-title">Using Query Builder</h3>					
						<div class="widget-content">
							<p>
							Data is published part of the International Aid Transparency Initiative by individual donors, often in a different file for each country they work in, and is listed in the <a href="http://iatiregistry.org/">IATI Registry</a>. This tool uses data fetched from all those files, and aggregated together in an <a href="http://www.exist-db.org">XML database</a> to allow you to query the data and convert it into the format you want.
							</p>
							<p>
							Use the query builder on this page to choose the sub-set of data you are interested in, and then choose the way you watch to explore it from the options below.
							</p>
							<p>
							<strong>Note:</strong> By default we'll only return the first 100 records.
							</p>
							<p>
							The <a href="http://en.wikipedia.org/wiki/XPath">XPATH expression</a> used for your query is shown below. With some experimentation you should be able to adapt this to run queries not easily set-up using the forms below.
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
