<?php
    /**
    *   -A- Автор - Ягодаров Ярослав Владимирович
    *   -D- WEB-приложение "Фотоальбом"
    *   -D- Состав приложения: Файлы фреймворка: pages.php - page-controller; photos.php, simple-image.php - классы работы с фотографиями;
    *   -D- header.tpl, footer.tpl, add_photo.tpl, get_photos.tpl, show_photo.tpl - шаблоны представлений;
    *   -D- Файлы CSS-оформления: style.css - файл общих свойств для всего приложения (всех страниц);
    *   -D- add_photo.css, get_photos.css, show_photo.css - файлы для оформления соответствующих страниц;
    *   -Date- 12.08.2020
    */
    header('Content-Type: text/html; charset=utf-8');
    include ('framework/pages.php');
    $pages = new pages;
    if (!isset( $_GET['page']))
    {
        $_GET['page'] = NULL;
    }
    $pages->router($_GET['page']);