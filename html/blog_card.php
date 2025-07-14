<?php
                        
    $i = 0;

    // Ввывод карточек блока.
    // С каждым циклом создаёться новая карточка.
    // Кол-во карточек зависит от переменной $card
    while( $i <= $card )
    {

        // Вытаскивание необходимого ключа из массива $arti,
        //  используя при этом id находящийся в массиве $main.
        $key = array_search( $main[$i + $m + 2], array_column($arti, 'id'));

        // Поиск ключа ячейки в массиве всех лайков по id статьи 
        $likey = array_search( $main[$i + $m + 2], array_column($arts_max_likes, 'id'));

        $like_stst = 0;
        // Проверка существует ли ключ
        // Нужен если карточек меньше чем нужно
        // Выполненна таким образом так как при проверках ноль равен пустая строчка
        // И и было не возможно отделить ключ ноль от пустой строчки
        if (strlen($key . '1') == 1)
        {
            break;
        }

            // Проверка существования ссесии аккаунта 
        if (isset($_SESSION['acount']))
        {
           
            // Проверка, лайкну та ли данная статья пользователем
            foreach ($my_liks as &$my_likse) 
            {
                
                if( $arti[$key]['id'] == $my_likse) 
                {
                    
                    $like_stst = 1;
                    
                }
            }
        }

        ?>
        <!-- Ссылка обрамляющая карточку статьи, через GET запрос передаёться id  и название статьи-->
        <a class="link" href="/blog_main.php?page=article&id=<?php echo $arti[$key]['id'] ?>&title=<?php echo $arti[$key]['title'] ?>">
            <button class="post content-<?php echo $main[$m] ?>__post">
                <!-- Верхняя часть карточки, 
                в ней находяться обложка и блок текста -->
                <div class="flex post__top content-<?php echo $main[$m] ?>-post__top">
                    <!-- Обложка статьи -->
                    <div class="post-top__picture content-<?php echo $main[$m] ?>-post-top__picture">
                        <img class="post-top-picture__img content-<?php echo $main[$m] ?>-post-top-picture__img" src="/img/s<?php echo $arti[$key]['image'] ?>.png" alt="">
                    </div>
                    <!-- Блок текста, в нём название, дата и превью статьи -->
                    <div class="post-top__text content-<?php echo $main[$m] ?>-post-top__text">
                        <!-- Название статьи -->
                        <h3 class="post-top-text__title content-<?php echo $main[$m] ?>-post-top-text__title">
                            <?php echo $arti[$key]['title'] ?>
                        </h3>
                        <!-- Время публикации статьи -->
                        <time class="post-top-text__time content-<?php echo $main[$m] ?>-post-top-text__time" datetime="2022-12-9-12:35">
                            <?php echo substr($arti[$key]['pubdate'], 0, 16) ?>
                        </time>
                        <!-- превью статьи -->
                        <div class="post-top-text__preview content-<?php echo $main[$m] ?>-post-top-text__preview">
                            <?php echo $arti[$key]['text'] ?>
                        </div>
                    </div>
                </div>
                <!-- Нижний блок карточки, в нём кол-во лайков и просмотров а так же автор  -->
                <div class="flex post__bottom content-<?php echo $main[$m] ?>-post__bottom">
                    <!-- Блок лайков -->
                    <div class="flex post-bottom__like content-<?php echo $main[$m] ?>-post-bottom__like">
                        <?php 
                            // Проверка лайкну та ли данная статья пользователем, и если да, изменение картинки лайка
                            if( $like_stst == 1 )
                            {
                                ?> <img class="post-bottom-like__img" src="/img/like-on.png" alt=""> <?php
                            } 
                            else
                            {
                                ?> <img class="post-bottom-like__img" src="/img/like-off.png" alt=""> <?php
                            }
                        ?>
                        <div class="post-bottom-like__text content-<?php echo $main[$m] ?>-post-bottom-like__text">
                            <?php echo $arts_max_likes[$likey]['like'] ?>
                        </div>
                    </div>
                    <!-- Блок просмотров -->
                    <div class="flex post-bottom__views content-<?php echo $main[$m] ?>-post-bottom__views">
                        <svg class="svg post-bottom-views__svg content-<?php echo $main[$m] ?>-post-bottom-views__svg" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="442.04px" height="442.04px" viewBox="0 0 442.04 442.04" style="enable-background:new 0 0 442.04 442.04;" xml:space="preserve">
                            <path d="M221.02,341.304c-49.708,0-103.206-19.44-154.71-56.22C27.808,257.59,4.044,230.351,3.051,229.203
                                        c-4.068-4.697-4.068-11.669,0-16.367c0.993-1.146,24.756-28.387,63.259-55.881c51.505-36.777,105.003-56.219,154.71-56.219
                                        c49.708,0,103.207,19.441,154.71,56.219c38.502,27.494,62.266,54.734,63.259,55.881c4.068,4.697,4.068,11.669,0,16.367
                                        c-0.993,1.146-24.756,28.387-63.259,55.881C324.227,321.863,270.729,341.304,221.02,341.304z M29.638,221.021
                                        c9.61,9.799,27.747,27.03,51.694,44.071c32.83,23.361,83.714,51.212,139.688,51.212s106.859-27.851,139.688-51.212
                                        c23.944-17.038,42.082-34.271,51.694-44.071c-9.609-9.799-27.747-27.03-51.694-44.071
                                        c-32.829-23.362-83.714-51.212-139.688-51.212s-106.858,27.85-139.688,51.212C57.388,193.988,39.25,211.219,29.638,221.021z" />
                            <path d="M221.02,298.521c-42.734,0-77.5-34.767-77.5-77.5c0-42.733,34.766-77.5,77.5-77.5c18.794,0,36.924,6.814,51.048,19.188
                                        c5.193,4.549,5.715,12.446,1.166,17.639c-4.549,5.193-12.447,5.714-17.639,1.166c-9.564-8.379-21.844-12.993-34.576-12.993
                                        c-28.949,0-52.5,23.552-52.5,52.5s23.551,52.5,52.5,52.5c28.95,0,52.5-23.552,52.5-52.5c0-6.903,5.597-12.5,12.5-12.5
                                        s12.5,5.597,12.5,12.5C298.521,263.754,263.754,298.521,221.02,298.521z" />
                            <path d="M221.02,246.021c-13.785,0-25-11.215-25-25s11.215-25,25-25c13.786,0,25,11.215,25,25S234.806,246.021,221.02,246.021z" />
                        </svg>
                        <div class="post-bottom-views__text content-<?php echo $main[$m] ?>-post-bottom-views__text">
                            <?php echo $arti[$key]['views'] ?>
                        </div>
                    </div>
                    <!-- Автор статьи -->
                    <div class="post-bottom__author content-<?php echo $main[$m] ?>-post-bottom__author">
                        <?php echo $autho[$arti[$key]['author']-1]['name'] ?>
                    </div>
                </div>
            </button>
        </a>                           
        <?php
        $i++;
        
    } 
?>     