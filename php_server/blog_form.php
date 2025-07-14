<?php session_start(); // Запуск ссесии
     setcookie("acount", time()+60*60*24*30); // Создание куки 
    include "blog_bd-connect.php"; 
    //header('Content-type: application/json');
    header("Access-Control-Allow-Origin: *");
    
    // Занесение пост запроса в переменную
    $data = $_POST;

    // Создание массива для ошибок
    $errors = array();

    // Создание массива для ответа
    $res = array();

    
    // Проверка что за запрос и откуда он был отправлен
///////////////////////////////////////////////////////////////////////////////////////////
    if($data['name-form'] == 'login') {
        
        $UID = 0;
        $log = 0;
        // Проверка введино ли имя
        if( trim( $data['name']) == '' ){

            // Запись о том что не введено имя
            $errors[] = 'Введите имя!';
        }

        // Проверка введён ли пароль
        if( $data['password'] == '' ){

            // Запись о том что не введён пароль
            $errors[] = 'Введите пароль!';
        }

        
        // Обход всего цикла $acco
        foreach ( (array) $autho as &$acco) {
            
            // Проверка соотвецтвия имени из БД с введёным именем
            if( $acco['name'] == $data['name'] ){
                
                // Проверка совпадают ли пароль из БД с введёным паролем
                if( $acco['password'] == $data['password'] ){
                
                    $UID = $acco['id'];
                    $log = 1;

                } else {

                    // Запись о том что пароль введён не верно
                    $errors[] = 'Пароль введён не верно.';
                } 

            } 
        
        }

        // Проверка есть ли ошибка о не правильном вводе пароля
        if( isset($errors[0]) and $errors[0] != 'Пароль введён не верно.') {
            
            // Запись о том что пользователя с таким именем не существует
            $errors[] = 'Такого пользователя не существует.';
        }

        // Проверка, успешна ли аутинтификация
        if( $log == 0 ){
            
            // Отправка ответа с ошибкой при заполнении формы
            $res['login']['status'] = 1;
            $res['login']['messeg'] = $errors[0];

        }else {

            // Обход массива $acco
            foreach ($autho as &$acco) {
                
                // Проверка соотвецтвия UID введёного пользвателя с UID в массиве
                if( $acco['id'] == $UID ){
                    
                    // Запись в ссесию данных аккаунта
                    $_SESSION['acount'] = $acco;
                    
                    // Проверка нужно ли запоминать пользователя
                    if( isset($data['remember']) and $data['remember'] == 'Yes' ){
                        
                        // Запись в ссесию что пользователя нужно запомнить
                        $_SESSION['acount']['remember'] = 'Yes';
                        
                    }else{

                        // Запись в ссесию что пользователя не нужно запоминать
                        $_SESSION['acount']['remember'] = 'No';

                    }

                } 
            
            }
            
                // Оповещение об удачной авторизации, и перенаправление на страницу профиля
                $res['login']['status'] = 0;
                $res['login']['messeg'] = "Вы успешно <br> авторизовались!";
                $res['redirection']['url'] = "blog_main.php?page=my-page";
        }

       

///////////////////////////////////////////////////////////////////////////////////////////   
    } elseif($data['name-form'] == 'signup'){


        // Проверка введено ли имя
        if( trim( $data['name']) == '' ){

            $errors[] = 'Введите имя!';
        }

        // Обход массива $autho, в целях убедиться в уникальности имени нового пользователя
        foreach ($autho as &$name) {

            if( $name['name'] == $data['name'] ){

                $errors[] = 'Такое имя уже есть!';

            }
        
        }

        // Проверка отсуцтвия аватара у нового пользователя
        if ( $_FILES['file_avatar']['name'] == '' ) {

            // Установка стандартного аватара для нового пользователя
            $avatar_name['avatar'] = 0;

        } else    

            // Проверка расширения файла
            if ( $_FILES['file_avatar']['type'] !=  "image/png" ) {
                
            $errors[] = "Расщирение файла не png";

        } else 

            // Проверка размера файла
            if ( $_FILES['file_avatar']['size'] > 2097152 ) {

                $errors[] = 'Размер файла больше 2 мегабайт';

        } else {

            // Определение номера аватара нового пользователя
            list($avatar_name) = array_slice( $autho, -1);
            $avatar_name['avatar'] = $avatar_name['avatar'] +1;
            
            // Сохранение файла
            move_uploaded_file( $_FILES['file_avatar']['tmp_name'], 
            'E:\PROGRAM\Vork\locHost\OSPanel\domains\blog\img\a'.$avatar_name['avatar'].'.png');
        }

        // Проверка, был ли введён первый пароль
        if( $data['password_1'] == '' ){

            $errors[] = 'Введите пароль!';
        }

        // проверка был ли введён второй пароль
        if( $data['password_2'] != $data['password_1'] ){

            $errors[] = 'Пароли должны совпадать!';
        }

        // Проверка на отсуцтвие ошибок
        if( empty($errors) ){
            
            // Задание переменных для SQL переменной
            $name = $data['name'];
            $avatar = $avatar_name['avatar'];
            $password = $data['password_1'];
            // $password = password_hash($password, PASSWORD_DEFAULT); Шифрование пароля

            // Создание SQL переменной для SQL запроса создания новой записи
            $sql = "INSERT INTO account (name, avatar, password) VALUES ('$name', ' $avatar', '$password')";
            
            // Совершение SQL запроса, и сразу проверка удачен ли он
            if (mysqli_query($connection, $sql)) {

                // Вывод сообщения об успешной регистрации

                $res['signup']['status'] = 0;
                $res['signup']['messeg'] = "Вы успешно <br> зарегестрировались!";

                // Проверка была ли выбрана галочка "сразу авторизоваться"
                if( $data['avto_autho'] == "Yes" ) {

                    // Запрос к БД аккаунтов и взятие из неё всех аккаунтов
                    $account = "SELECT * FROM account";
                    $author = mysqli_query($connection, $account);

                    // Цикл, переносящий всю БД аккаунтов в массив
                    while ($authors = mysqli_fetch_assoc($author)) {

                        $autho[$c] = $authors;
                    
                        $c++;
                    }

                    // Обход всего массива $autho, с целью найти вновь зарегестрированный аккаунт
                    // И занести в ссесию его данные
                    foreach ($autho as &$acco) {
                        
                        // Проверка являеться ли это массив нового пользователя
                        if( $acco[1] == $data[0] ){
                            
                            // Присвоение данных массива ссесии
                            $_SESSION['acount'] = $acco;

                            // Перенаправление в личный кабинет
                            $res['redirection']['url'] = "blog_main.php?page=my-page";
                        } 
                    
                    }
                }

            } else {

                // Вывод ошибки SQL запроса 
                $res['signup']['status'] = 1;
                //$res['signup']['messeg'] = ''. "Error: " . $sql . "<br>" . mysqli_error($connection) .'';
                $res['signup']['messeg'] = "Ошибка соединения <br> попробуйте повторить позже";
            }

        } else {

            // Вывод ошибок при заполнении формы регистрации
            $res['signup']['status'] = 1;
            $res['signup']['messeg'] = $errors[0];

        }

///////////////////////////////////////////////////////////////////////////////////////////        
    } elseif($data['name-form'] == 'my-page__setting'){

        $file = 0;
                
        // Проверка, был ли загружен новый аватар
        // Сделенно именно так что бы если изменяеться только имя\аватар, 
        // не писалась ошибка что второе не загруженно\ не написанно
        if ( $_FILES['file_avatar']['name'] == '' ) {

            // Показатель того что новый файл аватара не был загружен
            $file = 2;

        } else    

            // Проверка расширения файла
            if ( $_FILES['file_avatar']['type'] !=  "image/png" ) {

                // Запись о том что расширение файла не png
                $errors['files'] = "Расщирение файла не png";

        } else 

            // Проверка размера файла 
            if ( $_FILES['file_avatar']['size'] > 2097152 ) {

                // Запись о том что размер файла больше 2 мегабайт
                $errors['files'] = 'Размер файла больше 2 мегабайт';

        } else 

            // Проверка уникальный ли старый аватар\ или нулевой аватар
            if( $_SESSION['acount']['avatar'] == 0 ) {

                $file = 1;
                $avatar_name = 0;
                
                // Обход всего масива $name, алгоритм для 
                // поиска самого большого идентификатора аватара
                // Эту систему вполне можно изменить, просто привязав идентификатор аватара к id, 
                // пользователя, если он создаёт аккаунт без аватара, идентификатор аватара 0,
                // а когда добавляет свой аватар, то просто присваеваеться его id.
                foreach ($autho as &$name) {

                    if( $name['avatar'] > $avatar_name ){
                        
                        $avatar_name = $name['avatar'];
                    }            
                }

                $avatar_name++;

                // Сохранение нового аватара на сервер
                move_uploaded_file( $_FILES['file_avatar']['tmp_name'], 
                'D:\PROGRAM\Vork\locHost\OSPanel\domains\blog\img\a'.$avatar_name.'.png');

                $id = $_SESSION['acount']['id'];

                // Создание переменной SQL запроса для изменения идентификатора аватара
                $sql = "UPDATE account SET avatar = '$avatar_name' WHERE id = '$id' ";

                // SQL запрос для изменения идентификатора аватара
                mysqli_query($connection, $sql) or die(mysqli_error($connection));

                // Обновленее ссесии, указанае в ней актуального идентификатора аватара
                $_SESSION['acount']['avatar'] = $avatar_name;
               
        } else {

            $file = 1;

            // Присвоение переменной для сохранения файла актуального имени аватара
            $avatar_name = $_SESSION['acount']['avatar'];

            // Сохранение нового аватара на сервер
            move_uploaded_file( $_FILES['file_avatar']['tmp_name'], 
            'D:\PROGRAM\Vork\locHost\OSPanel\domains\blog\img\a'.$avatar_name.'.png');

        }

        // Переменна отображающая вводил ли пользователь новое имя
        $orig = 0;
        
        // Проверка ввёл ли пользователь новое имя
        if( $data['name'] == '' ){
            
            $orig = 2;
        
        } else 
            
            // Проверка совпадает ли новое имя со старым
            if( $data['name'] == $_SESSION['acount']['name'] ){

                $orig = 1;

                $errors['name'] = 'Новое имя должно отличаться от старого';

        } else 

            // Обход массива $name, с целью убедиться в уникальности имени
            foreach ($autho as &$name) {

                if( $name['name'] == $data['name'] ){

                    $orig = 1;

                    $errors['name'] = 'Такое имя уже есть!';

                } 

        } 

        // Проверка, ввёл ли пользоватеь новое имя и если да то корректно ли оно
        if( $orig == 0 ){
            
            // Объявление переменных для SQL запроса
            $name = $data['name'];
            $id = $_SESSION['acount']['id'];
            
            // Объявление SQL переменной для SQL запроса к БД
            $sql = "UPDATE account SET name = '$name' WHERE id = '$id' ";

            // SQL запрос к БД, перезапись имени пользователя
            mysqli_query($connection, $sql) or die(mysqli_error($connection));
           
            // Обновление данных об имени пользователя в ссесии
            $_SESSION['acount']['name'] = $data['name'];
        }


        // Проверка был ли загружен новый аватар
        if( $file != 2 ){

            // Проверка нет ли ошибок при обновлении аватарки
            if( !isset($errors['files']) ){

                // Вывод оповещения об обновлении аватара
                $res['files']['status'] = 0;
                $res['files']['messeg'] = " Аватар обновлён!";
                
            } else {

                // Вывод ошибок сохранения файла
                $res['files']['status'] = 1;
                $res['files']['messeg'] = $errors['files'][0];
            
            }
        } else {
            $res['files']['status'] = 2;
            $res['files']['messeg'] = "";    
        }


        // Проверка написано ли новое имя
        if($orig != 2){

            // Проверка отсуцтвия ошибок при обновлении имени
            if( !isset($errors['name'])){

                // Оповещение об успешном обновлении имени
                $res['name']['status'] = 0;
                $res['name']['messeg'] = " Аватар обновлён!";

            } else {

                // Вывод ошибок при обновлении имени
                $res['name']['status'] = 1;
                $res['name']['messeg'] = $errors['name'];
                
            } 
        }  else {
            $res['name']['status'] = 2;
            $res['name']['messeg'] = "";    
        }


///////////////////////////////////////////////////////////////////////////////////////////
    } elseif($data['name-form'] == 'my-page__creature-article'){

        // Проверка выбрана ли категория статьи
        if( trim( $data['categ']) == 'none' ){

            // Запись что не выбранна категория 
            $errors[] = 'Выберите категорию';
        }

        // Проверка на наличие названия 
        if( trim( $data['title']) == '' ){

            // Запись что не введено название
            $errors[] = 'Введите название!';
        }

        // Проверка что название длиннее 40 символов
        if(  mb_strlen( $data['title']) > 30 ){

            // Запись что название длиннее 40 символов
            $errors[] = 'Название длинее  40 символов';
        }

        // Проверки что есть добавленный файл и что он расширения png и что он меньше 2 мегабайт
        // Иначе запись о не выполнении одного из условий
        if ( $_FILES['file_picture']['name'] == '' ) {
            $errors[] = 'Добавте картинку!';
        } else    
            if ( $_FILES['file_picture']['type'] !=  "image/png" ) {
            $errors[] = 'Расщирение файла не png';
        } else 
            if ( $_FILES['file_picture']['size'] > 5242880 ) {
                $errors[] = 'Размер файла больше 2 мегабайт';
        } 

        // Проверка на наличие текста 
        if( trim( $data['text']) == '' ){

            // Запись о отсуцтвии текста
            $errors[] = 'Введите текст!';
        }

        // Проверка длинее ли текст 1000 символов
        if(  mb_strlen( $data['text']) > 1000 ){

            // Запись о том что текст длинее 1000 символов
            $errors[] = 'Текст длинее 1000 символов';
        }

        // Проверка на отсуцтвие ошибок
        if( empty($errors) ){
            
            $pict = 0;

            // Проверка какие индексы картинок существуют
            // И присвоение новой картинке индекса, самая большая +1
            foreach ($arti as &$pic) {
                if( $pic['image'] > $pict ){
                    
                    $pict = $pic['image'];
                }            
            }
            $pict++;

            // Задание переменных для SQL запроса
            $categoris_id = $data['categ'];
            $title = $data['title'];
            $picture = $pict;
            $text = $data['text'];
            $author = $_SESSION['acount']['id'];
           
            // Сохранение картинки на "Сервер" 
            move_uploaded_file( $_FILES['file_picture']['tmp_name'], 
            'D:\PROGRAM\Vork\locHost\OSPanel\domains\blog\img\s'.$picture.'.png');

            // Создание SQL запроса к БД и создание новой записи со статьёй 
            $sql = "INSERT INTO article (categoris_id, title, image, text, author) VALUES (' $categoris_id', ' $title', '$picture', '$text', '$author')";
            
            // Проверка удачный ли SQL запрос,
            if (mysqli_query($connection, $sql)) {

                // Вывод сообщения об удачном создании статьи
                $res['creature-article']['status'] = 0;
                $res['creature-article']['messeg'] = "Статья успешно <br> создана!";
                $res['redirection']['url'] = "blog_main.php?page=my-page";

            
            } else {

                // Вывод ошибки SQL запроса 
                $res['creature-article']['status'] = 1;
                //$res['creature-article']['messeg'] = ''. "Error: " . $sql . "<br>" . mysqli_error($connection) .'';
                $res['creature-article']['messeg'] = "Ошибка соединения <br> попробуйте повторить позже";
            }

        } else {

            // Вывод сообщения с ошибкой при заполнении формы
            $res['creature-article']['status'] = 1;
            $res['creature-article']['messeg'] = $errors[0];
        }


///////////////////////////////////////////////////////////////////////////////////////////
    } elseif($data['name-form'] == 'my-page__editing-article'){

        // Проверка выбрана ли категория статьи
        if( trim( $data['categoris_id']) == 'none' ){

            // Запись что не выбранна категория 
            $errors[] = 'Выберите категорию';
        }

        // Проверка на наличие названия 
        if( trim( $data['title']) == '' ){

            // Запись что не введено название
            $errors[] = 'Введите название!';
        }

        // Проверка что название длиннее 40 символов
        if(  mb_strlen( $data['title']) > 30 ){

            // Запись что название длиннее 40 символов
            $errors[] = 'Название длинее  40 символов';
        }

        // Проверки что есть добавленный файл и что он расширения png и что он меньше 2 мегабайт
        // Иначе запись о не выполнении одного из условий? если файла нет то просто остаёться старый
        if ( $_FILES['file_picture']['name'] == '' ) {
            
        } else    
            if ( $_FILES['file_picture']['type'] !=  "image/png" ) {
            $errors[] = 'Расщирение файла не png';
        } else 
            if ( $_FILES['file_picture']['size'] > 5242880 ) {
                $errors[] = 'Размер файла больше 2 мегабайт';
        } 

        // Проверка на наличие текста 
        if( trim( $data['text']) == '' ){

            // Запись о отсуцтвии текста
            $errors[] = 'Введите текст!';
        }

        // Проверка длинее ли текст 1000 символов
        if(  mb_strlen( $data['text']) > 1000 ){

            // Запись о том что текст длинее 1000 символов
            $errors[] = 'Текст длинее 1000 символов';
        }

        // Проверка на отсуцтвие ошибок
        if( empty($errors) ){

            // Задание переменных для SQL запроса
            $id = $data['id'];
            $categoris_id = $data['categoris_id'];
            $title = $data['title'];
            $text = $data['text'];
            $picture = $data['image'];

            // Сохранение картинки на "Сервер" 
            move_uploaded_file( $_FILES['file_picture']['tmp_name'], 
            'D:\PROGRAM\Vork\locHost\OSPanel\domains\blog\img\s'.$picture.'.png');

            // Объявление SQL переменной для SQL запроса к БД
            $sql = "UPDATE article SET categoris_id = '$categoris_id', title = '$title', text = '$text'  WHERE id = '$id' ";

            // Проверка удачный ли SQL запрос,
            if (mysqli_query($connection, $sql)) {

                // Вывод сообщения об удачном создании статьи
                $res['editing-article']['status'] = 0;
                $res['editing-article']['messeg'] = " Статья успешно <br> обновлена!";
                $res['redirection']['url'] = "blog_main.php?page=my-page";
               
            
            } else {

                // Вывод ошибки SQL если запрос не совершился
                $res['editing-article']['status'] = 1;
                //$res['editing-article']['messeg'] = ''. "Error: " . $sql . "<br>" . mysqli_error($connection) .'';
                $res['editing-article']['messeg'] = "Ошибка соединения <br> попробуйте повторить позже";
            }

        } else 
        {

            // Вывод сообщения с ошибкой при заполнении формы
            $res['editing-article']['status'] = 1;
            $res['editing-article']['messeg'] = $errors[0];

        }


///////////////////////////////////////////////////////////////////////////////////////////
    } elseif($data['name-form'] == 'my-page__exit'){

         // Удаление данных куки
         $params = session_get_cookie_params();
         setcookie(session_name(), '', time() - 42000,
         $params["path"], $params["domain"],
         $params["secure"], $params["httponly"]
     );
 
         // Удаление куки и сессии
         unset($_SESSION['acount']); 
         unset($_COOKIE['acount']); 
         setcookie('acount', null, -1, '/'); 
 
         // Перенаправление на главную страницу

         $res['my-page__exit']['status'] = 2;
         $res['my-page__exit']['messeg'] = "";
         $res['redirection']['url'] = "blog_main.php";

///////////////////////////////////////////////////////////////////////////////////////////
    } elseif($data['name-form'] == 'likes'){

        // Проверка существования аккаунта 
        if( !isset($_SESSION['acount']) ){

            // Добавление записи о том что пользователь не в аккаунте
            $errors[] = 'Войдите в аккаунт';
        } else {

            // Проверка есть ли строка с лайком данным пользователем данной статьи
            foreach ( (array) $lik_e as &$lik) 
            {
                       
                if( $lik['id_user'] == $_SESSION['acount']['id']) 
                {
                    if( $lik['id_article'] == $data['id_article'])  
                    {
                        $my_like['id'] = $lik['id'];
                        $my_like['exist'] = '1' ;
                    }
                            
                }
            }
        }

        
        
        // print_r($my_like['id'] );
        // print_r($my_like['exist'] );

        // Проверка наличия ошибок
        if( empty($errors) ){

            if (strlen($my_like['exist'] . '1') == 1)
            {

                // Объявление переменных для SQL запроса и создания записи в БД
                $author_id = $_SESSION['acount']['id'];
                $article_id = $data['id_article'];

                // SQL запрос в БД и создание записи лайка
                $sql = "INSERT INTO lik_e (id_user, id_article ) VALUES (' $author_id ', ' $article_id ' )";
                $mysqli_query = mysqli_query($connection, $sql);   

                // Перенаправление для сброса пост запроса 
                //echo '<script> window.setTimeout(function() { window.location = "blog_main.php?page=article&id='.$_GET['id'].'&title='.$_GET['title'].'"; }, 2000) </script>';
                $res['redirection']['url'] = $data['url'];
            } 
            else 
            {
                $id = $my_like['id'];  
                
                $sql = "DELETE FROM `lik_e` WHERE `id` = '$id'";
                $mysqli_query = mysqli_query($connection, $sql);   

                // Перенаправление для сброса пост запроса 
                //echo '<script> window.setTimeout(function() { window.location = "blog_main.php?page=article&id='.$_GET['id'].'&title='.$_GET['title'].'"; }, 2000) </script>';
                $res['redirection']['url'] = $data['url'];
            }
            
            
        }
        else 
        {
            // Вывод сообщения об ошибке при заполнении формы
            $res['likes']['status'] = 1;
            $res['likes']['messeg'] = $errors[0];
        }
        
        


///////////////////////////////////////////////////////////////////////////////////////////
    } elseif($data['name-form'] == 'comment'){

        // Проверка наличия текста
        if( trim( $_POST['text']) == '' ){

            // Добавление записи об отсуцтвие текста если его нет
            $errors[] = 'Введите текст!';
        }

        //Проверка что текст короче 150 символов
        if(  mb_strlen( $_POST['text']) > 150 ){

            // Добавление записи о том что текст слишком длинный
            $errors[] = 'Текст длинее 150 символов';
        }


        // Проверка наличия ошибок
        if( empty($errors) ){
            
            // Объявление переменных для SQL запроса и создания записи в БД
            $author_id = $_SESSION['acount']['id'];
            $article_id = $_GET['id'];
            $text = $_POST['text'];

            // SQL запрос в БД и создание записи
            $sql = "INSERT INTO coments (author_id, article_id, text) VALUES (' $author_id ', ' $article_id ', ' $text ' )";
            $mysqli_query = mysqli_query($connection, $sql);
           
        }

        // Проверка наличия ошибок если форма отправленна
        if( empty($errors) ){
                            
            // Проверка успешного создания в базе БД, если нет ошибок в форме
            if ( $mysqli_query == true ) {

                // Сообщение об успешном создании комментария
                echo '
                <div class="flex cuc-coments">
                    Комментарий <br> успешно создан!
                </div>
                ';
                $res['comment']['status'] = 0;
                $res['comment']['messeg'] = "Комментарий <br> успешно создан!";

            } else {

                // Вывод ошибки если форма правильная, но не произошла запись в БД
                $res['editing-article']['status'] = 1;
                //$res['editing-article']['messeg'] = ''. "Error: " . $sql . "<br>" . mysqli_error($connection) .'';
                $res['editing-article']['messeg'] = "Ошибка соединения <br> попробуйте повторить позже";
            }

        } else {

            // Вывод сообщения об ошибке при заполнении формы
            $res['comment']['status'] = 1;
            $res['comment']['messeg'] = $errors[0];
            
        }


///////////////////////////////////////////////////////////////////////////////////////////
    }
    
    if( empty($res) ){

    } else {
        echo json_encode($res);    
    }
                    
?>