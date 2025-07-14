


    <!-- Секция сидбар -->
    <section class="flex sidebar">
        <!-- Блок выбора случайно статьи -->
        <div class="sidebar__block-random">
            <h2 class="title sidebar-block-random-title">
                Случайная запись
            </h2>
            <?php

                // Выбор случайной статьи ($arti - многомерный массив со статьями)
                $rand_arts = array_rand($arti, 1);
                

                ?>
                <!-- Ссылка на случайно выбранную статью -->
                <a class="link" href="/blog_main.php?page=article&id=<?php echo $arti[$rand_arts]['id'] ?>&title=<?php echo $arti[$rand_arts]['title'] ?>">
                    <button class="btn-reset sidebar-block-random__btn">
                        <img class="sidebar-block-random-btn__img" src="/img/cat-black-hole.png" alt="">
                    </button>
                </a>
                
            
        </div>
        <?php

            
            // Подсчёт лайков всех статей
            // Первый цикл работает с таблицей статей а второй с таблицей лайков.
            // Во втором цикле с каждой итерацией идёт проверка id выбранной строки таблицы статей 
            // и id выбранной в данной итерации строки таблицы лайков и в случаи успеха добавляет +1 к 
            // переменной $l (индикатор кол-ва лайков).
            // После конца итераций второго цикла кол-во лайков и id статьи заносяться в многомерный массив
            // для использования позже, переменная $a используеться как счётчик итераций первого цикла
            // для выбора конкретной стать при сравнении (без $a не работает, второй цикл не зависим 
            // от первого) и для указания конкретного места в многомерном массиве $ar содержащим в себе 
            // под конец итераций id статей и кол-во их лайков.
            $a = 0;
            
            foreach ($arti as &$art) 
            {
                
                $l = 0;

                foreach ($lik_e as &$lik) 
                {
                    
                    if( $lik['id_article'] == $arti[$a]['id']) 
                    {
                        
                        $l++;   
                        
                    }
                }

                $ar[$a]['like'] = $l;
                $ar[$a]['id'] = $arti[$a]['id'];

                $a++;
                
            }
            
            $arts_max_likes = $ar;
                
            // Функция для сортировки многомерного массива $arts_max_likes по полю лайки
            function arts_max_likes($a, $b) 
            { 
                return strnatcmp($b["like"], $a["like"]); 
            } 
                 
            // Сортировка многомерного массива $arts_max_likes
            usort($arts_max_likes, "arts_max_likes");


            // $arti многомерный массив со статьями
            $arts_max_views = $arti;
            
            // Функция для сортировки многомерного массива $arts_max_views по полю просмотры
            function arts_max_views($a, $b) 
            { 
                return strnatcmp($b["views"], $a["views"]); 
            } 
                    
            // Сортировка многомерного массива $arts_max_views
            usort($arts_max_views, "arts_max_views");

            // Проверка существования ссесии аккаунта
            if (isset($_SESSION['acount']))
            {
                // Создание масива с id всех лайкнутых статей пользователем
                $my_liks = array();
            
                $i = 0;

                // Обход всего массива с лайками статей и занесение лайкнутых статей пользователем в отдельный массив 
                foreach ($lik_e as &$lik) 
                {
                    
                    if( $lik['id_user'] == $_SESSION['acount']['id']) 
                    {
                        
                        $my_liks[$i] = $lik['id_article'];

                        $i++;
                        
                    }
                }
            }



            // Кол-во блоков в сидбаре -1
            $block = 1;

            // Кол-во катрочек в блоке -1
            $card = 2;

            // Создание массива $main, в нём будут находиться класс блока, название блока, все статьи блока
            $main = array();

            // Внесение в массив $main класс и название первого блока
            $main[0] = 'favourite';
            $main[1] = 'Самое любимое';
            
            // Внесение в массив  $main id первых 3 статей из массива $arts_max_likes
            $i = 0;
            while( $i <= $card )
            {
                $main[$i + 2] = $arts_max_likes[$i]['id'];
                $i++;
            }

            // Подсчёт всех имеющихся в массиве элементов 
            $key_quantity = count($main);
            
            // Внесение в массив $main класс и название второго блока
            $main[$key_quantity] = 'famous';
            $main[$key_quantity + 1] = 'Самое известное';
            
            // Внесение в массив  $main id первых 6 статей из массива $arts_max_views
            $i = 0;
            while( $i <= $card )
            {
                $main[$key_quantity + 2 + $i] = $arts_max_views[$i]['id'];
                $i++; 
            }

            $b = 0;

            $m = 0;

            // Создание блоков с картачками в сидбаре.
            // С каждым циклом создаёться новый блок.
            // Кол-во блоков зависит от переменной $block.
            // Сделанно именно так с заделом на возможное в будущем управление сибаром через интерфейс
            // на пример через админ панель.
            while( $b <=  $block)
            {   
                
                // Переменная $m отражает на каком блоке в данный момент находиться массив
                // и какие ячейки массива $main будут использоваться
                // $m = $b * ( 3 + $card );
                
                
                ?>
                <!-- Ввывод блока, класс зависит от того какой это блок
                так что не смотря на то что блоки создаються циклом их можно стилизовать уникально -->
                <div class="sidebar__block-<?php echo $main[$m] ?>">
                    <!-- Заголовок блока -->
                    <h2 class="title sidebar-block-<?php echo $main[$m] ?>__title">
                        <?php echo $main[$m+1] ?>
                    </h2>
                    <?php
                        include "blog_card.php"; 
                    ?>
                </div>
           <?php
            $b++;
            $m= $m + 2 + $i;
            }
        ?>
        
    </section>
