<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Sitepackage Starter Kit for TYPO3 CMS',
    'description' => 'Contains website templates, assets, configuration and custom code for your TYPO3 project.',
    'version' => '1.0.0',
    'state' => 'stable',
    'category' => 'templates',
    'author' => 'Sebastian Klein',
    'author_email' => 'sebastian@sebkln.de',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.6-13.4.99',
            'seo' => '',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
