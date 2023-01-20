<?php

defined('TYPO3') or die('Access denied.');

(function () {
    // Hook for rendering backend preview content element
    if ((\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class))->getMajorVersion() < 12) {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] = \B13\Listelements\Hooks\DrawItem::class;
        // else PageContentPreviewRendering Listener is used
    }
})();
