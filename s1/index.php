<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/**
 * @global CMain $APPLICATION
 */
$APPLICATION->SetTitle('Realty Tender');

$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addString("<script type='module' src='/local/templates/main/assets/app.js'></script>");
?>

<h1 class="mb-4">Квартиры в продаже</h1>

<div class="row">
    <div class="col-md-9">
        <table class="table table-bordered" v-if="apartments.length">
            <thead>
            <tr>
                <th>ID</th>
                <th>Дом</th>
                <th>Номер</th>
                <th>Активность</th>
                <th>Статус</th>
                <th>Стоимость</th>
                <th>Стоимость со скидкой</th>
                <th></th>
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
                    <button class="btn btn-outline-secondary btn-sm" @click="showApartment(item.id)">...</button>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="alert alert-light" role="alert" v-else>
            Не найдены квартиры, удовлетворяющие условиям фильтра.
        </div>
    </div>
    <div class="col-md-3">
        <form class="mb-4" @submit.prevent="submitFilter">
            <label for="country" class="form-label">Фильтрация по дому:</label>
            <select class="form-select" v-if="filterData.houses?.length" v-model="filter.house">
                <option value="">Все...</option>
                <option v-for="(item, index) in filterData.houses" :value="item.id" :key="index" v-text="item.address"></option>
            </select>
            <div class="form-check mt-2 mb-4">
                <input type="checkbox" class="form-check-input" id="has-discount" v-model="filter.hasDiscount">
                <label class="form-check-label" for="has-discount">Фильтрация по наличию скидки</label>
            </div>
            <button type="submit" class="btn btn-outline-primary">Фильтровать</button>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="apartmentShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" ref="apartmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" v-if="apartment.id">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Квартира [{{ apartment.id }}]</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th class="col-sm-4">ID</th>
                        <td>{{ apartment.id }}</td>
                    </tr>
                    <tr>
                        <th>Дом</th>
                        <td>{{ apartment.address }}</td>
                    </tr>
                    <tr>
                        <th>Номер</th>
                        <td>{{ apartment.number }}</td>
                    </tr>
                    <tr>
                        <th>Активность</th>
                        <td>{{ apartment.active ? 'Да' : 'Нет' }}</td>
                    </tr>
                    <tr>
                        <th>Статус</th>
                        <td>{{ apartment.status ? 'В продаже' : 'Не в продаже' }}</td>
                    </tr>
                    <tr>
                        <th>Стоимость</th>
                        <td>{{ apartment.price }}</td>
                    </tr>
                    <tr>
                        <th>Стоимость со скидкой</th>
                        <td>{{ apartment.price_discount }}</td>
                    </tr>
                </table>

                <h4 class="mt-5">Фотогалерея квартиры</h4>
                <div class="row" v-if="apartment.photos.length">
                    <div class="col-md-3 mb-3" v-for="(photo) in apartment.photos">
                        <img :src="photo.url" class="img-thumbnail" alt="">
                    </div>
                </div>
                <div v-else><span class="badge text-bg-secondary">Нет фото</span></div>

                <h4 class="mt-5">Фотогалерея дома</h4>
                <div class="row" v-if="apartment.house_photos.length">
                    <div class="col-md-3 mb-3" v-for="(photo) in apartment.house_photos">
                        <img :src="photo.url" class="img-thumbnail" alt="">
                    </div>
                </div>
                <div v-else><span class="badge text-bg-secondary">Нет фото</span></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<?php require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'); ?>
