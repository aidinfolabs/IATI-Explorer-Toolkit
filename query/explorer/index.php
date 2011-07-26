    <html xml:lang="en" lang="en"
        xmlns="http://www.w3.org/1999/xhtml"
        xmlns:ex="http://simile.mit.edu/2006/11/exhibit#">
    <head>
        <title>IATI Data Explorer</title>

     <?php if($_GET['query']) { ?>
	<link href="./iatixslt.php?q=<?php echo $_GET['query']; ?>&howmany=<?php echo $_GET['howmany']; ?>" type="application/json" rel="exhibit/data" />
	<script src="http://api.simile-widgets.org/exhibit/2.2.0/exhibit-api.js" type="text/javascript"></script>
 	<script src="http://api.simile-widgets.org/exhibit/2.2.0/extensions/time/time-extension.js"></script>
  	<script src="http://api.simile-widgets.org/exhibit/2.2.0/extensions/map/map-extension.js?gmapkey=ABQIAAAAGqZ7tXHiGjVqon77O4l7FhRuUc3i0c2feLQwApVvJs2lHELn7BScmSQE59vGMpeyRfJLD_5HivZp8A"></script>
	<script src="./exhibit-helpers.js" type="text/javascript"></script>
    <?php } ?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
	<script src="js/thickbox-compressed.js"></script>
	<link media="screen" rel="stylesheet" href="js/thickbox.css" />

 <?php if($_GET['query']) { ?>
	<script type="text/javascript"><!--
	  function showThickbox(a){
	    var t = a.title || a.name || null;
	    var g = a.rel || false;
	    tb_show(t,a.href,g);
	    a.blur();
	    return false;
	  };
	--></script>
 <?php } else { ?>
	<script type="text/javascript"><!--
	  $(document).ready(function(){
			tb_show("Choose your data","query.php?howmany=<?php echo $_GET['howmany'];?>&TB_iframe=true&height=500&width=800",false);
  	  });
	
	--></script>
 <?php } ?>
 
	<link rel="stylesheet" type="text/css" href="style.css" title="Default Styles" />

    </head> 
    <body class="results">
	    
	    <div id="header">
	        <div id="nav">
                <h2><a href="#">AidInfo</a></h2>
	            <h1><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>"><abbr title="International Aid Transparency Initiative">IATI</abbr> Data Explorer</a></h1>
	        </div>
	        <div id="options">
	        <?php if($_GET['query']) { ?>
		    	
			Currently viewing <?php echo $_GET['desc'] ? "activities where ".str_replace("\\","",$_GET['desc']) : "any activities" ?>. 
			
			<?php $count= file_get_contents("http://".$_SERVER['SERVER_NAME']."/count/?query=".urlencode($_GET['query']));
			$howmany = $_GET['howmany'] ? $_GET['howmany'] : 200;
			$showing = ($howmany > $count) ? $count : $howmany;
			?>
			
			Showing <?php echo $showing; ?> of <?php echo $count;?> activities. <a href="query.php?howmany=<?php echo $_GET['howmany'];?>&query=<?php //echo $_GET['query'];?>&desc=<?php //echo $_GET['desc'];?>&TB_iframe=true&height=500&width=800" class="thickbox" title="Choose your data"><b>New selection</b></a> | <a href="about.php?height=500&width=800" class="thickbox">About</a>
			<?php } else { ?>
				<a href="query.php?TB_iframe=true&height=500&width=800" class="thickbox" title="Choose your data"><b>Choose the data to explore</b></a>	
			<?php }?>
	        </div>
	    </div>
	    
	    <div class="exhibit">
	

