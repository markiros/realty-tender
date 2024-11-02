<?php

namespace Dalee\Project\Entity;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;

/**
 * Class HouseTable
 */
class PhotoTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     */
    public static function getTableName()
    {
        return 'realty_photo';
    }

    /**
     * Returns entity map definition.
     */
    public static function getMap()
    {
        return [
            new IntegerField('id', ['primary' => true, 'autocomplete' => true]),
            new StringField('filename'),
        ];
    }
}
