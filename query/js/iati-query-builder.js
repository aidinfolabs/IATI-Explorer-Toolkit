$(document).ready(function() {
	query_prefix = "http://tools.aidinfolabs.org/exist/rest//db/iati/";
	root_element = "iati-activity";
	xsl ="";
	
	function updateQuery() {
			xpath = "//" + root_element + "";
			combine = "";
		$("#query-builder select").each(function(){
			if($(this).val()) {
				if($(this).attr("multiple")) {
					xpath_element = "["; element_combine = "";
					$(this).find(":selected").each(function(i, selected){
						xpath_element = xpath_element + element_combine + $(selected).parent().attr("name") + "='"+$(selected).val()+"'";
						element_combine = " or ";
					});	
					xpath = xpath + combine + xpath_element;
				} else {
					xpath = xpath + combine + $(this).attr("name") + "='"+$(this).val()+"'";
				}
				combine = "]";
			}
		});				
		
		xpath = xpath + combine;
		
		$("#query").val(xpath);
		
		if(xsl) {
			$("#querylink").html("<a href=\""+query_prefix+"?_xpath="+xpath+"&_xsl="+xsl+"\" target='_blank'>Run Query</a>");
		} else {
			$("#querylink").html("<a href=\""+query_prefix+"?_xpath="+xpath+"\" target='_blank'>Run Query</a>");
		}
		
		$("div.action").each(function(){ 
			if($(this).hasClass("stylesheet")) {
				$(this).find("a.target").attr("href",query_prefix+"?_xpath="+xpath+"&_xsl="+xsl+$(this).find(".config").html());
			} 
			if($(this).hasClass("url")) {
				url = $.unescape($(this).find(".config").html());
				url = url.replace("QUERY",escape(xpath));
				$(this).find("a.target").attr("href",url);
			}
		});
		
	}
	
	$("#query-builder select, #query-builder input").change(function() {
		updateQuery();
	});	
	
	updateQuery();
	
	$(".change_xsl").change(function() {
		elementName = $(this).attr("id");
		$(this).val();
	});
});