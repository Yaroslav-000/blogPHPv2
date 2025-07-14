<main class="main_content">  
    <?php 

        // Кол-во блоков на странице -1
        $block = 2;

        // Кол-во катрочек в блоке -1
        $card = 5;

        // Создание массива $main, в нём будут находиться класс блока, название блока, все статьи блока
        $main = array();

        // Проверка на номер страницы
        if( $mc == 0)
        {
                // Внесение в массив $main класс и название первого блока на первой странице
            $main[0] = 'new';
            $main[1] = 'Новое';
            
            // Внесение в массив  $main id первых 6 статей из массива $arti
            $i = 0;
            while( $i <= $card )
            {
                $main[$i + 2] =  $arti[$i]['id'];
                $i++;
            }
        } 
        else 
        {

            // Внесение в массив $main класс и название первого блока на 2,3,4....
            $main[0] = $cat[$mc - 1]['name_class'];
            $main[1] = $cat[$mc - 1]['name'];
            
            // Внесение в массив  $main id первых 6 статей из массива $arti с нужным id 
            $i = 0;
            $keys = array_keys(array_column($arti, 'categoris_id'), $mc);
            while( $i <= $card )
            {
                
                if ( isset($keys[$i]) !== false )
                {
                    $main[$i + 2] =  $arti[$keys[$i]]['id'];
                    $i++;
                } else 
                {
                    break;
                }
                
            }
        }

        $a = 0;
        
        while( $a <= $block - 1)
        {
            // Подсчёт всех имеющихся в массиве элементов 
            $key_quantity = count($main);

            // Внесение в массив $main класс и название второго блока
            $main[$key_quantity] = $cat[$mc + $a]['name_class'];
            $main[$key_quantity + 1] = $cat[$mc + $a]['name'];
            
            // Внесение в массив  $main id первых 6 статей с подходящим id
            $i = 0;
            $keys = array_keys(array_column($arti, 'categoris_id'), $mc + 1 + $a);
            while( $i <= $card )
            {
                if ( isset($keys[$i]) !== false )
                {
                    $main[$i + $key_quantity + 2] =  $arti[$keys[$i]]['id'];
                    $i++;
                } else 
                {
                    break;
                }
                
            }
            
            $a++;
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

            if( !isset($main[$m]))
            {
                break;
            }

            // проверка на каком блоке категорий сейчас массив, для создания ссылки на страницу со статьями этой категории
            if($main[$m] == 'new')
            {
                $block_categoris = 0;
            }
            else
            {
                foreach ($cat as &$ca) 
                {
                    
                    if( $ca['name_class'] == $main[$m]) 
                    {
                        
                        $block_categoris = $ca['id'];
                        
                    }
                }
            }

            
            ?>
            <!-- Ввывод блока, класс зависит от того какой это блок
            так что не смотря на то что блоки создаються циклом их можно стилизовать уникально -->
            <section class="flex content">
                <div class="content__block content__<?php echo $main[$m] ?>">
                    <!-- Заголовок блока -->
                    <a class="link" href="/blog_main.php?page=main-categories&categoris=<?php echo $block_categoris ?>&mpa=<?php echo '0' ?>">
                        <h2 class="title content-<?php echo $main[$m] ?>__title">
                            <?php echo $main[$m + 1] ?>
                        </h2>
                    </a>
                    <?php
                        include "blog_card.php"; 
                    ?>
                </div>
            </section> 
        <?php
        $b++;
        $m= $m + 2 + $i;
        }
    ?>
</main>    