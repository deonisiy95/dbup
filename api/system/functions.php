<?php

// ----
// Содержаться вспомогательные функции системы
// ----

//
function get($field, $default = null) {
	return isset($_GET[$field]) ? $_GET[$field] : $default;
}

//
function post($field, $default = null) {
	return isset($_POST[$field]) ? $_POST[$field] : $default;
}

//
function request($field, $default = null) {
	return isset($_REQUEST[$field]) ? $_REQUEST[$field] : $default;
}

//
function cookie($field, $default = null) {
	return isset($_COOKIE[$field]) ? $_COOKIE[$field] : $default;
}

// возвращает число ограниченное min & max
function limit($value, $min = null, $max = null) {
	$value = intval($value);
	if ($min !== null && $value < $min)
		$value = $min;
	if ($max !== null && $value > $max)
		$value = $max;

	return $value;
}

// возвращает айпи адрес того, кто выполняет текущий скрипт
function getIp() {
//	if (is_cron()) {
//		return '127.1.1.1';
//	}

	if (isset($_SERVER['HTTP_X_REAL_IP']))
		return $_SERVER['HTTP_X_REAL_IP'];

	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];

	return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.1.1.1';
}

// true - если Ajax запрос
function isAjax() {
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest" || (isset($GLOBALS['ajax']) && $GLOBALS['ajax'] == true));
}

// true - если крон
function isCron() {
	if (!defined('IS_CRON') || IS_CRON == false) {
		return false;
	}

	return true;
}

// true - если unit test
function isUtest() {
	if (!defined('IS_UTEST') || IS_UTEST == false) {
		return false;
	}

	return true;
}

// true - если работа из консоли
function isCLi() {
	if (php_sapi_name() == "cli") {
		return true;
	} else {
		false;
	}
}

// возвращает user agent пользователя
function getUa() {

	if (!isset($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == '') {
		$_SERVER['HTTP_USER_AGENT'] = 'robot';
	}

	$user_agent = formatString($_SERVER['HTTP_USER_AGENT']);

	return $user_agent;
}

// возвращает 40 символьную длину
function getUaHash() {
	return sha1(getUa());
}

function debug(... $arr) {

	foreach ($arr as $value) {
		@file_put_contents(PATH_LOGS . 'debug.log', dd($value) . "\n", FILE_APPEND);
	}
}


// --------------------------------------------
// ДЕБАГ И ОТОБРАЖЕНИЕ
// --------------------------------------------

function dd() {
	$out = [];
	$vars = func_get_args();
	foreach ($vars as $v) {
		$out[] = print_r($v, true);
	}

	return "<pre>" . implode(" | ", $out) . "</pre>\n";
}

// заменяет в строке выражения обернутые в {} на элементы массива по ключу
// Пример: format("после замены это {value} значение будет равно одному",['value'=>1]);
function format($txt, $replace) {
	foreach ($replace as $key => $value) {
		//echo dd($key,$value);
		$txt = @str_replace("{" . $key . "}", $value, $txt);
		$txt = @str_replace("{" . strtolower($key) . "}", $value, $txt);
		$txt = @str_replace("{" . strtoupper($key) . "}", $value, $txt);
	}

	return $txt;
}

//
function console() {
	if (!isCli()) {
		return;
	}

	$vars = func_get_args();
	foreach ($vars as $v) {
		if (is_array($v)) {
			$v = dd($v);
		}

		echo "{$v}\n";
	}
}

// --------------------------------------------
// РАБОТА СО ВРЕМЕНЕМ
// --------------------------------------------

//
function weekNum($time = null) {
	if ($time == null) {
		$time = time();
	}

	return limit(intval(date('W', $time)));
}

//
function dayNum($time = null) {
	if ($time == null) {
		$time = time();
	}

	return limit(intval(date('z', $time)));
}

//
function dayStart($time = null) {
	if ($time == null) {
		$time = time();
	}

	return mktime(0, 0, 0, date('m', $time), date('d', $time), date('Y', $time));
}

//
function weekStart($time = null) {
	if ($time == null)
		$time = time();
	$start = (date('w', $time) == 1) ? $time : strtotime('last monday', $time);

	return dayStart($start);
}

//
function monthStart($time = null) {
	if ($time == null) {
		$time = time();
	}

	$start = strtotime(date("Y-m",$time));
	return dayStart($start);
}

//
function hourStart($time = null) {
	if ($time == null) {
		$time = time();
	}

	return mktime(date('H', $time), 0, 0, date('m', $time), date('d', $time), date('Y', $time));
}

//
function fromDayStart() {
	return time() - mktime(0, 0, 0, date('m'), date('d'));
}

//
function tillDayEnd() {
	return mktime(0, 0, 0, date('m'), date('d') + 1) - time();
}


// --------------------------------------------
// КОНФИГИ
// --------------------------------------------

// возвращает знавение конфига
function getConfig($code) {
	global $CONFIG;
	$code = strtoupper($code);
	if (isset($CONFIG[$code])) {
		return $CONFIG[$code];
	}

	$codes = explode('_', $code);
	$file = strtolower($codes[0]);

	loadConfig($file);
	if (!isset($CONFIG[$code])) {
		return [];
	}

	return $CONFIG[$code];
}

// перезаписывает конфиг
function setConfig($code, $data) {
	global $CONFIG;
	$code = strtoupper($code);
	$CONFIG[$code] = $data;
}

// загружаем конфиг из файла конфигов /api/conf
function loadConfig($file) {
	global $CONFIG;
	$path = PATH_API . '/conf/' . $file . '.php';
	if (file_exists($path)) {
		include($path);
	}
}

// --------------------------------------------
// ШИФРОВАНИЕ
// --------------------------------------------

//
function grevalEnCrypt($str) {
	$str = trim($str);
	return base64_encode(bin2hex(mcrypt_encrypt(MCRYPT_TYPE, MCRYPT_KEY, $str, MCRYPT_MODE_CBC, hex2bin(MCRYPT_VI))));
}

//
function grevalDeCrypt($str) {
	return trim(mcrypt_decrypt(MCRYPT_TYPE, MCRYPT_KEY, hex2bin(base64_decode($str)), MCRYPT_MODE_CBC, hex2bin(MCRYPT_VI)));
}

// --------------------------------------------
// JSON
// --------------------------------------------

// alias системной функции, так как здесь возможны модификации
// и важно чтобы все кодирование JSON шло через одну функцию
function toJson($input) {
	return json_encode($input);
}

// раскодировать json
function fromJson($input) {
	$info = json_decode($input, true, 512, JSON_BIGINT_AS_STRING);
	if (!is_array($info)) {
		return array();
	}

	return $info;
}

// --------------------------------------------
// ПРОЧЕЕ
// --------------------------------------------

// устанавливает константу, если такая еще не установлена
function ddefine($key, $value) {
	if (!defined($key)) {
		define($key, $value);
	}
}

// выводит в консоль форматированный массив, чтобы его можно скопировать и вставить в РНР скрипт
function toPhpArray($list) {
	$txt = "array(\n";
	$ar_v = [];
	foreach ($list as $key => $value) {
		$key = trim($key);

		if (is_array($value)) {
			$value = toPhpArray($value);
			$ar_v[] = "\t'{$key}'	=> {$value}";
		} else {
			$value = trim($value);
			$ar_v[] = "\t'{$key}'	=> '{$value}'";
		}
	}

	$txt .= implode(",\n", $ar_v);
	$txt .= "\n)";

	return $txt;
}

//
function inHtml($html, $str) {
	return substr_count($html, $str) ? true : false;
}

// собрать случайную строку
function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}

	return $randomString;
}

