$(document).ready(function() {
	query_prefix = "http://tools.aidinfolabs.org/exist/rest//db/iati/";
	root_element = "iati-activity";
	xsl ="";
	
	function updateQuery() {
			xpath = "//" + root_element + "";
			combine = ""; field_combine = ""; human_query = "";
		$("#query-builder select.facet").each(function(){
			if($(this).val()) {
				field = $(this).parent().find("h4").html();
				if($(this).attr("multiple")) {
					xpath_element = "["; element_combine = ""; field_element = field_combine + field + " is ";
					$(this).find(":selected").each(function(i, selected){
						xpath_element = xpath_element + element_combine + $(selected).parent().attr("name") + "='"+$(selected).val()+"'";
						field_element = field_element + element_combine + "'" + $(selected).html() + "'"; 
						element_combine = " or ";
					});	
					xpath = xpath + combine + xpath_element;
					human_query = human_query + field_element;
				} else {
					xpath = xpath + combine + $(this).attr("name") + "='"+$(this).val()+"'";
					human_query = human_query + field_element + "'" + $(selected).html() + "'";
				}
				combine = "]";
				field_combine = " and ";
			}
		});				
		xpath = xpath + combine;
		updateActions(xpath,human_query);
	}
	
	function updateActions(xpath,human_query) {
		$("#query").val(xpath);
		if($("select.howmany").length) {
			howmany = "&howmany=" + $("select.howmany").val();
		} else {
			howmany="";
		}
		
		if(xsl) {
			$("#querylink").html("<a href=\""+query_prefix+"?_xpath="+xpath+howmany+"&_xsl="+xsl+"\" target='_blank'>Run Query</a>");
		} else {
			$("#querylink").html("<a href=\""+query_prefix+"?_xpath="+xpath+howmany+"\" target='_blank'>Run Query</a>");
		}
		
		$("div.action").each(function(){ 
			if($(this).hasClass("stylesheet")) {
				$(this).find("a.target").attr("href",query_prefix+"?_xpath="+xpath+howmany+"&_xsl="+xsl+$(this).find(".config").html());
			} 
			if($(this).hasClass("url")) {
				url = $.unescape($(this).find(".config").html());
				url = url.replace("QUERY",escape(xpath)+howmany);
				url = url.replace("HUMAN",human_query);
				$(this).find("a.target").attr("href",url);
			}
		});
		
		$(".human_query").html((human_query)?human_query:"All projects");
		
		if($(".query_count").length) {
			$.ajax({
			  url: "/count/?format=plain&query="+escape(xpath),
			  context: document.body,
			  dataType: 'text',
			  success: function(data){
			    $(".query_count").html(data);
			  }
			});
		}
	}
	
	$("input[type='Reset']").click(function(){
		updateActions("//iati-activity","");
	});
	
	$("#query-builder select, #query-builder input").change(function() {
		updateQuery();
	});	
	
	
	updateQuery();
	
	$(".change_xsl").change(function() {
		elementName = $(this).attr("id");
		$(this).val();
	});
});

