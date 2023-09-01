# MediaWiki packaged by Bitnami

## Configuration

### Enable Elasticsearch support

Untar CirrusSearch and Elastica extensions

```console
tar -xvzf CirrusSearch-REL1_40-7bd319c.tar.gz
tar -xvzf Elastica-REL1_40-3e076c7.tar.gz
```

Update to the instructions to the extensions/CirrusSearch/README.md file 

Get Elasticsearch up and running somewhere. Only Elasticsearch v6.8 is supported.  If you will be
running Elasticsearch on a host separate from the mediawiki installation see
https://www.elastic.co/guide/en/elasticsearch/reference/current/modules-network.html. Be careful
with the network configuration, never expose an unprotected node to the internet.

Place the CirrusSearch extension in your extensions directory.
You also need to install the Elastica MediaWiki extension.
Add this to LocalSettings.php:
 wfLoadExtension( 'Elastica' );
 wfLoadExtension( 'CirrusSearch' );
 $wgDisableSearchUpdate = true;

Configure your search servers in LocalSettings.php if you aren't running Elasticsearch on localhost:
 $wgCirrusSearchServers = [ 'elasticsearch0', 'elasticsearch1', 'elasticsearch2', 'elasticsearch3' ];
There are other $wgCirrusSearch variables that you might want to change from their defaults.

Now run this script to generate your elasticsearch index:

```console
docker exec -it mediawiki-mediawiki-1 bash -c 'cd /opt/bitnami/mediawiki && php maintenance/run.php /opt/bitnami/mediawiki/extensions_extra/CirrusSearch/maintenance/UpdateSearchIndexConfig.php'
```

Now remove $wgDisableSearchUpdate = true from LocalSettings.php.  Updates should start heading to Elasticsearch.

Next bootstrap the search index by running:

```console
docker exec -it mediawiki-mediawiki-1 bash -c 'cd /opt/bitnami/mediawiki && php maintenance/run.php /opt/bitnami/mediawiki/extensions_extra/CirrusSearch/maintenance/ForceSearchIndex.php --skipLinks --indexOnSkip'
```

```console
docker exec -it mediawiki-mediawiki-1 bash -c 'cd /opt/bitnami/mediawiki && php maintenance/run.php /opt/bitnami/mediawiki/extensions_extra/CirrusSearch/maintenance/ForceSearchIndex.php --skipParse'
```

Note that this can take some time.  For large wikis read "Bootstrapping large wikis" below.

Once that is complete add this to LocalSettings.php to funnel queries to ElasticSearch:
 $wgSearchType = 'CirrusSearch';

and restart MediaWiki container.