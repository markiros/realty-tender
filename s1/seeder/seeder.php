<?php
// Битрикс. Пролог файл ajax-контроллеров
define("BX_SECURITY_SHOW_MESSAGE", true);
define("BX_STATISTIC_BUFFER_USED", false);
define("NO_AGENT_CHECK", true);
define("NO_AGENT_STATISTIC", true);
define("NO_KEEP_STATISTIC", true);
define("STOP_STATISTICS", true);
define("DisableEventsCheck", true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;
use Dalee\Project\Entity\HouseTable;

Loader::includeModule('dalee.project');

for ($i = 0; $i < 1000; $i++) {
    $address = mt_rand(100, 100000000);

    $result = HouseTable::add(['address' => $address]);

    if (!$result->isSuccess()) {
        var_dump($result->getErrorMessages());
    }
}
