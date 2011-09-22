xquery version "1.0";
(:
 To Do
   - Identify correct deletion routine.
:)
declare namespace  xdb="http://exist-db.org/xquery/xmldb";
declare namespace util="http://exist-db.org/xquery/util";
declare namespace iatie="http://tools.aidinfolabs.org/xmlns";
declare namespace explorer="http://tools.aidinfolabs.org/explorer";
declare namespace exist="http://exist.sourceforge.net/NS/exist";

declare variable $local:triggerEvent external;
declare variable $local:eventType external;
declare variable $local:collectionName external;
declare variable $local:documentName external;
declare variable $local:document external;

declare function iatie:makeAChange($doc)  { 
	for $activity in collection("//db/iati")//iati-activity[iati-identifier=$doc] return
	   update insert <change>Changed</change> into $activity
};

declare function iatie:moveDoc($activity) {
    
    <created>{xdb:store("/db/All", $activity/iati-identifier,<iati-activities>{$activity}</iati-activities>)}</created>,
    update insert <explorer:revisioncontrol><explorer:modified type="created">{current-dateTime()}</explorer:modified></explorer:revisioncontrol> into doc(concat("/db/All/", $activity/iati-identifier))//iati-activity,
    update insert <explorer:log iati-identifier="{$activity/iati-identifier/text()}"><explorer:discovered ts="{current-dateTime()}"><explorer:origin>{$local:documentName}</explorer:origin></explorer:discovered></explorer:log> following doc(concat("/db/All/", $activity/iati-identifier))//iati-activity,
    iatie:create-hash(concat("//db/All/",$activity/iati-identifier/text()),$activity)
};

declare function iatie:updateDoc($activity,$revisionData) {
    let $doc := doc(concat("//db/All/",$activity/iati-identifier/text()))
    
    return
    update replace $doc//iati-activity with $activity, 
    update insert <explorer:revisioncontrol><explorer:modified>{current-dateTime()}</explorer:modified></explorer:revisioncontrol> into doc(concat("/db/All/", $activity/iati-identifier))//iati-activity,
    update insert <explorer:modified ts="{current-dateTime()}"><explorer:origin>{$local:documentName}</explorer:origin></explorer:modified> into doc(concat("/db/All/", $activity/iati-identifier))//explorer:log,
    iatie:create-hash(concat("//db/All/",$activity/iati-identifier/text()),$activity)
};

declare function iatie:create-hash($doc as xs:string,$original)  { 
	for $activity in doc($doc)//iati-activity return
		update insert <explorer:hash>{util:md5($original)}</explorer:hash> into $activity/explorer:revisioncontrol
};

declare function iatie:parseActivity($activity) {
       let $identifier := $activity/iati-identifier/text()
       return 
       <activity>
       {if(exists(doc(concat("//db/All/",$identifier))))
        then ( <exists>{$identifier}</exists>,
            if(doc(concat("//db/All/",$identifier))//explorer:revisioncontrol/explorer:hash/text() = util:md5($activity)) 
                then (
                    <status>Unchanged</status>,
                    <before>{util:md5($activity)}</before>,
                    <test>{util:md5(doc(concat("//db/All/",$identifier)))}</test>,
                    <after>{doc(concat("//db/All/",$identifier))//explorer:revisioncontrol/explorer:hash[position()=1]/text()}</after> 
                )
                else (
                    <status>Changed</status>,
                    iatie:updateDoc($activity,doc(concat("/db/All/", $activity/iati-identifier))//explorer:revisioncontrol)
                )
        )
        else (
            (:No record existed - we are creating a new one :)
            iatie:moveDoc($activity)
            )
       } 
       </activity>

};


<output>{
for $activity in doc($local:documentName)//iati-activity 
      return
      iatie:parseActivity($activity)
}
</output>

(: iatie:makeAChange("GB-1-102603") :)

