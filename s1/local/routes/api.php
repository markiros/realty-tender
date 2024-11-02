<?php

use Bitrix\Main\Routing\RoutingConfigurator;
use Dalee\Project\Controllers\Api;

return function (RoutingConfigurator $routes) {
    $routes->prefix('api')->group(function (RoutingConfigurator $routes) {

        $routes->get('filter',                  [Api::class, 'filter']);
        $routes->get('find-apartments',         [Api::class, 'findApartments']);

        $routes->get('houses',                  [Api::class, 'housesList']);
        $routes->get('houses/{id}',             [Api::class, 'housesDetail']);
        $routes->post('houses',                 [Api::class, 'housesCreate']);
        $routes->post('houses/update/{id}',     [Api::class, 'housesUpdate']);
        $routes->post('houses/delete/{id}',     [Api::class, 'housesDelete']);

        $routes->get('apartments',              [Api::class, 'apartmentsList']);
        $routes->get('apartments/{id}',         [Api::class, 'apartmentsDetail']);
        $routes->post('apartments',             [Api::class, 'apartmentsCreate']);
        $routes->post('apartments/update/{id}', [Api::class, 'apartmentsUpdate']);
        $routes->post('apartments/delete/{id}', [Api::class, 'apartmentsDelete']);

    });
};
