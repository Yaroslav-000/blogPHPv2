<main class="main_content"> 
    <!-- HTML код вормы авторизации -->
    <section class="flex log">
        <h2 class="title log-title">
            Авторизация
        </h2>
        <form class="flex form log__form" action="/blog_main.php?page=login" method="post">
            <div class="flex log-form__name">
                <h3 class="log-form-name__title">
                    Ваше имя
                </h3>
                <input class="input log-form-name__inp" type="text" name="name" value="<?php
                 (isset($data['name'])) ? $data['name'] : '' ; ?>">
            </div>
            <div class="flex log-form__pas">
            <h3 class="log-form-pas__title">
                    Ваш пароль
                </h3>
                <input class="input log-form-pas__inp" type="password" name="password"  value="<?php
               (isset($data['password'])) ?  $data['password'] :  '';   ?>">
            </div>
            <div class="flex log-form__remember">
                <h3 class="log-form-remember__title">
                    Запомнить меня
                </h3>
                <input type="checkbox" name="remember" value="Yes">
                <input type="hidden" name="name-form" value="login">
            </div>
            <div class="flex btn-reset log-form__com">
                <button class="log-form-com__btn" name="du_login" type="submit" >
                    Авторизоваться
                </button>
            </div>
        </form>
    </section>
</main>