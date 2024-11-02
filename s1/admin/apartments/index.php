<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/**
 * @global CMain $APPLICATION
 */
$APPLICATION->SetTitle('Управление квартирами');

$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addString("<script type='module' src='/local/templates/main/assets/admin.js'></script>");

?>

<h1 class="mb-4">Управление квартирами</h1>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Дом</th>
        <th>Номер</th>
        <th>Активность</th>
        <th>Статус</th>
        <th>Стоимость</th>
        <th>Стоимость со скидкой</th>
        <th>
            <button class="btn btn-outline-secondary btn-sm" @click.prevent="createApartment">Create</button>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(item, index) in apartments" :key="index">
        <td v-text="item.id"></td>
        <td v-text="item.address"></td>
        <td v-text="item.number"></td>
        <td v-text="item.active ? 'Да' : 'Нет'"></td>
        <td v-text="item.status ? 'В продаже' : 'Не в продаже'"></td>
        <td v-text="item.price"></td>
        <td v-text="item.price_discount"></td>
        <td>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary" @click.prevent="editApartment(item.id)">Edit</button>
                <button class="btn btn-outline-secondary" @click.prevent="deleteApartment(item.id)">Delete</button>
            </div>
        </td>
    </tr>
    </tbody>
</table>

<!-- Create apartment modal -->
<div class="modal fade" id="createApartmentModal" tabindex="-1" aria-labelledby="createApartmentModalLabel" aria-hidden="true" ref="createApartmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form @submit.prevent="submitCreateApartment">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createApartmentModalLabel">Создать квартиру</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-sm-9 offset-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck1" v-model="apartment.active">
                                <label class="form-check-label" for="gridCheck1">
                                    Активность
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck2" v-model="apartment.status">
                                <label class="form-check-label" for="gridCheck2">
                                    В продаже
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputHouse" class="col-sm-3 col-form-label">Дом</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="inputHouse" v-model="apartment.house_id">
                                <option value="">Выберите дом...</option>
                                <option v-for="house in houses" :value="house.id" v-text="house.address"></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputNumber" class="col-sm-3 col-form-label">Номер</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputNumber" v-model="apartment.number">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPrice" class="col-sm-3 col-form-label">Стоимость</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputPrice" v-model="apartment.price">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPriceDiscount" class="col-sm-3 col-form-label">Стоимость со скидкой</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputPriceDiscount" v-model="apartment.price_discount">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPriceDiscount" class="col-sm-3 col-form-label">Фотогалерея</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" id="formFileMultiple1" multiple accept="image/*" ref="inputPhotosCreate">
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

<!-- Edit apartment modal -->
<div class="modal fade" id="editApartmentModal" tabindex="-1" aria-labelledby="editApartmentModalLabel" aria-hidden="true" ref="editApartmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" v-if="apartment.id">
            <form @submit.prevent="submitUpdateApartment">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editApartmentModalLabel">Редактировать квартиру [{{ apartment.id }}]</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-sm-9 offset-sm-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck1" v-model="apartment.active">
                                <label class="form-check-label" for="gridCheck1">
                                    Активность
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck2" v-model="apartment.status">
                                <label class="form-check-label" for="gridCheck2">
                                    В продаже
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputHouse" class="col-sm-3 col-form-label">Дом</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="inputHouse" v-model="apartment.house_id">
                                <option value="">Выберите дом...</option>
                                <option v-for="house in houses" :value="house.id" v-text="house.address"></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputNumber" class="col-sm-3 col-form-label">Номер</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputNumber" v-model="apartment.number">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPrice" class="col-sm-3 col-form-label">Стоимость</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputPrice" v-model="apartment.price">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPriceDiscount" class="col-sm-3 col-form-label">Стоимость со скидкой</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="inputPriceDiscount" v-model="apartment.price_discount">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPriceDiscount" class="col-sm-3 col-form-label">Фотогалерея</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" id="formFileMultiple2" multiple accept="image/*" ref="inputPhotosEdit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3 position-relative" v-for="(photo, index) in apartment.photos">
                            <button class="btn btn-sm btn-outline-secondary remove-photo-button" @click="removeApartmentPhoto(index)">&cross;</button>
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
