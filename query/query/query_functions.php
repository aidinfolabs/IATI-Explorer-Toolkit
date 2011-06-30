<?php

function listValues($list) {
	$cache_life = CACHE_LIFETIME + (rand(0,14400)-7200); //Set cache to 1 day +/- 2 hours so that cache refresh is staged (i.e. one page load doesn't get hit by it all)
//	$list_server = "http://".$_SERVER['SERVER_NAME']."/lists/";
	$list_server = "http://tools.aidinfolabs.org/lists/";

	$codelist_data = json_decode(c_file_get_contents($list_server."?list=$list",$cache_life));

	foreach($codelist_data->codelist->items as $code) {
		$data[$code->code] = $code->name; 
	}

	return $data;
}

function codeListValues($codelist = null) {

		$cache_life = CACHE_LIFETIME + (rand(0,14400)-7200); //Set cache to 1 day +/- 2 hours so that cache refresh is staged (i.e. one page load doesn't get hit by it all)

		$codelist_data = json_decode(c_file_get_contents(CODELIST_API."codelists/".$codelist.CODELIST_API_SUFFIX,$cache_life));
		foreach($codelist_data->codelist->$codelist as $code) {
			$data[$code->code] = $code->name; 
		}

		return $data;
}

function generateSelect($id = null, $multiple = false, $codelist = null, $list = null) {

	if($codelist) {
		$data = codeListValues($codelist);
	} elseif($list) {
		$data = listValues($list);
	} else {
		$data = array("NA"=>"No values available");
	}
	
	$output.="<select name=\"".$id."\"".($multiple ? " MULTIPLE": "").">";
		foreach($data as $code => $name) {
			$output .= "<option value='$code'>$name</option>";
		}
	$output .= "</select>";

	return $output;	
}