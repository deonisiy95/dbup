<?php

function exception_handler($exception) {
    echo "Неперехваченное исключение: " , $exception, "\n";
}

set_exception_handler('exception_handler');