<main class="main_content">
    <!-- HTML код формы регистрации -->
    <section class="flex regi">
        <h2 class="title regi-title">
            Регистрация
        </h2>
        <form class="flex form regi__form" action="/blog_main.php?page=signup" method="post" enctype="multipart/form-data">
            <div class="flex regi-form__name">
                <h3 class="regi-form-name__title">
                    Ваше имя
                </h3>
                <input class="input regi-form-name__inp" type="text" name="name" value="<?php echo
                $data['name']; ?>">
            </div>
            <div class="flex regi-form__file">
                <h3 class="regi-form-file__title">
                    Ваша аватарка
                </h3>
                <input class="input regi-form-file__inp" type="file" name="file_avatar" >
            </div>
            <div class="flex regi-form__pas-1">
                <h3 class="regi-form-pas-1__title">
                    Ваш пароль
                </h3>
                <input class="input regi-form-pas-1__inp" type="password" name="password_1"  value="<?php echo
                $data['password_1']; ?>">
            </div>
            <div class="flex regi-form__pas-2">
                <h3 class="regi-form-pas-2__title">
                    Введите ваш пароль ещё раз
                </h3>
                <input class="input regi-form-pas-2__inp" type="password" name="password_2"  value="<?php echo
                $data['password_2']; ?>">
            </div>
            <div class="flex regi-form__avto-autho">
                <h3 class="regi-form-avto-autho__title">
                    Автоматически авторизоваться?
                </h3>
                <input type="checkbox" name="avto_autho" value="Yes" />
            </div>
            <input type="hidden" name="name-form" value="signup">
            <div class="flex btn-reset regi-form__com">
                <button class="regi-form-com__btn" name="du_signup" tyep="submit" >
                    Зарегестрироваться
                </button>
            </div>
        </form>
    </section>
</main>