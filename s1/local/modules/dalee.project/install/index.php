<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

class dalee_project extends CModule
{
    public $MODULE_ID = 'dalee.project';
    public $MODULE_GROUP_RIGHTS = 'Y';
    public $siteId = 's1';

    public $errors = false;

    function __construct()
    {
        $this->siteId = SITE_ID;

        $this->PARTNER_NAME = Loc::getMessage('DALEE_PROJECT_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('DALEE_PROJECT_PARTNER_URI');

        if (file_exists(__DIR__ . '/version.php')) {
            $arModuleVersion = [];
            include_once(__DIR__ . '/version.php');
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('DALEE_PROJECT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DALEE_PROJECT_MODULE_DESCRIPTION');
    }

    function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        global $DB;
        $errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/dalee.project/install/db/install.sql");

        return true;
    }

    function DoUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }
}
