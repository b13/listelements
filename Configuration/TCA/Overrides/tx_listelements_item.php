<?php

defined('TYPO3') or die();

(function () {
    $childTcaTypes = [
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
    ];
    if ((\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class))->getMajorVersion() < 12) {
        $GLOBALS['TCA']['tx_listelements_item']['ctrl']['cruser_id'] = 'cruser_id';
        $GLOBALS['TCA']['tx_listelements_item']['columns']['l10n_parent']['config']['internal_type'] = 'db';
        $GLOBALS['TCA']['tx_listelements_item']['columns']['sorting_foreign']['config']['type'] = 'input';
        $GLOBALS['TCA']['tx_listelements_item']['columns']['sorting_foreign']['config']['eval'] = 'int';
        $GLOBALS['TCA']['tx_listelements_item']['columns']['sorting_foreign']['config']['internal_type'] = 'db';
        $GLOBALS['TCA']['tx_listelements_item']['columns']['images']['config'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'images',
            [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.images.addFileReference',
                ],
                // custom configuration for displaying fields in the overlay/reference table
                // to use the imageoverlayPalette instead of the basicoverlayPalette
                'overrideChildTca' => [
                    'types' => $childTcaTypes,
                ],
            ]
        );
        $GLOBALS['TCA']['tx_listelements_item']['columns']['assets']['config'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'assets',
            [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.assets.addFileReference',
                ],
                // custom configuration for displaying fields in the overlay/reference table
                // to use the imageoverlayPalette instead of the basicoverlayPalette
                'overrideChildTca' => [
                    'types' => $childTcaTypes,
                ],
            ]
        );
        $GLOBALS['TCA']['tx_listelements_item']['columns']['link']['config'] = [
            'type' => 'input',
            'renderType' => 'inputLink',
            'size' => 50,
            'max' => 1024,
            'eval' => 'trim',
            'fieldControl' => [
                'linkPopup' => [
                    'options' => [
                        'title' => 'LLL:EXT:listelements/Resources/Private/Language/locallang_db.xlf:tx_listelements_item.link',
                    ],
                ],
            ],
            'softref' => 'typolink',
        ];
    } else {
        $GLOBALS['TCA']['tx_listelements_item']['columns']['assets']['config']['overrideChildTca']['types'] = $childTcaTypes;
        $GLOBALS['TCA']['tx_listelements_item']['columns']['images']['config']['overrideChildTca']['types'] = $childTcaTypes;
    }
})();
