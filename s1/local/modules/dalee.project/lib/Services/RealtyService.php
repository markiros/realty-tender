<?php

namespace Dalee\Project\Services;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Dalee\Project\Entity\ApartmentTable;
use Dalee\Project\Entity\HouseTable;
use Dalee\Project\Entity\PhotoTable;
use Exception;

class RealtyService
{
    const PHOTOS_PATH = '/upload/realty';
    const PAGE_SIZE = 10000;

    public function __construct()
    {
        try {
            Loader::IncludeModule("iblock");
        } catch (LoaderException $e) {
            ShowError('iblock module is required');
        }
    }

    public function run()
    {
        $apartments = $this->fetchApartments();

        header('Content-Type: application/json');
        die(json_encode($apartments, JSON_UNESCAPED_UNICODE));
    }

    public function fetchHouses(): array
    {
        return HouseTable::getList([])->fetchAll();
    }

    public function createHouse($entity)
    {
        $result = HouseTable::add([
            'address' => $entity['address'],
        ]);

        if (!$result->isSuccess()) {
            throw new Exception('Ошибка создания дома');
        }

        $houseId = $result->getId();
        $house = HouseTable::getByPrimary($houseId, [
            'select' => ['*', 'photos'],
        ])->fetchObject();

        if (!empty($entity['photos'])) {
            foreach ($entity['photos'] as $file) {
                $filepath = $this->uploadFile($file);
                $filename = pathinfo($filepath, PATHINFO_BASENAME);

                $result = PhotoTable::add([
                    'filename' => $filename,
                ]);
                if (!$result->isSuccess()) {
                    AddMessage2Log(implode("\n", $result->getErrorMessages()));
                }
                $photoId = $result->getId();

                $photo = PhotoTable::getByPrimary($photoId)->fetchObject();
                $house->addToPhotos($photo);
            }
            $house->save();
        }

        return [
            'id' => $houseId
        ];
    }

    public function fetchHouse($id)
    {
        $house = HouseTable::getByPrimary($id, [
            'select' => ['*', 'photos'],
        ])->fetchObject();

        if (!$house) {
            throw new Exception('Not found');
        }

        $photos = [];
        foreach ($house->getPhotos() as $photo) {
            $filename = $photo->getFilename();
            $prefix = mb_substr($filename, 0, 3);
            $photos[] = [
                'id' => $photo->getId(),
                'url' => static::PHOTOS_PATH . '/' . $prefix . '/' . $filename,
            ];
        }

        return [
            'id' => $house->getId(),
            'address' => $house->getAddress(),
            'photos' => $photos,
        ];
    }

    public function updateHouse($entity)
    {
        $houseId = $entity['id'];

        $result = HouseTable::update($houseId, [
            'address' => $entity['address'],
        ]);

        if (!$result->isSuccess()) {
            throw new Exception('Ошибка обновления дома');
        }

        $house = HouseTable::getByPrimary($houseId, [
            'select' => ['*', 'photos'],
        ])->fetchObject();

        // пришел список photos_ids - эти фотки оставляем
        $newPhotosIds = $entity['photos_ids'];

        $currentPhotos = $house->getPhotos();

        $delPhotosIds = [];
        foreach ($currentPhotos as $photo) {
            $photoId = $photo->getId();
            if (!in_array($photoId, $newPhotosIds)) {
                // удаляем те файлы, которые есть в записи, но нет в photos_ids
                $currentPhotos->removeByPrimary($photoId);
                $delPhotosIds[] = $photoId;
            }
        }

        if (!empty($entity['photos'])) {
            foreach ($entity['photos'] as $file) {
                $filepath = $this->uploadFile($file);
                $filename = pathinfo($filepath, PATHINFO_BASENAME);

                $result = PhotoTable::add([
                    'filename' => $filename,
                ]);
                if (!$result->isSuccess()) {
                    AddMessage2Log(implode("\n", $result->getErrorMessages()));
                }
                $photoId = $result->getId();

                $photo = PhotoTable::getByPrimary($photoId)->fetchObject();
                $house->addToPhotos($photo);
            }
        }

        $house->save();

        // Удаляем запись фотки @todo удалить файл
        foreach ($delPhotosIds as $delPhotosId) {
            PhotoTable::delete($delPhotosId);
        }

        return [
            'id' => $houseId,
            'newPhotosIds' => $newPhotosIds,
            'delPhotosIds' => $delPhotosIds,
        ];
    }

    public function deleteHouse($id)
    {
        $result = HouseTable::delete($id);

        if (!$result->isSuccess()) {
            throw new Exception('Ошибка удаления дома');
        }

        return [
            'id' => $id,
        ];
    }

    public function fetchApartments($houseId = null, $active = null, $status = null, $hasDiscount = null): array
    {
        $query = ApartmentTable::query()
            ->setSelect(['*', 'house'])
            ->setOrder(['id' => 'ASC'])
            ->setLimit(static::PAGE_SIZE);

        if (is_set($active)) {
            $query->where('active', $active);
        }

        if (is_set($status)) {
            $query->where('status', $status);
        }

        if ($houseId) {
            $query->where('house_id', $houseId);
        }

        if ($hasDiscount) {
            $query->where('price_discount', '>', 0);
        }

        $res = $query->exec();

        $result = [];
        while ($apartment = $res->fetchObject()) {
            $result[] = [
                'id' => $apartment->getId(),
                'active' => $apartment->getActive(),
                'status' => $apartment->getStatus(),
                'number' => $apartment->getNumber(),
                'price' => $apartment->getPrice(),
                'price_discount' => $apartment->getPriceDiscount(),
                'house_id' => $apartment->getHouse()->getId(),
                'address' => $apartment->getHouse()->getAddress(),
            ];
        }

        return $result;
    }

