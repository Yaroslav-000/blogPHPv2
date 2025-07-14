
    <?php


    // Автоматическое перенаправление на главную страницу если пользователь не в аккаунте
    if( !isset($_SESSION['acount']['id'])){
        echo'
        <script> window.setTimeout(function() { window.location = "blog_main.php"; }, 0) </script>
        ';
    }
    ?>
    <main>
        <?php

       
        // // Проверка был ли передан GET запрос
        // if ( strlen($_GET['id'] .'1') !== 1 ) {

        //     // Поиск ключа нужной статьи в массиве всех статей $arti по id статьи, полученном из GET запроса
        //     $keys = array_keys(array_column($arti, 'id'), $_GET['id']);

        //     $data = $arti[$keys[0]];
        //     echo '1';
        // }
      
        if ( strlen($_GET['edit_id'] .'1') !== 1 ) {

            // Поиск ключа нужной статьи в массиве всех статей $arti по id статьи, полученном из GET запроса
            $keys = array_keys(array_column($arti, 'id'), $_GET['edit_id']);

            $data['id'] = $arti[$keys[0]]['id'];

            $data['image'] = $arti[$keys[0]]['image'];

            $data['pubdate'] = $arti[$keys[0]]['pubdate'];
            
        } elseif(isset($_POST)) {

            // Перенос данных из  переменной $_POST, в переменную $data
            $data['categoris_id'] = $_POST['categoris_id'];
            $data['title'] = $_POST['title'];
            $data['text'] = $_POST['text'];

        } elseif ( empty($_GET)) {

            // Перенаправление пользователя в личный кабинет если не был передан не GET не POST запрос
            // С сообщением об ошибке переданной через GET запрос
            echo ' <script> window.setTimeout(function() { window.location = "blog_my-page.php?errors-editing=1"; }, 2000) </script> ';

        }

        // Проверка прошло ли с момента создания статьи больше суток
        if(  strtotime( $data['pubdate']) <  strtotime (date("Y-m-d H:i")) - 86400 )
        {
            //Если да, перенаправление пользователя в его личный кабинет
            echo '
            <script> window.setTimeout(function() { window.location = "blog_main.php?page=my-page"; }, 2000) </script>
            ';
        }
            
        ?>
        <!-- HTML форма создания статьи -->
        <section class="flex creature">
            <h2 class="title creature-title">
                Редактирование статьи 
            </h2>
            <form class="flex creature__form" action="/blog_main.php?page=editing-artiсle&edit_id=<?php echo $data['id'] ?>" method="post" enctype="multipart/form-data">
                <div class="flex creature-form__categ">
                    <h3 class="creature-form-categ__title">
                        Категоря 
                    </h3>
                    <select class="select creature-form-categ__sel" name="categoris_id">
                        <?php 
                         echo'<option class="select creature-form-categ-sel_opt" value="none">Выбор категории</option>';
                        $i = 0;

                        // Цикл в котором создаються пункты выбора категории
                        while( $i <= array_key_last($cat) )
                        {
                            if( $cat[$i]['id'] == $data['categoris_id'] ){
                                echo'
                                    <option selected="selected" class="select creature-form-categ-sel_opt" value="'. $cat[$i]['id'] .'">'. $cat[$i]['name'] .'</option>
                                ';
                            } else {
                                echo'
                                    <option class="select creature-form-categ-sel_opt" value="'. $cat[$i]['id'] .'">'. $cat[$i]['name'] .'</option>
                                ';
                            }
                            $i++;
                        }
                        ?>

                    </select>
                </div>
                <div class="flex creature-form__name">
                    <h3 class="creature-form-name__title">
                        Название
                    </h3>
                    <input class="input creature-form-name__inp" type="text" name="title" value="<?php echo
                    $data['title']; ?>">
                </div>
                <div class="flex creature-form__file">
                    <h3 class="creature-form-file__title">
                        Картинка 
                    </h3>
                    <div class="creature-form-file__preview">
                        <img class="creature-form-file-preview__img" src="/img/s<?php echo $data['image'] ?>.png" alt="">
                    </div>
                    <input class="input creature-form-file__inp-custom" type="file" placeholder="Обновить картинку" name="file_picture" value="<?php 
                    $_FILES; ?>">
                </div>
                <div class="flex creature-form__text">
                    <h3 class="creature-form-text__title">
                        Текст 
                    </h3>
                    <textarea class="creature-form-text__textarea" resize type="text" name="text" ><?php echo $data['text']; ?></textarea>
                    <div class="creature-form-text__symbols">
                        <?php
                            $symbols = mb_strlen( $data['text']);
                            echo'
                                Количество символов = '. $symbols .'     
                            ';
                        ?>
                    </div>
                </div>
                <input type="hidden" name="name-form" value="my-page__editing-article">
                <div class="flex btn-reset creature-form__com">
                    <button class="creature-form-com__btn" name="du_editing" tyep="submit" >
                        Сохранить
                    </button>
                </div>
            </form> 
        </section>
            
        </main>
