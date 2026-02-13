<?php

defined('TYPO3') or die();

(function () {
    $majorVersion = (new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion();
    if ($majorVersion < 14) {
        $GLOBALS['TCA']['tx_listelements_item']['ctrl']['searchFields'] = 'header,subheader,bodytext,link';
    } else {
        // https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106972-TCAControlOptionSearchFieldsRemoved.html
        // the Schema API detects searchable fields (e.g. all type text or input
        // we exclude:
        $GLOBALS['TCA']['tx_listelements_item']['columns']['tablename']['config']['searchable'] = false;
        $GLOBALS['TCA']['tx_listelements_item']['columns']['fieldname']['config']['searchable'] = false;
    }
})();
