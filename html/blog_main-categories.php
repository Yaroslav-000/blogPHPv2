<main class="main_content">
    <?php 

        // Кол-во блоков на странице -1
        $block = 0;

        // Кол-во катрочек в блоке -1
        $card = 2;

        // Mod page article -мод страници статей, используеться если пользователь на 2,3,4... страницах, 
        // для смещение отображаемых статей
        $mpa = $_GET['mpa'];
        
        // Создание массива $main, в нём будут находиться класс блока, название блока, все статьи блока
        $main = array();

        if( $_GET['categoris'] == 0)
        {
            // Внесение в массив $main класс и название сатегории статей
            $main[0] = 'new';
            $main[1] = 'Новое';
        }
        else
        {
            // Внесение в массив $main класс и название первого блока на 2,3,4....
            $main[0] = $cat[$_GET['categoris'] - 1]['name_class'];
            $main[1] = $cat[$_GET['categoris'] - 1]['name'];
        }

        // Проверка на номер страницы
        if( $mpa == 0)
        {
            if($_GET['categoris'] == 0)
            {
                // Внесение в массив  $main id первых 24 статей из массива $arti
                $i = 0;
                while( $i <= $card )
                {
                    $main[$i + 2] =  $arti[$i]['id'];
                    $i++;
                }
            }
            else
            {
                // Внесение в массив  $main id первых 24 статей из массива $arti с нужным id 
                $i = 0;
                $keys = array_keys(array_column($arti, 'categoris_id'), $_GET['categoris']);
                while( $i <= $card )
                {
                    $main[$i + 2] =  $arti[$keys[$i]]['id'];
                    $i++;
                }  
            }
            
        } 
        else 
        {
            // Счётчик страницы, смещает статьи на 2,3,4... страницах
            $p = $mpa * ($card+1) +1;

            if($_GET['categoris'] == 0)
            {
                // Внесение в массив  $main id первых 24 статей из массива $arti
                $i = 0;
                while( $i <= $card )
                {
                    $main[$i + 2] =  $arti[$p]['id'];
                    $i++;
                    $p++;
                }
            }
            else
            {
                // Внесение в массив  $main id первых 24 статей из массива $arti с нужным id 
                $i = 0;
                $keys = array_keys(array_column($arti, 'categoris_id'), $_GET['categoris']);
                while( $i <= $card )
                {
                    $main[$i + 2] =  $arti[$keys[$p]]['id'];
                    $i++;
                    $p++;
                }  
            }
        }

        $b = 0;

        // Переменная $m отражает на каком блоке в данный момент находиться массив
        // и какие ячейки массива $main будут использоваться
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
            // $m = $b * ( 4 + $card )
            ?>
            <!-- Ввывод блока, класс зависит от того какой это блок
            так что не смотря на то что блоки создаються циклом их можно стилизовать уникально -->
            <section class="flex content">
                <div class="content__block content__<?php echo $main[$m] ?>">
                    <!-- Заголовок блока -->
                    <h2 class="title content-<?php echo $main[$m] ?>__title">
                        <?php echo $main[$m + 1] ?>
                    </h2>
                    <?php
                        include "blog_card.php";
                    ?>
                </div>
            </section> 
        <?php
        $b++;
        $m= $m + 2 + $i;
        }
        
        // Счётчик статей данной категории
        $ar = 0;

        // Проверка какая категория открыта, и подсчёт статей в данной категории 
        if( $_GET['categoris'] == 0)
        {
            foreach ($arti as &$art) 
            {
                $ar++;
            }
        }
        else
        {
            foreach ($arti as &$art) 
            {
                
                if( $art['categoris_id'] == $_GET['categoris']) 
                {
                    
                    $ar++;   
                    
                }
            }
        }

        // Определение колличества страниц
        $num = ceil($ar / ($card + 1));

        // Выввод ссылки на первую страницу
        ?>
            <section class="flex numbering">
                <div class="flex numbering__block">
                    <div class="numbering__first">
                        <a class="links numbering-first__link" href="/blog_main.php?page=main-categories&categoris=<?php echo $_GET['categoris'] ?>&mpa=<?php echo '0' ?>">
                            Первая
                        </a>
                    </div>
        <?php

        // Проверка, на какой странице пользователь, и не отображение страниц дальше -3 от текущей
        if( $mpa < 4 )
        {
            $i = 1;
        }
        else
        {
            $i = $mpa - 2;
            ?>
                <div class="numbering__initial-ellipsis">
                    ...
                </div>
            <?php
        }

        // Цикл создания номеров страниц
        while( $i <= $num )
        {

            // Проверка на какой странице сейчас пользователь, и если в данной итерации отображаеться текущая страница,
            // поменять ей цвет
            if( $mpa + 1 == $i )
            {
                ?>
                    <div class="numbering__current-positions">
                        <a class="links numbering-current-positions__link" href="/blog_main.php?page=main-categories&categoris=<?php echo $_GET['categoris'] ?>&mpa=<?php echo $i -1 ?>">
                            <?php echo $i ?>
                        </a>
                    </div>
                <?php
            }
            else
            {
                ?>
                    <div class="numbering__positions">
                        <a class="links numbering-positions__link" href="/blog_main.php?page=main-categories&categoris=<?php echo $_GET['categoris'] ?>&mpa=<?php echo $i -1 ?>">
                            <?php echo $i ?>
                        </a>
                    </div>
                <?php 
            }

            // Проверка на верхний порог отображаемый страниц, выход из цикла если уже отображенно +3 страницы от текущей
            if( $mpa + 3 < $i )
            {
                ?>
                    <div class="numbering__finite-ellipsis">
                        ...
                    </div>
                <?php

                break;
            }

            $i++;
        }

        // Выввод ссылки на последную страницу
        ?>
                    <div class="numbering__last">
                        <a class="links snumbering-last__link" href="/blog_main.php?page=main-categories&categoris=<?php echo $_GET['categoris'] ?>&mpa=<?php echo $num - 1 ?>">
                            Последняя
                        </a>
                    </div>
                </div>
            </section>
            
        <?php
    
    ?>
</main>
