<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
        '@import "EXT:basetemplate11/Configuration/TSconfig/User.tsconfig"'
    );
});

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['basetemplate11_rte'] = 'EXT:basetemplate11/Configuration/RTE/Custom.yaml';
