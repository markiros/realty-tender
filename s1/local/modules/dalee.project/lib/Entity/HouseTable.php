<?php

namespace Dalee\Project\Entity;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;

/**
 * Class HouseTable
 */
class HouseTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     */
    public static function getTableName()
    {
        return 'realty_house';
    }

    /**
     * Returns entity map definition.
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'id',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('APARTMENT_ENTITY_ID_FIELD'),
                    'size' => 8,
                ]
            ),
            new StringField(
                'address',
                [
                    'validation' => function () {
                        return [
                            new LengthValidator(null, 255),
                        ];
                    },
                    'title' => Loc::getMessage('HOUSE_ENTITY_ADDRESS_FIELD'),
                ]
            ),
            (new ManyToMany('photos', PhotoTable::class))->configureTableName('realty_house_photo'),
        ];
    }
}
