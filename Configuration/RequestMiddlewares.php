<?php

return [
    'frontend' => [
        'basetemplate/favicon-head-tags' => [
            'target' => \Sebkln\Basetemplate\Middleware\FaviconHeadTags::class,
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
            'after' => [
                'typo3/cms-frontend/site',
            ],
        ],
    ],
];
