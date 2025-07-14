<?php

    // Подключение к бд
    $host = '127.0.0.1';
    $servername = "db";
    $username = 'root';
    $pass = 'root';
    $bd = 'blog_bd';
    $connection = mysqli_connect($servername, $username, $pass, $bd);


    // Объектно-ориентированный способ подключения БД
    // $connection = new mysqli($host, $username, $pass, $bd);

    // Проверка удачно ли подключение к БД
    if( $connection == false )
    {
        echo 'Не удаёться подключиться к БД! <br>';
        echo mysqli_connect_errno();
        exit;

    }

    // Запрос на информацию из таблицы статьи отсортирован по дате
    $pubdate = "SELECT * FROM article ORDER BY pubdate DESC";
    $article_pubdate = mysqli_query($connection, $pubdate);

    // Запрос на информацию из таблицы Категории статей
    $categoris = "SELECT * FROM article_categoris";
    $article_categoris = mysqli_query($connection, $categoris);

    // Запрос на информацию из таблицы аккаунты
    $account = "SELECT * FROM account";
    $author = mysqli_query($connection, $account);

    // Запрос на информацию из таблицы комментарии отсортирован по дате
    $coments = "SELECT * FROM coments ORDER BY pubdate DESC";
    $coment = mysqli_query($connection, $coments);

    // Запрос на информацию из таблицы лайки
    $like_bd = "SELECT * FROM lik_e ";
    $like_mysqli = mysqli_query($connection, $like_bd);

    // Переннос данных из таблицы категории в массив
    $a = 0;

    while ($cats = mysqli_fetch_assoc($article_categoris)) 
    {

        $cat[$a] = $cats;

        $a++;
    }

    // Переннос данных из таблицы статьи в массив
    $b = 0;

    while ($articles = mysqli_fetch_assoc($article_pubdate)) 
    {

        $arti[$b] = $articles;

        $b++;
    }

    // Переннос данных из таблицы аккаунты в массив
    $c = 0;

    while ($authors = mysqli_fetch_assoc($author)) 
    {

        $autho[$c] = $authors;

        $c++;
    }

    // Переннос данных из таблицы комментарии в массив
    $d = 0;

    while ($commentary = mysqli_fetch_assoc($coment)) 
    {

        $com[$d] = $commentary;

        $d++;
    }

    // Переннос данных из таблицы лайки в массив
    $f = 0;

    while ($like_while = mysqli_fetch_assoc($like_mysqli)) 
    {

        $lik_e[$f] = $like_while;

        $f++;
    }

?>