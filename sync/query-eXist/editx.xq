xquery version "1.0";
 
declare namespace  xdb="http://exist-db.org/xquery/xmldb";
declare namespace util="http://exist-db.org/xquery/util";
declare namespace iatie="http://tools.aidinfolabs.org/xmlns";
declare namespace exist="http://exist.sourceforge.net/NS/exist";

declare function iatie:create-hash($doc,$original)  { 
	for $activity in doc($doc)//iati-activity return
		update insert <hash>{util:md5($original)}</hash> into $activity
};


for $file in collection("//db/All") return
		<out>{$file}</out>


(: iatie:create-hash('//db/All/GB-1-105801')  :)
