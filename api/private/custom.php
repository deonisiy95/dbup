<?php

// ----
// private/custom.php
// Индивиудалньые для каждого модуля приватные констакнты
// Например: ключи шифрования, ключи доступа к SMS и внешним сервисам
// ----

// для шифрования пользовательских паролей
// echo bin2hex(mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_TYPE, MCRYPT_MODE_ECB), MCRYPT_RAND));


// токен бота telegram
define('TELEGRAM_BOT_TOKEN', '573301813:AAG6HgNkg9ThpqeUwA4k0FiMLGEHnp5AEr8');

// название очереди для вебсокета
define('WEBSOCKET_RABBITMQ', 'go_websocket');

// адрес и порт для создания вебсокета
define('WEBSOCKET_ADDR', 'ws://192.168.1.162:8000');

// включена ли двухфакторная авторизация
define('TWO_FACTOR_ENABLED', true);


