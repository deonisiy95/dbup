<?php

class Type_User_Main {

    // получаем из базы информацию пользователя по имени
    public static function getByUserName($username) {

        $row = db::query(sprintf('SELECT * FROM `user` WHERE `username` = \'%s\'', $username));
        return $row;
    }

    // получаем из базы информацию пользователя по id
    public static function getByUserId($user_id) {

        $row = db::query(sprintf('SELECT * FROM `user` WHERE `user_id` = %g', $user_id));
        return $row;
    }

    // получаем из базы полную информацию
    public static function getFullInfo($user_id) {

        $row = db::query(sprintf('SELECT * FROM `user` as user, `organisation` as organisation  WHERE `user_id` = %g and organisation.organisation_id = user.organisation_id', $user_id));
        return $row;
    }

    // получаем из базы информацию пользователя по роли
    public static function getByUserRole($role) {

        //$row = sharding::pdo('telegram_data')->getAll("SELECT * FROM `?p` WHERE `?p` = ?i LIMIT ?i", 'user', 'role', $role, 100);
        //return $row;
    }

    // устанавливает роль пользователю
    public static function setRole($username, $role) {

        //$query = 'UPDATE `?p` SET ?u WHERE `?p` = ?s LIMIT ?i';
        //sharding::pdo('telegram_data')->update($query, 'user', ['role' => $role], 'username', $username, 1);
    }

    public static function create($username, $password, $organization_id) {

        $query_str = sprintf("INSERT INTO `user` (username, auth_hash, organization_id, created_at) VALUES ('%s', '%s', %g, %g)",
            $username,
            Type_User_Auth::genAuthHash($password),
            $organization_id,
            time()
        );

        return db::query($query_str);
    }
}