<?php if($_GET['query']) { ?>
 	 <!-- Establish our collections--> 
    	<div ex:role="exhibit-collection" ex:itemTypes="project, component" id="projects"></div> 
    	<div ex:role="exhibit-collection" ex:itemTypes="summary" id="summary"></div> 
    	<div ex:role="exhibit-collection" ex:itemTypes="sector" id="sector"></div> 

        <!--Set up lens templates: these are used to display each project or summary entry etc.--> 

    	<!--DATA SUMMARY--> 
        <div ex:role="lens" ex:itemTypes="summary" style="display: none" class="data-summary-container exhibit-lens exhibit-ui-protection" ex:formats="date { template: 'MMMM yyyy'; show: date }"> 
    	    <span class="data-summary-text" ex:content=".label"></span> 
    	</div> 

		<!--ORGANISATIONS-->
		<div ex:role="lens" ex:itemTypes="organisation" style="display: none" class="data-project exhibit-lens exhibit-ui-protection">
			 <div class="lens-header"><h2 ex:content=".label" class="name"></h2></div>
			 You can find other projects related to this organisation available from your current query using the menu on the left. Alternatively, you can create a new query to find places where this organisation is <a ex:href-subcontent=".?query=//iati-activity[participating-org/@ref='{{.code}}']">participating</a>.
		</div>

    	<!--PROJECTS AND COMPONENTS--> 
    	<div ex:role="lens" ex:itemTypes="project, component" style="display: none" class="data-project exhibit-lens exhibit-ui-protection" ex:formats="date { template: 'MMMM yyyy'; show: date }"> 
    	    <div class="lens-header">
		        <a ex:href-subcontent="activity/?activity={{.iati-identifier}}&TB_iframe=true&height=500&width=800" class="activitydetails" onclick="return showThickbox(this);" ex:title-subcontent="Project details {{.label}}">View details</a>
			    <h2 ex:content=".label" class="name"></h2>
			</div>
    	    <div class="lens-inner">
        		<div class="iati-image">
    			    <img ex:src-subcontent="https://chart.googleapis.com/chart?cht=map:fixed=-55,-180,73,180&chs=200x100&chld={{.recipient-country-code}}&chco=676767|EA600A&chm=f{{.recipient-country}},000000,0,0,10&chf=bg,s,F8F8F8" />
    			</div>
    			<div class="iati-key-info"> 
    				<span class="reporting-org"><span class="field-title">Reporting organisation:</span> <span ex:content=".reporting-org" class="data-reporting-org"></span></span> 
    				<span class="participating-org"><span class="field-title">Participating organisations:</span> <span ex:content=".participating-org" class="data-participating-org"></span></span> 
    			</div> 

    			<div class="iati-key-info country"> 
    				<span ex:content=".recipient-country" class="data-country"></span> 
    				<span class="dates-planned"><span ex:content=".start-date-planned"></span> - <span ex:content="if(exists(.end-date-planned),.end-date-planned,'Unspecified')"> (Planned)</span></span> 
    				<span class="dates-actual"><span ex:if-exists=".start-date-actual"><span ex:content=".start-date-actual"></span> - <span ex:content="if(exists(.end-date-actual),.end-date-actual,'Unspecified')"></span> (Actual)</span></span> 
    				<span class="activity-status"><span ex:if-exists=".activity-status"><span class="field-title">Status:</span> <span ex:content=".activity-status"></span></span></span> 
    			</div> 

    			<div ex:content=".description" class="description"></div> 

    			<div class="funding"> 
    					<span class="field-title">Commitments:</span> <span ex:content=".default-currency"></span> <span ex:content="add(.total-commitments)"></span> 
    					<span class="field-title">Expenditure:</span> <span ex:content=".default-currency"></span> <span ex:content="add(.total-expenditure,.total-disbursments,.total-reimbursment,.total-incoming-funds)"></span> 
    					<span class="field-title">Loan and Interest Repayments:</span> <span ex:content=".default-currency"></span> <span ex:content="add(.total-loan-repayment,.total-interest-repayment)"></span> 
    			</div> 
    			<div class="iati-aidtype"> 
    			    <span ex:content=".default-flow-type"></span> - <span ex:content=".default-aid-type"></span> - <span ex:content=".collaboration-type"></span> - <span ex:content=".default-tied-status"></span> 
    			</div> 

    			<div class="id"> 
    				<a ex:href-subcontent="activity/?activity={{.iati-identifier}}" class="activitydetails">View details</a> 
    			</div>
    	    </div><!--/lens-inner-->	
        </div><!--/project lens--> 

        <!--Page layout - this is where the page layout is generated -->        
        <div id="sidebar">
            <div ex:role="facet" ex:facetClass="TextSearch" id="textSearch" ex:label="Search"  ex:collectionID="projects"></div> 
            <div ex:role="facet" ex:expression=".reporting-org" ex:collectionID="projects" ex:height="60"></div> 
            <div ex:role="facet" ex:expression=".funding-org" ex:collectionID="projects" ex:height="60"></div> 

            <div ex:role="facet" ex:expression=".recipient-country-code.label" ex:height="60" ex:collectionID="projects"></div>

            <div ex:role="facet" ex:expression=".recipient-region" ex:height="60" ex:collectionID="projects"></div> 

			<div ex:role="facet" ex:expression=".activity-status" ex:height="60" ex:collectionID="projects"></div>
			
			<div ex:role="facet" ex:expression=".sector" ex:collectionID="projects"></div> 

			<div ex:role="facet" ex:expression=".policy-marker" ex:collectionID="projects"></div> 

            <div ex:role="facet" ex:expression=".participating-org" ex:collectionID="projects"></div> 
			 
			<div ex:role="facet" ex:expression=".default-aid-type" ex:height="80" ex:collectionID="projects"></div> 
           
            <div ex:role="facet" ex:expression=".type" ex:height="60" ex:collectionID="projects"></div>
        </div>
        <div id="results" ex:role="viewPanel">
            <div ex:role="view" ex:collectionID="projects" ex:grouped="false" ex:showSummary="false"></div> 

			<div ex:role="view" ex:viewClass="Tabular" ex:columns=".iati-identifier,.funding-org,.label,.total-commitments,.total-expenditure,.total-disbursment,.total-reimbursment,.total-incoming-funds,.total-loan-repayment,.total-interest-repayment" ex:collectionID="projects"></div> 

			<div ex:role="view"
			     ex:viewClass="Timeline"
			     ex:start=".start-date-actual"
			     ex:end=".end-date-actual"
			     ex:topBandUnit="year"
			     ex:colorKey=".reporting-org.label"
			     ex:topBandIntervalPixels="500"
			     ex:bottomBandIntervalPixels="1000"
			     ex:bubbleWidth="400"
			     ex:bubbleHeight="250"
			     ex:densityFactor="1"
				 ex:collectionID="projects"></div> 

			<div ex:role="view"
			  ex:viewClass="Map"
              ex:label="Location"
              ex:latlng=".latlng"
              ex:center="25.2, -32.3"
              ex:zoom="2"
              ex:bubbleWidth="300"
              ex:colorKey=".activity-status"
			  ex:collectionID="projects"
              ></div>
        </div>	 
        <br style="clear:both"/>       
    </div>
 <?php } else { ?>
 <div id="sidebar"></div>
 <div id="results" ex:role="viewPanel">
	
 	<br/>
	<br/>
	<p>
	<div class="data-project exhibit-lens exhibit-ui-protection" style="display: block;"> 
	    	    <div class="lens-header">
				    <h2 class="name"><span>Welcome to the IATI Explorer</span></h2>
				</div>
	    	    <div class="lens-inner about-explorer">
	        		<?php include("about.php"); ?>
	    	    </div>	
    </div>
	</p>

	
</div>

 <?php } ?>

</div>
    </body>
    </html>
