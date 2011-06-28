<?php
	function count_results($query) {
		if(is_int($_GET['total'])) { 
		 	return $_GET['total'];
		} else {
			$data = file_get_contents("http://".$_SERVER['HTTP_HOST']."/exist/rest//db/iati/?_query=count(".$query.")");
			return trim(strip_tags($data));
		}
	}
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
	<style><!--
		.facet, .action {
			width:45%;
			display: inline-block;
			vertical-align: top;
			margin: 5px;
		}
		
		.wide {
			width:95%;
			display: inline-block;
			vertical-align: top;
			margin: 5px;
		}
		
		.wide select {
			max-width:400px;
		}
	
		.config {
			display:none;
		}
		
		.note {
			font-size:smaller;
			display:block;
			
		}
		
		.intro {
			padding-top:10px;
			padding-bottom:10px;
		}
		
		.result-count {
			padding-top:10px;
			padding-bottom:10px;
		}
		
		.page-list {
			margin:auto;
			width:100%;
			text-align:center;
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
						<h2 class="entry-title">Fetching your query results</h2>
						
							<div class="intro">

							</div>
							
							<?php
								if($query = $_GET["query"]) { 
									$total = count_results($query);
									$howmany = $_GET['howmany'] ? $_GET['howmany'] : 100;
									if($howmany > 1000) { $howmany = 1000; }
									echo "<div class='result-count'>Your query returned ".$total." results</div>";
									
									if($total < $howmany) { 
										$url = "fetch.php?query=".$_GET['query']."&howmany=$howmany&xsl=".$_GET['xsl']."&format=".$_GET['format']."&page=$pn";
										echo "<p>You can fetch your data in a single file. Click the link below to download.</p>";
										echo "<p><a href=\"$url\">Download $total entries</a></p>";
									} else {
										$pages = round($total / $howmany,0,PHP_ROUND_HALF_UP);
										echo "<div class='result-pages'>";
										echo "<p>To avoid large queries causing difficulty we'll need to fetch this in $pages pages. Select from the links below on turn to fetch your data. You can then recombine this data in your local software</p>";
										
										for($pn = 1;$pn <= $pages;$pn++) {
											$page_array[] = "<a href=\"fetch.php?query=".$_GET['query']."&howmany=$howmany&xsl=".$_GET['xsl']."&format=".$_GET['format']."&page=$pn\">Page $pn</a>";
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
											<input type="text" size="60" value="<?php echo "http://".$_SERVER['HTTP_HOST']."/exist/rest//db/iati/?_query=".$_GET['query'].($_GET["xsl"] ? "&_xsl=".$_GET["xsl"] : null); ?>">
										</p>
									</div>	
									
									<?php
								} else {?>
									<h3>Error</h3>
									No query was found. Please go back to the <a href="/query/">query builder</a> or provide an XPATH query in the ?query= querystring parameter.
									
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