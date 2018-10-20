<?php

// класс который наследует любой Api класс
abstract class ApiV1_Default {

    /** @var User */
	protected $user          = null;
	protected $method        = null;
	protected $post_data     = [];
	protected $allow_methods = [];      // разрешенные методы

	//
	public function work($action, $post_data, User $user) {

		unset($this->user);

		//
		$this->post_data = $post_data;
		$this->user      = $user;
		//
		foreach ($this->allow_methods as $value) {
			if (strtolower($action) == strtolower($value)) {
				return $this->$action();
			}
		}

		return ['error' => 1234];
	}

	//
	protected function error($code, array $etc = []) {

		if (is_array($code)) {
			$etc = array_merge($code, $etc);
		} else {
			$etc['message'] = $code;
		}

		return [
			'status'   => 'error',
			'response' => $etc,
		];
	}

	//
	protected function ok($message = []) {

		$output = [
			'status'   => 'ok',
			'response' => [],
		];

		if (is_array($message)) {
			$output['response'] = array_merge($output['response'], $message);
		} else {
			$output['response'] = [
				'message' => $message,
			];
		}

		return $output;
	}

	//  возвращает параметр который пришел от пользователя
	protected function post($type, $key, $default = null) {

		$value = null;
		switch ($type) {
			case '?s':
				// строка
				$value = isset($this->post_data[$key]) && !is_array($this->post_data[$key]) ? formatString($this->post_data[$key]) : $default;
				break;
			case '?f':
				// двоичное значение
				$value = isset($this->post_data[$key]) && !is_array($this->post_data[$key]) ? formatFloat($this->post_data[$key]) : $default;
				break;
			case '?i':
				// цифры
				$value = isset($this->post_data[$key]) && !is_array($this->post_data[$key]) ? formatInt($this->post_data[$key]) : $default;
				break;
			case '?a':
				// массив
				if (isset($this->post_data[$key]) && is_array($this->post_data[$key])) {
					$value = $this->post_data[$key];

					// рекурсивно применяем функцию formatString ко всем вложкенным элементам
					array_walk_recursive($value, function(&$item) {

						$item = formatString($item);
					});
				} else {
					$value = $default;
				}
				break;
			default:
				break;
		}

		return $value;
	}

	// magic function для доступа к Юзеру
	public function __get($name) {

		if ($name == 'user' && (!isset($this->user) || $this->user == null)) {
			return User::Init($this->user_id);
		}
	}
}