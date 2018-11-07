<?php

class ApiV1_Handler {

	// поддерживаемые методы (при создании новой группы заносятся в ручную)
	protected static $allow_controllers = [
		'global',
		'auth',
		'logs',
		'profile',
		'assessment',
	];

	protected static $allowed_not_authorized = [
		'global',
		'auth',
	];

	// единая точка входа в API
	// В качестве параметров принимает:
	//	@$api_method 	название метода вида test.code401
	//	@$post_data 	параметры post запроса которые будут использоваться внутри контролеров
	//	@user_id	для отладки и тестов. действуйет только в режиме cli
	public static function doStart($api_method, $post_data, $user_id = 0) {

        // подключаемся к БД
        db::connect();

        //
		$user = User::init($user_id);

		// получаем ответ
		$output = self::_getResponse($api_method, $post_data, $user);

		// отдаем финальный ответ
		return $output;
	}


	// ---------------------------------------------------
	// PROTECTED UTILS METHODS
	// ---------------------------------------------------

	// роутим юзера в нужную функцию
	protected static function _getResponse($api_method, $post_data, $user) {

		$method = explode('/', strtolower($api_method));

		// если метода нет в разрешенных группах методов
        if (!in_array($method[0], self::$allow_controllers)) {

            return [
                "status" => "error",
                "response" => [
                    "message" => 1
                ]
            ];
        }

		// возвращаем работу метод
		$class = "ApiV1_{$method[0]}";
		return (new $class())->work($method[1], $post_data, $user);
	}

}

