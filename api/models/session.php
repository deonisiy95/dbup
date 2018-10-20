<?php

class Type_User_Session {

    const SESSION_KEY         = 'session_id'; // ключ по которому будем сохранять cookie
    const SESSION_EXPIRE_TIME = DAY1 * 365; // время жизни cookie

    public $session_id;     // идентификатор сессии
    public $user_id = 0;    // идентификатор пользователя
    public $ip_address;     // ip адрес с которого осуществляется запрос,

    public $user_agent;     // название клиента с которого осуществляется запрос
    public $ua_hash;        // хэш от user_agent

    public function start() {

        // получаем ip
        $this->ip_address = getIp();
        // получаем user_agent
        $this->user_agent = getUa();
        // получаем hash от user_agent
        $this->ua_hash = getUaHash();

        //проверяем установленна ли cookie
        if (!isset($_COOKIE[self::SESSION_KEY])) {

            // устанавливаем сессию пользователю
            self::_init();
            return;
        }

        $this->session_id = $_COOKIE[self::SESSION_KEY];

        // получаем информацию по session id из базы
        $row = self::_getInfo($this->session_id);

        // если запись из бызы не вернулась, значит пользователь не авторизован
        if (!isset($row['session_id'])) {
            return;
        }

        // проверяем хэш user agent
        // если не совпадают то устанавливаем сессию и return
        if ($this->ua_hash != $row['ua_hash']) {
            self::_init();
            return;
        }

        // сохраняем в свойство user_id что вернулась с базы
        $this->user_id = $row['user_id'];
    }

    // возвращает уникальный ключ для записи в кеш
    protected function _getKey($key){

        return $this->user_id . '_' . $this->session_id . '_' . $key;
    }

    // функция устанавливает сессию для пользователя
    protected function _init() {

        // генерируем идентификатор сессии
        $session_id = sha1(uniqid(microtime(), true));

        // сохраняем идентификатор
        $_COOKIE[self::SESSION_KEY] = $session_id;

        // устанавливаем пользователю cookie
        setcookie(self::SESSION_KEY, $session_id, self::SESSION_EXPIRE_TIME + time(), '/');
        setcookie("some_cookie", "123", self::SESSION_EXPIRE_TIME + time(), '/');
    }

    // получаем информацию по session id из базы
    protected static function _getInfo($session_id) {

        $query  = sprintf('SELECT * FROM %s WHERE %s = \'%s\'', 'session', 'session_id', $session_id);
        $output = db::query($query);
        return $output;
    }
}