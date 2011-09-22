This folder contains files to be uploaded to a vanilla eXist install to get full functionality. 

## Collections  

Create the following collections

* iati - for all raw IATI data files to be loaded into. Should be updated by the sync scripts regularly.
* All - each IATI Activity gets it's own record in here when the triggers run
* extra - for temporary data to be added to - triggers import into all
* triggers - for keeping trigger xquery in
* apps - for keeping apps xquery in

## Files
Store iatiVersionControl.xq in /triggers/

Store iati.xconf in /db/system/config/db/iati/

Store iati.xconf in /db/system/config/db/extra/

Store All.xconf in /db/system/config/db/All/

## Users

Create a user called 'iati' with the same password as you use in the load.php file and no home directory. 

## Scripts

Store atom.xq in /apps/

## Loading data 

Either manually add files to the /iati/ folder or use the load.php scripts. These should be processed and added to the /All/ collection as individual documents, with revisions tracked.

### To Do

* Inserting all 