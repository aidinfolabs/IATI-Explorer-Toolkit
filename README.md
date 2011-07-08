## About

The IATI Explorer Toolkit is made up of a set of scripts that can be used along with the [eXist XML Database](http://exist-db.org) to give developers, researchers, policy makers and other actors interested in working with data from the International Aid Transparency Initiative (IATI) a head-start.

A live implementation is currently available at [http://tools.aidinfolabs.org](http://tools.aidinfolabs.org). 

The current version is a proof-of-concept based predominantly upon PHP code. It is hoped that this can be developed to offer either a suite of tools that can be deployed by anyone interested in using IATI data, or that can be offered as part of a virtual server image along the lines of the [Data Science Toolkit](http://datasciencetoolkit.org)

It is made up of a number of components:

**/sync/**
The synchronisation scripts provide a basic set of tools that check the IATI registry and fetch any updated data into eXist

**/query/** 
The query scripts provide an number of different functions for working with the data, including
* count - a simple endpoint that takes an XPATH expression and returns a count of the number of results returned.
* lists - generating lists of values found to support dynamic query interfaces
* query - a javascript based tool to help creation of xpath queries
* csv - scripts for simple csv conversion based on [IATI XSLT](https://github.com/aidinfolabs/IATI-XSLT) templates
* process - scripts to handle processing of large files
* explore - a demonstration [Simile Exhibit](http://www.simile-widgets.org/exhibit/) interface onto the data

## Design principles

The toolkit has been designed to store and work directly with the raw XML data provided by IATI Donors and **is not** designed to modify this data in any way. 

Where modification of IATI data is requires (e.g. conversion to CSV; conversion of currencies [not implemented]) this should be provided by a 'service' which is layered on top of the toolkit.

In future the design could allow for services to be chained together, augmenting, cleaning and formatting the data for users - but always making it possible to see the original data, and to transparently see what has happened to it at each stage of augmentation or manipulation. 

## Configuration

You will need an installed copy of the [eXist XML Database](http://exist-db.org) configured with an 'IATI' collection and user.

The default configuration options in the scripts expect eXist to be made exposed to the outside world via a [reverse proxy](http://demo.exist-db.org/exist/production_web_proxying.xml) at /exist/ so that the REST endpoint for the IATI data is at /exist/rest//db/iati 

Place the '/query/' directory in a web root folder (i.e. at /) and the /sync/ directory anywhere on your system. 

Check the configuration options in the load.php script and then run it at the command line to fetch and load data into your copy of eXist.

```php
php load.php
```

This script can be scheduled to run as a regular cron job.

The genindex.php script can be used to generate indexes to speed up queries. 

The **/csv/** scripts also expect a number of XSL files to be made available within eXist. Draft scripts to support this are in the **/sync/xsl/** and documentation is forthcoming (contact tim@practicalparticipation.co.uk for details if documentation is not yet available).

## ToDo

* Many of the scripts were initially developed independently and there is some duplication of code and configuration options
* Many scripts are not failure tolerant
* Work on optimisation of eXist 
* Create tool for easier loading of XSLT files to the dataset

## License

GNU AFFERO GENERAL PUBLIC LICENSE (http://www.gnu.org/licenses/agpl-3.0.html) unless otherwise stated

## Contact

Prototype developed by tim@practicalparticipation.co.uk

## Dependencies

Uses PHEXIST from http://query-exist.sourceforge.net/ licensed under the GNU General Public License (GPL)

Uses CKAN PHP Client from https://github.com/evo42/Ckan_client-PHP licensed under an open license (MIT?)
