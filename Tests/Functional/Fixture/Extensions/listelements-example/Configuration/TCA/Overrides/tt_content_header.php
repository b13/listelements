<?php

$GLOBALS['TCA']['tt_content']['types']['header']['showitem'] .= 'tx_listelements_list;listelements,';
$GLOBALS['TCA']['tt_content']['types']['header']['columnsOverrides'] = [
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
