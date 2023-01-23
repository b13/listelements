<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'List Elements for TYPO3 records',
    'description' => 'Adds list elements to tt_content, pages, and other content tables',
    'category' => 'backend',
    'version' => '1.0.1',
    'state' => 'stable',
    'author' => 'David Steeb, b13 GmbH',
    'author_email' => 'typo3@b13.cpom',
    'author_company' => 'b13 GmbH, Stuttgart',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-12.99.99',
        ],
    ],
];
