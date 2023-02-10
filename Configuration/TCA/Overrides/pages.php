<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(static function () {
    $extensionKey = 'basetemplate';

    ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TSconfig/Page.tsconfig',
        'Template Extension: Page TSconfig'
    );
});
