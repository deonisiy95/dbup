<?php

class Type_User_Auth {

    // функция производит авторизацию пользователя
    public static function doLogin($user_id, $session_id, $ip_address, $ua_hash) {

        db::query(sprintf('INSERT INTO `session` (user_id, session_id, created_at, ip_address, ua_hash) VALUES (%g, \'%s\', %g, \'%s\', \'%s\')',
            $user_id,$session_id,time(),$ip_address,$ua_hash));
    }

    // функция разлогинивает сессию
    public static function doLogout($session_id) {

        db::query(sprintf('DELETE FROM `%s` WHERE `%s` = \'%s\'', 'session','session_id' , $session_id));
    }

    //
    public static function checkPassword($password_hash, $auth_hash) {

        return $password_hash == $auth_hash;
    }

    //
    public static function genAuthHash($password) {
        return sha1($password);
    }

    // генерируем проверечный код
    public static function genCode() {

        return sprintf("%06d", mt_rand(100000, 999999));
    }
}