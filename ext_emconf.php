<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'List Elements for TYPO3 records',
    'description' => 'Adds list elements to tt_content, pages, and other content tables',
    'category' => 'backend',
    'version' => '1.0.0',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearcacheonload' => 0,
    'author' => 'David Steeb, b13 GmbH',
    'author_email' => 'typo3@b13.cpom',
    'author_company' => 'b13 GmbH, Stuttgart',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.9.99',
        ],
    ],
];
