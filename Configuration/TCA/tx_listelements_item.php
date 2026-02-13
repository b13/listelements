<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.title',
        'label' => 'header',
        'label_alt' => 'subheader,bodytext,text,linklabel',
        'label_alt_force' => 1,
        'iconfile' => 'EXT:listelements/Resources/Public/Icons/Extension.svg',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'type' => 'uid_foreign:CType',
        'hideTable' => true,
        'sortby' => 'sorting_foreign',
        'delete' => 'deleted',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => ['default' => 'tx-listelements-item'],
        'security' => [
            'ignoreWebMountRestriction' => true,
            'ignoreRootLevelRestriction' => true,
            'ignorePageTypeRestriction' => true,
        ],
    ],

    'columns' => [
        'sys_language_uid' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_listelements_item',
                'size' => 1,
                'maxitems' => 1,
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'uid_foreign' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.title',
            'config' => [
                'type' => 'group',
                'allowed' => 'tt_content',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
            ],
        ],
        'sorting_foreign' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.sorting_foreign',
            'config' => [
                'type' => 'number',
                'size' => 4,
                'max' => 4,
                'checkbox' => 0,
                'range' => [
                    'upper' => 1000,
                    'lower' => 10,
                ],
                'default' => 0,
            ],
        ],
        'tablename' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_tca.xlf:tx_listelements_item.tablename',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'fieldname' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_tca.xlf:tx_listelements_item.fieldname',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'header' => [
            'l10n_mode' => 'prefixLangTitle',
            'l10n_cat' => 'text',
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.header',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 256,
            ],
        ],
        'subheader' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.subheader',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 256,
                'softref' => 'email[subst]',
            ],
        ],
        'layout' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.layout',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [],
            ],
        ],
        'text' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.text',
            'config' => [
                'type' => 'text',
                'cols' => 80,
                'rows' => 10,
                'softref' => 'typolink_tag,images,email[subst],url',
            ],
        ],
        'bodytext' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.bodytext',
            'config' => [
                'type' => 'text',
                'cols' => 80,
                'rows' => 10,
                'softref' => 'typolink_tag,images,email[subst],url',
                'enableRichtext' => true,
            ],
        ],
        'images' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.images',
            'config' => [
                'type' => 'file',
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.images.addFileReference',
                ],
                'allowed' => 'common-image-types',
                'overrideChildTca' => [
                    'types' => [
                        0 => [
                            'showitem' => '
                            --palette--;;basicoverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::TEXT->value => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::IMAGE->value => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::AUDIO->value => [
                            'showitem' => '
                            --palette--;;audioOverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::VIDEO->value => [
                            'showitem' => '
                            --palette--;;videoOverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::APPLICATION->value => [
                            'showitem' => '
                            --palette--;;basicoverlayPalette,
                            --palette--;;filePalette',
                        ],
                    ],
                ],
            ],
        ],
        'assets' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.assets',
            'config' => [
                'type' => 'file',
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.assets.addFileReference',
                ],
                'overrideChildTca' => [
                    'types' => [
                        0 => [
                            'showitem' => '
                            --palette--;;basicoverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::TEXT->value => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::IMAGE->value => [
                            'showitem' => '
                            --palette--;;imageoverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::AUDIO->value => [
                            'showitem' => '
                            --palette--;;audioOverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::VIDEO->value => [
                            'showitem' => '
                            --palette--;;videoOverlayPalette,
                            --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\FileType::APPLICATION->value => [
                            'showitem' => '
                            --palette--;;basicoverlayPalette,
                            --palette--;;filePalette',
                        ],
                    ],
                ],
            ],
        ],
        'link' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.link',
            'config' => [
                'type' => 'link',
                'size' => 50,
                'appearance' => [
                    'browserTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.link',
                ],
            ],
        ],
        'linklabel' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.linklabel',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 256,
            ],
        ],
        'linkconfig' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.linkconfig',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                ],
                'default' => 0,
            ],
        ],
    ],
    'palettes' => [
        'linklabel' => [
            'showitem' => 'link,linklabel',
        ],
        'linklabelconfig' => [
            'showitem' => 'link,linklabel,linkconfig',
        ],
        // additional fields palette, hidden but needs to be included all the time
        'hiddenpalette' => [
            'showitem' => 'hidden,sys_language_uid,l10n_parent',
            'isHiddenPalette' => true,
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '',
        ],
    ],
];
