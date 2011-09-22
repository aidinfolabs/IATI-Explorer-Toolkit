

declare namespace  xdb="http://exist-db.org/xquery/xmldb";
declare namespace util="http://exist-db.org/xquery/util";

xdb:register-database("org.exist.xmldb.DatabaseImpl", true()),
let $isLoggedIn := xdb:login("xmldb:exist:///db", "admin", "")

let $results := <output>{
	for $activity in subsequence(//iati-activity,1,10) 
		let $return := xdb:store("/db/All", $activity/iati-identifier,<iati-activities>{$activity}</iati-activities>)
		return <return>{$return}</return>	
} </output> 

return 	
for $identifier in doc(/db/All/GB-1-102603)//iati-activity return 
		<id>{$identifier}</id>

	
	