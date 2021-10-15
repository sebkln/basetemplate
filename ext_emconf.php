<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Templating Starter Kit for TYPO3 v11 LTS',
    'description' => 'Use it as a base for your website configuration. Add all your Stylesheets, JavaScripts and Templates.',
    'version' => '1.0.0',
    'state' => 'stable',
    'category' => 'templates',
    'author' => 'Sebastian Klein',
    'author_email' => 'sebastian@sebkln.de',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
            'recycler' => '',
            'seo' => '',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