// случайную сессию
function generateUUID($type = true) {
	$uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
	return $type ? $uuid : str_replace('-', '', $uuid);
}

//
function pdoSet($allowed, &$values, $source = array()) {
	$set = '';
	$values = array();
	if (!$source) $source = &$_POST;
	foreach ($allowed as $field) {
		if (isset($source[$field])) {
			$set.="`".str_replace("`","``",$field)."`". "=:$field, ";
			$values[$field] = $source[$field];
		}
	}
	return substr($set, 0, -2);
}

// отдать ajax в браузер
function showAjax($output) {
	if (!headers_Sent()) {
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Content-type: application/json;charset=" . CONFIG_WEB_CHARSET);
	}
	
	$txt = is_array($output) ? toJson($output) : $output;
	echo $txt;
}

// генерирует исключение если не верно значение
function isTrue($value,$message=null) {
	if ($value !== true) {
		throw new inValidException($message ?? "Value into function isTrue are FALSE!");
	}
}

// возвращает 40 символьную длину
function getHash() {
	return sha1(uniqid());
}

// оставляем только цифры
function formatInt($value) {
	$value = trim($value);
	$value = str_replace(",", ".", $value);
	$value = preg_replace("#[^0-9\.-]*#ism", "", $value);
	return intval($value);
}

// удаляем все не UTF последовательности (вдоичный левак, битые символы и тп)
// https://stackoverflow.com/questions/1401317/remove-non-utf8-characters-from-string
function formatString($value) {
	$regex = <<<'END'
/
  (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
    ){1,100}                        # ...one or more times
  )
| .                                 # anything else
/x
END;
	
	return trim(preg_replace($regex, '$1', $value));
}

// оставляем только телефон
function formatPhone($value) {
	$value = trim(formatInt($value));
	
	if (substr($value, 0, 1) == '8') {
		$value = '7' . substr($value, 1);
	}
	
	return $value;
}

// форматируем только на целочисленный тип
function formatFloat($value) {
	$value = trim($value);
	$value = str_replace(",", ".", $value);
	$value = preg_replace("#[^0-9\.-]*#ism", "", $value);
	return floatval($value);
}

// форматируем email
function formatEmail($value) {
	$value = strtolower($value);
	return filter_var($value, FILTER_SANITIZE_EMAIL);
}

// возвращает корректен ли емайл
function isEmailCorrect($value) {
	return filter_var($value, FILTER_VALIDATE_EMAIL) === false ? false : true;
}

//
function formatHash($value) {
	$value = preg_replace("#[^a-zA-Z0-9_\-=]*#ism", "", $value);
	return $value;
}

// сравнивает ip между собой (можно по принадлежности к подсети)
function isIpEqual($ip1,$ip2) {
	$ip_ar = explode('.', $ip1);

	$allow_ip_ar = explode('.', $ip2);
	$allow_result = true;
	foreach ($allow_ip_ar as $key => $d) {
		if ($d == '*') {
			continue;
		}

		$d = intval($d);

		//
		if (!isset($ip_ar[$key])) {
//			System_Admin::write(__FUNCTION__ , dd($ip1,$ip2));
		}

		if ($d != intval($ip_ar[$key]) || intval($ip_ar[$key]) < 1 || $d < 1) {
			$allow_result = false;
		}
	}

	return $allow_result;
}