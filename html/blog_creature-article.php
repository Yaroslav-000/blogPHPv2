
    <?php
  
    // Автоматическое перенаправление на главную страницу если пользователь не в аккаунте
    if( !isset($_SESSION['acount']['id'])){
        echo'
        <script> window.setTimeout(function() { window.location = "blog_main.php"; }, 0) </script>
        ';
    }
    ?>
    <main>
        <!-- HTML форма создания статьи -->
        <section class="flex creature">
            <h2 class="title creature-title">
                Создание статьи
            </h2>
            <form class="flex creature__form" action="/blog_main.php?page=creature-article" method="post" enctype="multipart/form-data">
                <div class="flex creature-form__categ">
                    <h3 class="creature-form-categ__title">
                        Категоря 
                    </h3>
                    <select class="select creature-form-categ__sel" name="categ">
                        <?php 
                         echo'<option class="select creature-form-categ-sel_opt" value="none">Выбор категории</option>';
                        $i = 0;
                        
                        // Цикл в котором создаються пункты выбора категории
                        while( $i <= array_key_last($cat) )
                        {
                            if( $cat[$i]['id'] == $data['categoris_id'] ){
                                echo'
                                    <option selected class="select creature-form-categ-sel_opt" value="'. $cat[$i]['id'] .'">'. $cat[$i]['name'] .'</option>
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
                    <input class="input creature-form-file__inp" type="file" name="file_picture" value="<?php 
                    $_FILES; ?>">
                </div>
                <div class="flex creature-form__text">
                    <h3 class="creature-form-text__title">
                        Текст 
                    </h3>
                    <textarea class="creature-form-text__textarea" resize type="text" name="text" ><?php echo
                    $data['text']; ?></textarea>
                    <div class="creature-form-text__symbols">
                        <?php
                            $symbols = mb_strlen( $data['text']);
                            echo'
                                Количество символов = '. $symbols .'     
                            ';
                        ?>
                    </div>
                </div>
                <input type="hidden" name="name-form" value="my-page__creature-article">
                <div class="flex btn-reset creature-form__com">
                    <button class="creature-form-com__btn" name="du_creature" tyep="submit" >
                        Создать
                    </button>
                </div>
            </form> 
        </section>
            
        </main>
