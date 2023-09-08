<?php

$additionalColumns = [
    // add field for saving the list of elements for a list or a slider
    'tx_listelements_list' => [
        'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tt_content.list',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_listelements_item',
            'foreign_field' => 'uid_foreign',
            'foreign_table_field' => 'tablename',
            'foreign_match_fields' => [
                'fieldname' => 'tx_listelements_list',
            ],
            'appearance' => [
                'showSynchronizationLink' => false,
                'showAllLocalizationLink' => true,
                'showPossibleLocalizationRecords' => true,
                'showRemovedLocalizationRecords' => true,
                'expandSingle' => true,
                'newRecordLinkAddTitle' => false,
                'newRecordLinkTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tt_content.list.newRecordLinkAddTitle',
                'useSortable' => true,
                'useCombination' => false,
            ],
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $additionalColumns);

$GLOBALS['TCA']['pages']['types']['1']['showitem'] .= ',tx_listelements_list;listelements';

$GLOBALS['TCA']['pages']['types']['1']['columnsOverrides'] = [
    'tx_listelements_list' => [
        'config' => [
            'overrideChildTca' => [
                'types' => [
                    '0' => [
                        'showitem' => 'header;itemheader,--palette--;;hiddenpalette',
                    ],
                ],
            ],
        ],
    ],
];
