<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/**
 * @global CMain $APPLICATION
 */
$APPLICATION->SetTitle('Управление домами');

$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addString("<script type='module' src='/local/templates/main/assets/admin.js'></script>");
?>

<h1 class="mb-4">Управление домами</h1>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Дом</th>
        <th>
            <button class="btn btn-outline-secondary btn-sm" @click.prevent="createHouse">Create</button>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(item, index) in houses" :key="index">
        <td v-text="item.id"></td>
        <td v-text="item.address"></td>
        <td>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary" @click.prevent="editHouse(item.id)">Edit</button>
                <button class="btn btn-outline-secondary" @click.prevent="deleteHouse(item.id)">Delete</button>
            </div>
        </td>
    </tr>
    </tbody>
</table>

<!-- Create house modal -->
<div class="modal fade" id="createHouseModal" tabindex="-1" aria-labelledby="createHouseModalLabel" aria-hidden="true" ref="createHouseModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form @submit.prevent="submitCreateHouse">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createHouseModalLabel">Создать дом</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="inputAddressCreate" class="col-sm-3 col-form-label">Адрес</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputAddressCreate" v-model="house.address">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="formFileMultipleCreate" class="col-sm-3 col-form-label">Фотогалерея</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" id="formFileMultipleCreate" multiple accept="image/*" ref="inputHousePhotosCreate">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="submit" class="btn btn-outline-primary" data-bs-dismiss="modal">Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit house modal -->
<div class="modal fade" id="editHouseModal" tabindex="-1" aria-labelledby="editHouseModalLabel" aria-hidden="true" ref="editHouseModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" v-if="house.id">
            <form @submit.prevent="submitUpdateHouse">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editHouseModalLabel">Редактировать дом [{{ house.id }}]</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="inputAddressEdit" class="col-sm-3 col-form-label">Адрес</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputAddressEdit" v-model="house.address">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="formFileMultipleEdit" class="col-sm-3 col-form-label">Фотогалерея</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" id="formFileMultipleEdit" multiple accept="image/*" ref="inputPhotosEdit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3 position-relative" v-for="(photo, index) in house.photos">
                            <button class="btn btn-sm btn-outline-secondary remove-photo-button" @click="removeHousePhoto(index)">&cross;</button>
                            <img class="img-thumbnail" :src="photo.url" alt="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отменить</button>
                    <button type="submit" class="btn btn-outline-primary" data-bs-dismiss="modal">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'); ?>
