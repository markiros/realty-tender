<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 */

$asset = \Bitrix\Main\Page\Asset::getInstance();
$asset->addString("<script type='module' src='/local/templates/main/assets/bootstrap.bundle.min.js'></script>");
$asset->addCss('/local/templates/main/assets/bootstrap.min.css');
$asset->addCss('/local/templates/main/assets/style.css');

?>
<!doctype html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <?php $APPLICATION->ShowHead(); ?>
</head>
<body>

<nav class="py-2 bg-body-tertiary border-bottom">
    <div class="container d-flex flex-wrap">
        <ul class="nav me-auto">
            <li class="nav-item"><a href="/" class="nav-link link-body-emphasis px-2 active" aria-current="page">Квартиры в продаже</a></li>
        </ul>
        <ul class="nav">
            <li class="nav-item"><a href="/admin/houses" class="nav-link link-body-emphasis px-2">Дома</a></li>
            <li class="nav-item"><a href="/admin/apartments" class="nav-link link-body-emphasis px-2">Квартиры</a></li>
        </ul>
    </div>
</nav>

<div id="app" class="container mt-4">

<!-- #WORK_AREA# --------------------------------------------------------------------------------------------------- -->
