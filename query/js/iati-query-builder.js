	$(document).ready(function() {
		query_prefix = "http://opendatacookbook.net:8080/exist/rest//db/iati/";
		root_element = "iati-activity";
		xsl ="";
		
		$("#query-builder select, #query-builder input").change(function() {
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
					combine = "][";
				}
			});				
			
			xpath = xpath + "]";
			
			$("#query").val(xpath);
			if(xsl) {
				$("#querylink").html("<a href=\""+query_prefix+"?_xpath="+xpath+"&_xsl="+xsl+"\" target='_blank'>Run Query</a>");
			} else {
				$("#querylink").html("<a href=\""+query_prefix+"?_xpath="+xpath+"\" target='_blank'>Run Query</a>");
			}
		});	
	});