<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(static function () {
    ExtensionManagementUtility::addUserTSConfig(
        '@import "EXT:basetemplate/Configuration/TSconfig/User.tsconfig"'
    );
});

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['basetemplate_rte'] = 'EXT:basetemplate/Configuration/RTE/Custom.yaml';
