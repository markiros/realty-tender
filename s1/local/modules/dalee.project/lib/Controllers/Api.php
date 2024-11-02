<?php

namespace Dalee\Project\Controllers;

use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Dalee\Project\Services\RealtyService;

class Api extends Controller
{
    protected RealtyService $service;

    public function configureActions()
    {
        $rules = [
            'prefilters' => [
                new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                new ActionFilter\Csrf(false),
            ]
        ];

        return [
            'filter' => $rules,
            'findApartments' => $rules,

            'housesList' => $rules,
            'housesCreate' => $rules,
            'housesDetail' => $rules,
            'housesUpdate' => $rules,
            'housesDelete' => $rules,

            'apartmentsList' => $rules,
            'apartmentsCreate' => $rules,
            'apartmentsDetail' => $rules,
            'apartmentsUpdate' => $rules,
            'apartmentsDelete' => $rules,
        ];
    }

    public function init(): void
    {
        parent::init();

        try {
            Loader::includeModule('dalee.project');
        } catch (LoaderException $e) {
            ShowError('dalee.project module is required');
        }

        $this->service = new RealtyService();
    }

    public function filterAction(): array
    {
        return $this->service->fetchFilter();
    }

    /**
     * Публичный список квартир
     *
     * @return array
     */
    public function findApartmentsAction(): array
    {
        $houseId = null;
        $active = true;
        $status = true;
        $hasDiscount = null;

        if ($this->request->get('house')) {
            $houseId = (int)$this->request->get('house');
        }

        if ($this->request->get('hasDiscount')) {
            $hasDiscount = $this->request->get('hasDiscount') === 'true';
        }

        return $this->service->fetchApartments($houseId, $active, $status, $hasDiscount);
    }

    // Manage Houses --------------------------------------------------------
    public function housesListAction(): array
    {
        return $this->service->fetchHouses();
    }

    public function housesDetailAction($id)
    {
        return $this->service->fetchHouse($id);
    }

    public function housesCreateAction()
    {
        $house = [
            'address' => $this->request->get('address'),
        ];

        $files = $this->request->getFile('photos');
        if ($files) {
            $house['photos'] = $this->getFilesArray($files);
        }

        return $this->service->createHouse($house);
    }

    public function housesUpdateAction($id)
    {
        $house = [
            'id' => $id,
            'address' => $this->request->get('address'),
            'photos_ids' => [],
        ];

        if ($photosIds = $this->request->get('photos_ids')) {
            $house['photos_ids'] = array_map('intval', explode(',', $photosIds));
        }

        if ($files = $this->request->getFile('photos')) {
            $house['photos'] = $this->getFilesArray($files);
        }

        return $this->service->updateHouse($house);
    }

    public function housesDeleteAction($id)
    {
        return $this->service->deleteHouse($id);
    }

    // Manage Apartmens --------------------------------------------------------
    public function apartmentsListAction(): array
    {
        $houseId = null;
        $active = null;
        $status = null;
        $hasDiscount = null;

        if ($this->request->get('house')) {
            $houseId = (int)$this->request->get('house');
        }

        if ($this->request->get('hasDiscount')) {
            $hasDiscount = $this->request->get('hasDiscount') === 'true';
        }

        return $this->service->fetchApartments($houseId, $active, $status, $hasDiscount);
    }

    public function apartmentsDetailAction($id)
    {
        return $this->service->fetchApartment($id);
    }

    public function apartmentsCreateAction()
    {
        $apartment = [
            'active' => (bool)$this->request->get('active'),
            'status' => (bool)$this->request->get('status'),
            'number' => (int)$this->request->get('number'),
            'price' => (int)$this->request->get('price'),
            'house_id' => $this->request->get('house_id'),
        ];

        if ($priceDiscount = $this->request->get('price_discount')) {
            $apartment['price_discount'] = (int)$priceDiscount;
        }

        $files = $this->request->getFile('photos');
        if ($files) {
            $apartment['photos'] = $this->getFilesArray($files);
        }

        return $this->service->createApartment($apartment);
    }

    public function apartmentsUpdateAction($id)
    {
        $apartment = [
            'id' => $id,
            'active' => $this->request->get('active') === 'true',
            'status' => $this->request->get('status') === 'true',
            'number' => (int)$this->request->get('number'),
            'price' => (int)$this->request->get('price'),
            'house_id' => $this->request->get('house_id'),
            'photos_ids' => [],
        ];

        if ($priceDiscount = $this->request->get('price_discount')) {
            $apartment['price_discount'] = (int)$priceDiscount;
        }

        if ($photosIds = $this->request->get('photos_ids')) {
            $apartment['photos_ids'] = array_map('intval', explode(',', $photosIds));
        }

        if ($files = $this->request->getFile('photos')) {
            $apartment['photos'] = $this->getFilesArray($files);
        }

        return $this->service->updateApartment($apartment);
    }

    public function apartmentsDeleteAction($id)
    {
        return $this->service->deleteApartment($id);
    }

    protected function getFilesArray($files)
    {
        $photos = [];

        for ($i = 0; $i < sizeof($files['name']); $i++) {
            $photos[] = [
                'name' => $files['name'][$i],
                'full_path' => $files['full_path'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'size' => $files['size'][$i],
                'error' => $files['error'][$i],
                'type' => $files['type'][$i],
            ];
        }

        return $photos;
    }
}
