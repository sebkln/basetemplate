<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(static function () {
    $extensionKey = 'basetemplate';

    ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TSconfig/Page/BackendLayouts.tsconfig',
        'Template Extension: BackendLayouts'
    );

    ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TSconfig/Page/mod.tsconfig',
        'Template Extension: Module TSconfig [mod]'
    );

    ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TSconfig/Page/rte_ckeditor.tsconfig',
        'Template Extension: Config for Rich Text Editor (CKEditor)'
    );

    ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TSconfig/Page/TCAdefaults.tsconfig',
        'Template Extension: Default values for records [TCAdefaults]'
    );

    ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TSconfig/Page/TCEFORM.tsconfig',
        'Template Extension: Form fields TSconfig [TCEFORM]'
    );

    ExtensionManagementUtility::registerPageTSConfigFile(
        $extensionKey,
        'Configuration/TSconfig/Page/TCEMAIN.tsconfig',
        'Template Extension: Page permissions et al. [TCEMAIN]'
    );
});
