<?php

/**
 * @property Type_User_Session $session
 */
class User {

	protected $_allow_magic = [
		'session',
	];

	public $user_id = 0;
	public $role;
	public $full_name;
	public $organisation_id;

	function __construct($user_id = 0) {

        if ($user_id < 1) {

			$this->session->start();
			$this->user_id = $this->session->user_id;

			// получаем пользователя из базы
			$row = Type_User_Main::getByUserId($this->user_id);
			if (isset($row['role'])) {

				$this->role = $row['role'];
				$this->full_name = $row['full_name'];
				$this->organisation_id = $row['organisation_id'];
			}

			return $this;
		}

		$this->user_id             = $user_id;
		$this->session->session_id = sha1(uniqid(microtime(), true));
		$this->session->user_agent = getUa();
		$this->session->ua_hash    = getUaHash();

		return $this;
	}

	public static function init($user_id = 0) {

		if (!isset($GLOBALS[__CLASS__])) {
			$GLOBALS[__CLASS__] = [];
		}

		// проверяем был ли создан раннее
		if (isset($GLOBALS[__CLASS__][$user_id])) {

			// если создан вернуть ранее созданный объект
			return $GLOBALS[__CLASS__][$user_id];
		}

		// создаем объект
		$GLOBALS[__CLASS__][$user_id] = new self($user_id);

		// возвращаем объекст
		return $GLOBALS[__CLASS__][$user_id];
	}

	function __get($name) {

		$class = 'Type_User_' . $name;

		$this->$name = new $class();

		return $this->$name;
	}

}