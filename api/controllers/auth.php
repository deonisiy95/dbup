<?php

// Контроллер для авторизации пользователя и инициализации сессии
class ApiV1_Auth extends ApiV1_Default {

    // Поддерживаемые методы. Регистр не имеет значение */
    protected $allow_methods = [
        'tryLogin',
        'doLogout',
        'tryConfirm',
        'doResend',
    ];

    // Пытаемся авторизовать пользователя
    protected function tryLogin() {

        $username = $this->post('?s', 'username');
        //$password = $this->post('?s', 'password');;

        //return $this->ok(['username' => 'Hello,' . $username]);
        return $this->error(123);
    }

    // отправить код для двухфакторной авторизации
    protected function tryConfirm() {


    }

    // переотправить код
    protected function doResend($code) {

    }

    // Завершаем сессию пользователя и разлогиниваемся
    protected function doLogout() {



    }

    // ключ по которому сохранчем в кеш
    protected function _getKey() {

        return self::class;
    }

}