    <main class="main_content">
    
        <?php
            
            // Поиск ключа нужной статьи в массиве всех статей $arti по id статьи, полученном из GET запроса
            $keys = array_keys(array_column($arti, 'id'), $_GET['id']);

            // Поиск ключа ячейки в массиве всех лайков по id статьи 
            $likey = array_search( $_GET['id'], array_column($arts_max_likes, 'id'));

            //Увеличение просмотров на 1 при загрузке страницы и перезапись просмотров в БД
            $id = $_GET['id'];
            $views = $arti[$keys[0]]['views']+1;
            $sql = "UPDATE article SET views = ' $views ' WHERE id = ' $id ' ";
            mysqli_query( $connection, $sql ) or die(mysqli_error($connection));
            
            // Проверка существования ссесии аккаунта 
            if (isset($_SESSION['acount']))
            {
                $like_stst = 0;

                // Проверка, лайкну та ли данная статья пользователем
                foreach ($my_liks as &$my_likse) 
                {
                    
                    if( $arti[$keys[0]]['id'] == $my_likse) 
                    {
                        
                        $like_stst = 1;
                        
                    }
                }
            }

            // HTML код страницы с вставками из массива $arti по ключу нужной статьи
            echo'
            <section class="flex article">
            <div class="flex article__base">
                <h2 class="title article-base__title">
                ' . $arti[$keys[0]]['title'] . '
                </h2>
                <div class="article-base__picture">
                    <img class="article-base-picture__img" src="/img/s' . $arti[$keys[0]]['image'] . '.png" alt="">
                </div>
                <div class="article-base__text">
                ' . $arti[$keys[0]]['text'] . '
                </div>
                <div class="flex article-base__bottom">
                    <div class="flex article-base-bottom__like">
                        <form class="flex form article-base-bottom-like__form" action="/blog_main.php?page=article&id='.$_GET['id'].'&title='. $_GET['title'] .'#coments" method="post" enctype="multipart/form-data">
                            <button class="btn-reset article-base-bottom-like__btn" name="du_my-like" tyep="submit">
                                '; 
                                    // Проверка лайкну та ли данная статья пользователем, и если да, изменение картинки лайка
                                    if( $like_stst == 1 )
                                    {
                                        ?> <img class="article-base-bottom-like-btn__img" src="/img/like-on.png" alt=""> <?php
                                    } 
                                    else
                                    {
                                        ?> <img class="article-base-bottom-like-btn__img" src="/img/like-off.png" alt=""> <?php
                                    }
                                echo '
                            </button>
                            <div class="article-base-bottom-like__text">
                            ' . $arts_max_likes[$likey]['like'] . '
                            </div>
                            <input type="hidden" name="name-form" value="likes">
                            <input type="hidden" name="id_article" value='.$_GET['id'].'>
                            <input type="hidden" name="url" value='. "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" .'>
                        </form>
                    </div>
                    <div class="flex article-base-bottom__views">
                        <div class="article-base-bottom-views__picture">
                            <svg class="svg article-base-bottom-views-picture__svg" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="442.04px" height="442.04px" viewBox="0 0 442.04 442.04" style="enable-background:new 0 0 442.04 442.04;" xml:space="preserve">
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
                        </div>
                        <div class="article-base-bottom-views__text">
                        ' . $arti[$keys[0]]['views'] . '
                        </div>
                    </div>
                    <div class="flex article-base-bottom__author">
                        <div class="article-base-bottom-author__avatar">
                            <img class="article-base-bottom-author-avatar__img" src="/img/a' .  $autho[$arti[$keys[0]]['author']-1]['avatar']  . '.png" alt="">
                        </div>
                        <div class="article-base-bottom-author__name">
                        ' .  $autho[$arti[$keys[0]]['author']-1]['name']  . '
                        </div>
                    </div>
                </div>
            </div>
            <div class="article__comments">
            ';
                // Нахождение всех ключей комментариев для данной статьи по id статьи
                $keys = array_keys(array_column($com, 'article_id'), $_GET['id']);

                $a=0;

                // Колличество переменный в массиве $keys, -1 так как массив начинаеться с 0
                $b=sizeof($keys)-1;
                
                // Цикл отображения комментариев по ключам коментариев к данной статье из массива $keys
                while($a <= $b)
                {
                    echo'
                    <div class="flex article-comments__post">
                        <div class="article-comments-post__picture">
                            <img class="article-comments-post-picture__img" src="/img/a' .  $autho[$com[$keys[$a]]['author_id']-1]['avatar'] . '.png" alt="avatar">
                        </div>
                        <div class="article-comments-post__right">
                            <div class="article-comments-post-right__name">
                            ' .  $autho[$com[$keys[$a]]['author_id']-1]['name'] . '
                            </div>
                            <div class="article-comments-post-right__data">
                            ' .   substr($com[$keys[$a]]['pubdate'], 0, 16). '
                            </div>
                            <div class="article-comments-post-right__text">
                            ' .  $com[$keys[$a]]['text'] . '
                            </div>
                        </div>
                    </div>
                    ';
                    $a++;
                }

                echo'
            </div>';

            // Проверка существования ссесиии для отображения формы комментария, а так же вывода ошибки\
            // успеха создания комментария
            if( $_SESSION['acount']['id'] != ''){
                echo'
                    <div class="article__my-comments">
                    ';
                        //  HTML Форма для написания и отправки комментария
                        echo'
                        <a id="coments"></a>
                        <div class="flex article-my-comments__post">
                            <div class="article-my-comments-post__picture">
                                <img class="article-my-comments-post-picture__img" src="/img/a' .  $_SESSION['acount']['avatar'] . '.png" alt="avatar">
                            </div>
                            <div class="article-my-comments-post__right">
                                <div class="article-my-comments-post-right__name">
                                ' .  $_SESSION['acount']['name'] . '
                                </div>
                                <div class="article-my-comments-post-right__data">
                                ' .    date("Y-m-d H:i") . '
                                </div>
                                <form class="flex article-my-comments-post__form" action="/blog_main.php?page=article&id='.$_GET['id'].'&title='. $_GET['title'] .'#coments" method="post" enctype="multipart/form-data">
                                    <div class="flex article-my-comments-post-form__text">
                                        <textarea class="article-my-comments-post-form-text__textarea" resize type="text" name="text" >'.$data['text'].'</textarea>
                                        <div class="article-my-comments-post-form-text__symbols">
                                            ';
                                                $symbols = mb_strlen( $data['text']);
                                                echo'
                                                    Количество символов = '. $symbols .'     
                                                ';
                                            echo'
                                        </div>
                                    </div>
                                    <input type="hidden" name="name-form" value="comment">
                                    <div class="flex btn-reset article-my-comments-post-form__com">
                                        <button class="article-my-comments-post-form-com__btn" name="du_my-comments" tyep="submit" >
                                            Создать
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>    
                '; 
            }
        ?>
    </main>
