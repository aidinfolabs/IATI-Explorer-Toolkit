xquery version "1.0";
  
declare namespace request="http://exist-db.org/xquery/request";
declare namespace xs="http://www.w3.org/2001/XMLSchema";
declare namespace explorer="http://tools.aidinfolabs.org/explorer";
declare namespace util="http://exist-db.org/xquery/util";
declare namespace atom="http://www.w3.org/2005/Atom";


let $query:= request:get-parameter("query",0)  
let $query:= if ($query)
              then $query
              else ""
              
let $query_description := request:get-parameter("query_description",0)
let $query_description:= if ($query_description)
              then $query_description
              else concat("Results for XPATH //iati-activity",$query)

let $max := request:get-parameter("max",50)
let $max := if ($max castable as  xs:integer)
              then xs:integer($max)
              else 50

let $query := concat("collection('/db/All')","//iati-activity",$query)

return
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{$query_description }</title>
    <subtitle>New and updated aid activites included in the IATI Explorer Toolkit</subtitle>
    <link href="http://tools.aidinfolabs.org" rel="self" />
    <id>urn:iatie-feed:{util:md5($query)}</id>
    <updated>{current-dateTime()}</updated>
    <author>
        <name>IATI Explorer Toolkit</name>
        <email>aidinfo@practicalparticipation.co.uk</email>
    </author>
    
{for $activity in subsequence(util:eval($query),1,$max)
    let $lastModified := $activity/explorer:revisioncontrol/explorer:modified/text()
    let $title := $activity/*:title/text()
    let $iati-identifier:= $activity/*:iati-identifier/text()
    let $description := $activity/*:description/text()
    let $reporting-org := $activity/*:reporting-org/text()
    order by xs:dateTime($lastModified) descending  
    return 
        <entry>
            <title>New or updated: {$title}</title>
            <updated>{$lastModified}</updated>
            <link href="http://tools.aidinfolabs.org/explorer/activity/?activity={$iati-identifier}"/>
            <id>urn:iati:{$iati-identifier}</id>
            <summary>New or updated project {$title}. 
            Reported by: {$reporting-org}. 
            Description: {$description}. </summary>
        </entry>
    }   
</feed>
        