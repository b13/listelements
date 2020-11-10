<?php

defined('TYPO3_MODE') or die('Access denied.');

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('listitems');
})();
