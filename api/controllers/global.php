<?php

Class ApiV1_Global extends ApiV1_Default {

    // Поддерживаемые методы. Регистр не имеет значение */
    protected $allow_methods = [
        'doStart', 'isAdmin'
    ];

    // говорим пользователю авторизован он или нет и возвращаем стартовую информацию
    protected function doStart() {

        return $this->ok([
            'is_logged' => $this->user->user_id > 0 ? 1 : 0,
        ]);
    }

    // говорим пользователю админ ли он
    protected function isAdmin(){

        return $this->ok([
            'is_admin' => $this->user->role == 1 ? 1 : 0,
        ]);
    }
}