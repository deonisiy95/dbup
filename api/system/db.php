<?php
/**
 * Класс для работы с базой данных
 */

class db {

    // дескриптор описывающий подключение с базой данных
    public static $connection = false;

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

        if ( self::$connection != false) {

            // отправляем запрос
            $result = self::$connection->query($query);

            console($result->num_rows);

            $result_array = [];

            if ( isset($result->num_rows) && $result->num_rows > 0) {

                if ($result->num_rows >= 2) {

                    while ($row = $result->fetch_assoc()) {

                        $result_array[] = $row;
                    }

                    return $result_array;
                } else {

                    return mysqli_fetch_assoc($result);
                }
            }

            return [];
        }


    }
}