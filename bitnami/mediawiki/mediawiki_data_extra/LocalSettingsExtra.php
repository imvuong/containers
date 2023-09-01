<?php

// @see https://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

# VisualEditor
wfLoadExtension( 'VisualEditor' );
// OPTIONAL: Enable VisualEditor in other namespaces
// By default, VE is only enabled in NS_MAIN
//$wgVisualEditorNamespaces[] = NS_PROJECT;
// Enable by default for everybody
$wgDefaultUserOptions['visualeditor-enable'] = 1;
// Don't allow users to disable it
$wgHiddenPrefs[] = 'visualeditor-enable';
// OPTIONAL: Enable VisualEditor's experimental code features
//$wgVisualEditorEnableExperimentalCode = true;

# Elastica and CirrusSearch
wfLoadExtension( 'Elastica', "$IP/extensions_extra/Elastica/extension.json");
wfLoadExtension( 'CirrusSearch', "$IP/extensions_extra/CirrusSearch/extension.json");
if (getenv('MW_ELASTICSEARCH_HOSTS') != '') {
    foreach (explode(',', getenv('MW_ELASTICSEARCH_HOSTS')) as $server) {
        $wgCirrusSearchServers[] = trim($server);
    }
}
// $wgDisableSearchUpdate = true;
$wgSearchType = 'CirrusSearch';
