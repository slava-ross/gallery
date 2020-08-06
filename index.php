<?php
    /**
    *   -A- Автор - Ягодаров Ярослав Владимирович
    *   -D- WEB-приложение "Фотоальбом"
    *   -D- Состав приложения: Файлы фреймворка: pages.php - page-controller; photos.php - класс работы с фотографиями;
    *   -D- add_photo.tpl, footer.tpl, get_photos.tpl, header.tpl, show_photo.tpl - шаблоны представлений;
    *   -D- photos.json - файл для хранения информации о файлах фотографий, кодированной в формате json;
    *   -D- Файлы CSS-оформления: style.css - файл общих свойств для всего приложения (всех страниц);
    *   -D- add_photo.css, get_photos.css, photo.css - файлы для оформления соответствующих страниц;
    *   -Date- 03.08.2020
    */
    header('Content-Type: text/html; charset=utf-8');
    include ('framework/pages.php');
    $pages = new pages;
    if ( !isset( $_GET['page'] )) {
        $_GET['page'] = 'none';
    }
    $pages->router( $_GET['page'] );
?>