    public function fetchFilter()
    {
        $res = HouseTable::getList([]);
        $houses = $res->fetchAll();

        return [
            'houses' => $houses,
        ];
    }

    public function fetchApartment($id)
    {
        $apartment = ApartmentTable::getByPrimary($id, [
            'select' => ['*', 'house', 'photos', 'house.photos'],
        ])->fetchObject();

        if (!$apartment) {
            throw new Exception('Not found');
        }

        $photos = [];
        foreach ($apartment->getPhotos() as $photo) {
            $filename = $photo->getFilename();
            $prefix = mb_substr($filename, 0, 3);
            $photos[] = [
                'id' => $photo->getId(),
                'url' => static::PHOTOS_PATH . '/' . $prefix . '/' . $filename,
            ];
        }

        $housePhotos = [];
        foreach ($apartment->getHouse()->getPhotos() as $photo) {
            $filename = $photo->getFilename();
            $prefix = mb_substr($filename, 0, 3);
            $housePhotos[] = [
                'id' => $photo->getId(),
                'url' => static::PHOTOS_PATH . '/' . $prefix . '/' . $photo->getFilename(),
            ];
        }

        return [
            'id' => $apartment->getId(),
            'active' => $apartment->getActive(),
            'status' => $apartment->getStatus(),
            'number' => $apartment->getNumber(),
            'price' => $apartment->getPrice(),
            'price_discount' => $apartment->getPriceDiscount(),
            'house_id' => $apartment->getHouse()->getId(),
            'address' => $apartment->getHouse()->getAddress(),
            'photos' => $photos,
            'house_photos' => $housePhotos,
        ];
    }

    public function createApartment($entity)
    {
        $result = ApartmentTable::add([
            'active' => $entity['active'],
            'status' => $entity['status'],
            'number' => $entity['number'],
            'price' => $entity['price'],
            'price_discount' => $entity['price_discount'],
            'house_id' => $entity['house_id'],
        ]);

        if (!$result->isSuccess()) {
            throw new Exception('Ошибка создания квартиры');
        }

        $apartmentId = $result->getId();
        $apartment = ApartmentTable::getByPrimary($apartmentId, [
            'select' => ['*', 'photos'],
        ])->fetchObject();

        if (!empty($entity['photos'])) {
            foreach ($entity['photos'] as $file) {
                $filepath = $this->uploadFile($file);
                $filename = pathinfo($filepath, PATHINFO_BASENAME);

                $result = PhotoTable::add([
                    'filename' => $filename,
                ]);
                if (!$result->isSuccess()) {
                    AddMessage2Log(implode("\n", $result->getErrorMessages()));
                }
                $photoId = $result->getId();

                $photo = PhotoTable::getByPrimary($photoId)->fetchObject();
                $apartment->addToPhotos($photo);
            }
            $apartment->save();
        }

        return [
            'id' => $apartmentId
        ];
    }

    public function updateApartment($entity)
    {
        $apartmentId = $entity['id'];

        $result = ApartmentTable::update($apartmentId, [
            'active' => $entity['active'],
            'status' => $entity['status'],
            'number' => $entity['number'],
            'price' => $entity['price'],
            'price_discount' => $entity['price_discount'],
            'house_id' => $entity['house_id'],
        ]);

        if (!$result->isSuccess()) {
            throw new Exception('Ошибка обновления квартиры');
        }

        $apartment = ApartmentTable::getByPrimary($apartmentId, [
            'select' => ['*', 'photos'],
        ])->fetchObject();

        // пришел список photos_ids - эти фотки оставляем
        $newPhotosIds = $entity['photos_ids'];

        $currentPhotos = $apartment->getPhotos();

        $delPhotosIds = [];
        foreach ($currentPhotos as $photo) {
            $photoId = $photo->getId();
            if (!in_array($photoId, $newPhotosIds)) {
                // удаляем те файлы, которые есть в записи, но нет в photos_ids
                $currentPhotos->removeByPrimary($photoId);
                $delPhotosIds[] = $photoId;
            }
        }

        if (!empty($entity['photos'])) {
            foreach ($entity['photos'] as $file) {
                $filepath = $this->uploadFile($file);
                $filename = pathinfo($filepath, PATHINFO_BASENAME);

                $result = PhotoTable::add([
                    'filename' => $filename,
                ]);
                if (!$result->isSuccess()) {
                    AddMessage2Log(implode("\n", $result->getErrorMessages()));
                }
                $photoId = $result->getId();

                $photo = PhotoTable::getByPrimary($photoId)->fetchObject();
                $apartment->addToPhotos($photo);
            }
        }

        $apartment->save();

        // Удаляем запись фотки @todo удалить файл
        foreach ($delPhotosIds as $delPhotosId) {
            PhotoTable::delete($delPhotosId);
        }

        return [
            'id' => $apartmentId,
            'newPhotosIds' => $newPhotosIds,
            'delPhotosIds' => $delPhotosIds,
        ];
    }

    public function deleteApartment($id)
    {
        $result = ApartmentTable::delete($id);

        if (!$result->isSuccess()) {
            throw new Exception('Ошибка удаления квартиры');
        }

        return [
            'id' => $id,
        ];
    }

    protected function uploadFile($file): string
    {
        $filename = $file['name'];
        $hashedFilename = md5($filename . microtime(true) . mt_rand(100, 10000000)) . '.' . mb_strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $prefix = mb_substr($hashedFilename, 0, 3);
        $to = Loader::getDocumentRoot() . '/upload/realty/' . $prefix . '/' . $hashedFilename;

        mkdir(dirname($to), BX_DIR_PERMISSIONS, true);

        if (!move_uploaded_file($file['tmp_name'], $to)) {
            return '';
        }

        return $to;
    }
}
