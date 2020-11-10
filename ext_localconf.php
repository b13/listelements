<?php

defined('TYPO3_MODE') or die('Access denied.');

(function () {
    if (TYPO3_MODE === 'BE') {
        // Hook for rendering backend preview content element
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem'][] = \B13\Listelements\Hooks\DrawItem::class;
    }
})();