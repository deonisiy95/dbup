<?php

// ----
// основной файл, которые инклудим при старте
// ----


// locale
date_default_timezone_set('Europe/Moscow');
setlocale(LC_ALL			, 'en_US.utf8');
setlocale(LC_NUMERIC			, 'en_US.utf8');

// устанавливаем пути
define('PATH_ROOT'			, dirname(__FILE__).'/');
define('PATH_API'			, dirname(__FILE__).'/api/');
define('PATH_LOGS'			, dirname(__FILE__).'/logs/');
define('PATH_STATIC'			, dirname(__FILE__).'/www/static/');
define('CONFIG_WEB_CHARSET'		, 'UTF-8');

// base functions
require_once PATH_API 	. "system/functions.php";
require_once PATH_API 	. "system/exception.php";
require_once PATH_API 	. "system/define.php";
require_once PATH_API 	. "system/mcache.php";
require_once PATH_API 	. "system/db.php";

// private data
require_once PATH_API	. "private/main.php";
require_once PATH_API	. "private/custom.php";

// controllers
require_once PATH_API	. "controllers/default.php";
require_once PATH_API	. "controllers/handler.php";
require_once PATH_API	. "controllers/auth.php";




if (!headers_Sent()) {
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Content-type: text/html;charset=" . CONFIG_WEB_CHARSET );
}

