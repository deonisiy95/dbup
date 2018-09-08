<?php
echo '<h1>Hello!</h1>';
require_once __DIR__ . '/../../start.php';

// начинаем работу
showAjax(ApiV1_Handler::doStart(get('api_method'), $_POST));