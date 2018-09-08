<?php
/**
 * Класс для работы с бахзой данных
 */

class db {

    // дескриптор описывающий подключение с базой данных
    protected static $connection = false;

    // устанавливаем соединение с базой данных
    public static function connect() {

        // подключаемся к серверу бд
        self::$connection = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);

        // если соединиться не удалось
        if (!self::$connection) {

            return false;
        }
    }

    // закрываем соединение c бд
    public static function close() {

        // закрываем соединение
        self::$connection->close();
    }

    // функция выполняющая запрос
    public static function query($query) {

        // отправляем запрос
        $result = self::$connection->query($query);

        console(mysqli_fetch_assoc($result));

    }
}