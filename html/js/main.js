// AJAX - отправка запроса на сервер и обработка ответа

const AJAX_form = () => {

    $(document).ready(function () {


        // Создание нового элемента div для отображения в нём сообщения с сервера
        var mes_cuc = document.createElement('div');
        mes_cuc.className += ' flex cuc-regi messeg';

        var mes_eror = document.createElement('div');
        mes_eror.className += ' flex errors messeg';

        // Тригер нажатия на кнопку формы
        $(".form").on("submit", function (event) {

            // Запрет на обновление страницы
            event.preventDefault();


            // Отправка пост запроса на сервер
            fetch('http://localhost:91/blog_form.php', {
                //mode: 'no-cors',
                method: 'POST',
                // body: new FormData(form)
                body: new FormData(event.currentTarget)
            })
                .then((response) => response.json())

                .then((data) => {
                    // Если сообщение уже есть, удалить текущее
                    if (document.querySelector(".messeg") != null) {
                        document.querySelector(".messeg").remove();
                    }

                    // Выделение элемента main, для последующей вставки в его начало сообщений
                    const main = document.querySelector(".main_content");

                    Object.keys(data).forEach(el => {

                        if (el == 'redirection') {
                            loadPage(data[el]['url']);
                        }

                        if (el == 'login' || el == 'signup' || el == 'my-page__exit') {
                            loadHeader();
                        }

                        if (el == 'comment') {
                            const main = document.querySelector(".article__my-comments");
                        }

                        if (data[el]['status'] == 0) {

                            // Вставка полученого от сервера сообщения в созданный div
                            mes_cuc.innerHTML = data[el]['messeg'];



                            // Вставка div в начало main
                            main.before(mes_cuc);

                        } else if (data[el]['status'] == 1) {

                            // Вставка полученого от сервера сообщения в созданный div
                            mes_eror.innerHTML = data[el]['messeg'];

                            // Вставка div в начало main
                            main.before(mes_eror);
                        }
                    });


                    // Если есть сообщение об успешной авторизации, то перенаправление на страницу пользователя
                    if (document.querySelector(".cuc-regi") != null) {
                        // window.setTimeout(function() { window.location = "blog_main.php?page=my-page"; }, 2000);
                        loadPage("blog_main.php?page=my-page");
                    }

                    if (document.querySelector(".messeg") != null) {
                        setTimeout(() => {
                            document.querySelector(".messeg").remove();
                        }, 4000)
                    }

                });

        })
    });

}


// AJAX обновление контента по клику на любую ссылку с классом link
$('body').delegate('.link', 'click', function (e) {

    // Отменить выполнение функционала по умолчанию, то есть не переходить на страницу
    e.preventDefault();

    // Создание константы с значением URL ссылки на которую нажали
    const url = e.currentTarget.getAttribute('href');

    // Вызов функции загрузки страницы, с полученным URL
    loadPage(url);


});



AJAX_form();

// Поиск и сохранение тегов с классом main_content
const contentMAIN = document.querySelector('.main_content');

// Поиск и сохранение тегов с классом header
const contentHEADER = document.querySelector('.header');

// Функция для корректного выполнения скриптов на новой странице
const loadScripts = (url) => {
    // Все скрипты страницы нужно выполнять здесь


}


// Функциия для загрузки новой страницы, принимающая на вход URL новой страницы
const loadPage = (url) => {

    // Отправка fetch запроса на url новой страницы
    fetch(url)
        // После получения ответа привести его в текстовый вид
        .then(response => response.text())
        // После обработки ответа начало обновления контента на странице
        .then(html => {
            // Создание DOM парсера 
            const parser = new DOMParser();
            // Конвертация ответа в DOM структуру с помощью парсера
            const doc = parser.parseFromString(html, 'text/html');
            // Выделение из DOM структуры нужного нам участка внутри тега с классом main_content
            const newContent = doc.querySelector('.main_content').innerHTML;

            // Замена текущего контента внутри тега с классом main_content на новый
            contentMAIN.innerHTML = newContent;

            // Вызов задержки на 0.5 секунд
            setTimeout(() => {
                // Создание в истории записи о переходе на новый url
                history.pushState({}, '', url);
            }, 500)
        })

        // После вызов функции выполняющей скрипты на странице
        .then(() => {
            AJAX_form();
            loadScripts(url);
        })
};

const loadHeader = () => {

    // Отправка fetch запроса на url новой страницы
    fetch('../blog_header.php')
        // После получения ответа привести его в текстовый вид
        .then(response => response.text())
        // После обработки ответа начало обновления контента на странице
        .then(html => {
            // Создание DOM парсера 
            const parser = new DOMParser();
            // Конвертация ответа в DOM структуру с помощью парсера
            const doc = parser.parseFromString(html, 'text/html');
            // Выделение из DOM структуры нужного нам участка внутри тега с классом header
            const newContent = doc.querySelector('.header').innerHTML;

            // Замена текущего контента внутри тега с классом header на новый
            contentHEADER.innerHTML = newContent;
        })
        .then(() => {
            AJAX_form();

        })

};











