<?php

// Контроллер для авторизации пользователя и инициализации сессии
class ApiV1_Auth extends ApiV1_Default {

    // Поддерживаемые методы. Регистр не имеет значение */
    protected $allow_methods = [
        'tryLogin',
        'doLogout',
        'singUp',
        'doResend',
    ];

    // Пытаемся авторизовать пользователя
    protected function tryLogin() {

        $username = $this->post('?s', 'username');
        $password = $this->post('?s', 'password');

        if (!isset($username) || !isset($password)) {

            return $this->error(10);
        }

        // проверка на то что пользователь авторизован
        if ($this->user->user_id > 0) {

            return $this->ok($this->user->user_id);
        }

        // TODO добавить блокировку на логин

        // получаем информацию о пользователе по его user_name
        $row = Type_User_Main::getByUserName($username);

        // если нет записи
        if (!isset($row['user_id'])) {
            return $this->error(11);
        }

        // проверяем переданный пароль
        if (Type_User_Auth::checkPassword(Type_User_Auth::genAuthHash($password), $row['auth_hash']) == false) {

            // инкрементим блокировку auth_try_login

            // возвращаем ошибку
            return $this->error(12);
        }

        Type_User_Auth::doLogin($row['user_id'], $this->user->session->session_id, $this->user->session->ip_address,
            $this->user->session->ua_hash);

        return $this->ok([
                'need_confirm' => 0,
            ]
        );
    }

    // метод регистрации пользователя
    protected function singUp() {

        // получаем переданные данные
        $username = $this->post('?s', 'username'); // имя пользователя
        $password = $this->post('?s', 'password'); // пароль
        $organization_id = $this->post('?i', 'organization_id'); // идентификатор организации
        //$role = $this->post('?i', 'role'); // роль

        // если пользователь уже зарегистрирован
        if (isset(Type_User_Main::getByUserName($username)['username'])) {

            return $this->error('User is exist');
        }

        // создаем нового пользователя
        Type_User_Main::create($username, $password, $organization_id);

        return $this->ok();

    }

    // Завершаем сессию пользователя и разлогиниваемся
    protected function doLogout() {

        // удаляем запись сессии из бд
        Type_User_Auth::doLogout($this->user->session->session_id);

        // очищаем куки
        setcookie($this->user->session->session_id, -1);

        return $this->ok();


    }

    // ключ по которому сохранчем в кеш
    protected function _getKey() {

        return self::class;
    }

}