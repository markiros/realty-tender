<?php

namespace Dalee\Project\Entity;

use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

/**
 * Class ApartmentTable
 */
class ApartmentTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'realty_apartment';
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
            new BooleanField(
                'active',
                [
                    'default' => false,
                    'title' => Loc::getMessage('APARTMENT_ENTITY_ACTIVE_FIELD'),
                    'size' => 1,
                ]
            ),
            new IntegerField(
                'number',
                [
                    'required' => true,
                    'title' => Loc::getMessage('APARTMENT_ENTITY_NUMBER_FIELD'),
                ]
            ),
            new IntegerField(
                'house_id',
                [
                    'required' => true,
                    'title' => Loc::getMessage('APARTMENT_ENTITY_HOUSE_ID_FIELD'),
                    'size' => 8,
                ]
            ),
            new BooleanField(
                'status',
                [
                    'default' => false,
                    'title' => Loc::getMessage('APARTMENT_ENTITY_STATUS_FIELD'),
                    'size' => 1,
                ]
            ),
            new IntegerField(
                'price',
                [
                    'required' => true,
                    'title' => Loc::getMessage('APARTMENT_ENTITY_PRICE_FIELD'),
                ]
            ),
            new IntegerField(
                'price_discount',
                [
                    'required' => true,
                    'title' => Loc::getMessage('APARTMENT_ENTITY_PRICE_DISCOUNT_FIELD'),
                ]
            ),
            (new Reference('house', HouseTable::class, Join::on('this.house_id', 'ref.id'))),
            (new ManyToMany('photos', PhotoTable::class))->configureTableName('realty_apartment_photo'),
        ];
    }
}
