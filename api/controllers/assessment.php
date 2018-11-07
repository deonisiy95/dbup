<?php

Class ApiV1_Assessment extends ApiV1_Default {

    // Поддерживаемые методы. Регистр не имеет значение */
    protected $allow_methods = [
        'getAll',
        'tryUpload'
    ];

    // получение информации о профиле
    protected function getAll() {

        // получим информацию из бд
        $row = Type_Assessment_Main::getAll($this->user->user_id);

        return $this->ok($row);
    }

    protected function tryUpload() {

        // получим название файла
        $file_name = $this->post('?s', 'file_name');

        // путь к папке с файлами оценки
        $uploads_dir = '../assessment';

        // получим сам файл
        $tmp_name = $_FILES["file"]["tmp_name"];

        // сохраняем файл
        move_uploaded_file($tmp_name, "$uploads_dir/$file_name");

        return $this->ok();
    }

}