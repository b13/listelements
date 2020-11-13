<?php
defined('TYPO3_MODE') or die();

/**
 * add fields to tt_content table
 */
$additionalColumns = [
	// add field for saving the list of elements for a list or a slider
	'list' => [
		'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tt_content.list',
		'config' => [
			'type' => 'inline',
			'foreign_table' => 'listitems',
			'foreign_field' => 'uid_foreign',
			'appearance' => [
                'showSynchronizationLink' => FALSE,
                'showAllLocalizationLink' => TRUE,
                'showPossibleLocalizationRecords' => TRUE,
                'showRemovedLocalizationRecords' => TRUE,
                'expandSingle' => TRUE,
                'newRecordLinkAddTitle' => FALSE,
                'newRecordLinkTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tt_content.list.newRecordLinkAddTitle',
                'useSortable' => TRUE,
                'useCombination' => FALSE
			],
		],
	],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $additionalColumns);
