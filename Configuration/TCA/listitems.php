<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.title',
        'label' => 'header',
        'label_alt' => 'subheader,bodytext,linklabel',
        'label_alt_force' => 1,
        'iconfile' => 'EXT:listelements/Resources/Public/Icons/Extension.svg',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'type' => 'uid_foreign:CType',
        'hideTable' => true,
        'sortby' => 'sorting_foreign',
        'delete' => 'deleted',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        // records can and should be edited in workspaces
        'shadowColumnsForNewPlaceholders' => 'uid_foreign',
        'typeicon_classes' => [
            'default' => 'mimetypes-other-other',
        ],
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'security' => [
            'ignoreWebMountRestriction' => true,
            'ignoreRootLevelRestriction' => true,
        ],
        'searchFields' => 'header,subheader,bodytext,link',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,uid_foreign,sorting_foreign,header,subheader,bodytext,link,linklabel,assets',
    ],

    'columns' => [
        'sys_language_uid' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
                ],
                'default' => 0,
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'listitems',
                'foreign_table_where' => 'AND listitems.uid=###REC_FIELD_l10n_parent### AND listitems.sys_language_uid IN (-1,0)',
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
                'type' => 'passthrough'
            ]
        ],
        'hidden' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'uid_foreign' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.title',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tt_content',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
            ],
        ],
        'sorting_foreign' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.sorting_foreign',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'max' => 4,
                'eval' => 'int',
                'checkbox' => 0,
                'range' => [
                    'upper' => 1000,
                    'lower' => 10,
                ],
                'default' => 0,
            ],
        ],
        'header' => [
            'l10n_mode' => 'prefixLangTitle',
            'l10n_cat' => 'text',
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.header',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 256,
            ],
        ],
        'subheader' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.subheader',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 256,
                'softref' => 'email[subst]',
            ],
        ],
        'layout' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.layout',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => '',
            ],
        ],
        'text' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.text',
            'config' => [
                'type' => 'text',
                'cols' => 80,
                'rows' => 15,
                'softref' => 'typolink_tag,images,email[subst],url',
            ]
        ],
        'bodytext' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.bodytext',
            'config' => [
                'type' => 'text',
                'cols' => 80,
                'rows' => 15,
                'softref' => 'typolink_tag,images,email[subst],url',
            ]
        ],
        'image' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.image.addFileReference',
                    ],
                    // custom configuration for displaying fields in the overlay/reference table
                    // to use the imageoverlayPalette instead of the basicoverlayPalette
                    'overrideChildTca' => [
                        'types' => [
                            0 => [
                                'showitem' => '
                                --palette--;;basicoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                --palette--;;audioOverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                --palette--;;videoOverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                                --palette--;;basicoverlayPalette,
                                --palette--;;filePalette',
                            ],
                        ],
                    ],
                ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
            ),
        ],
        'assets' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.assets',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'assets',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.assets.addFileReference',
                    ],
                    // custom configuration for displaying fields in the overlay/reference table
                    // to use the imageoverlayPalette instead of the basicoverlayPalette
                    'overrideChildTca' => [
                        'types' => [
                            0 => [
                                'showitem' => '
                                --palette--;;basicoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                                'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                --palette--;;audioOverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                --palette--;;videoOverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                                'showitem' => '
                                --palette--;;basicoverlayPalette,
                                --palette--;;filePalette',
                            ],
                        ],
                    ],
                ], $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext']
            ),
        ],
        'link' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 50,
                'max' => 1024,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'title' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.link',
                    ],
                ],
                'softref' => 'typolink',
            ],
        ],
        'linklabel' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.linklabel',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 256,
            ],
        ],
        'linkconfig' => [
            'label' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:listitems.linkconfig',
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
