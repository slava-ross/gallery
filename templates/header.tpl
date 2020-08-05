<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title><?php print $vars['title']; ?></title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="<?php print $vars['styles']; ?>">
    </head>
    <body>
        <header>
            <ul class="main_menu">
                <li><a href="index.php?page=get_photos">Фотоальбом</a></li>
                <li><a href="index.php?page=add_photo">Добавить фотографию</a></li>
            </ul>
        </header>