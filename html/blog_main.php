<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        if(isset($_GET['title'])) {
           echo '<title>'. $_GET['title'] .'</title>'; 
        } else {
            echo '<title>Сингулярность</title>';    
        } 
    ?>
    <link rel="stylesheet" href="/css/blog_header.css">
    <link rel="stylesheet" href="/css/blog_header_media.css">
    <link rel="stylesheet" href="/css/blog_sidbar.css">
    <link rel="stylesheet" href="/css/blog_sidbar_media.css">
    <link rel="stylesheet" href="/css/blog_main.css">
    <link rel="stylesheet" href="/css/blog_main_media.css">
    <link rel="stylesheet" href="/css/blog_article.css">
    <link rel="stylesheet" href="/css/blog_main-categories.css">
    <link rel="stylesheet" href="/css/blog_login.css">
    <link rel="stylesheet" href="/css/blog_signup.css">
    <link rel="stylesheet" href="/css/blog_my-page.css">
    <link rel="stylesheet" href="/css/blog_creature-article.css">
    <link rel="stylesheet" href="/css/blog_footer.css">
    <link rel="icon" href="/img/favicon.png" type="image/x-icon">
</head>

<?php
    include "blog_header.php";
    include "blog_sidbar.php";

    if (isset($_GET['page'])) {
        if ($_GET['page'] == "article"){
            include "blog_article.php";
        } elseif ($_GET['page'] == "creature-article"){
            include "blog_creature-article.php";
        } elseif ($_GET['page'] == "editing-artiсle"){
            include "blog_editing-artiсle.php";
        } elseif ($_GET['page'] == "login"){
            include "blog_login.php";
        } elseif ($_GET['page'] == "main-categories"){
            include "blog_main-categories.php";
        } elseif ($_GET['page'] == "my-page"){
            include "blog_my-page.php";
        } elseif ($_GET['page'] == "signup"){
            include "blog_signup.php";
        }
    } else {
        include "blog_card-block.php";
    }
   

    include "blog_footer.php";
?>
<script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
  <script src="./js/main.js"></script>
</html>