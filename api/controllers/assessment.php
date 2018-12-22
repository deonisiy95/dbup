<?php

Class ApiV1_Assessment extends ApiV1_Default {

    // Поддерживаемые методы. Регистр не имеет значение */
    protected $allow_methods = [
        'getAll',
        'tryUpload',
        'setDocument'
    ];

    // получение информации о профиле
    protected function getAll() {

        // получим информацию из бд
        $row = Type_Assessment_Main::getAll($this->user->user_id);

        return $this->ok($row);
    }

    protected function setDocument() {

        // получим информацию о документах
        $document_info = $this->post('?s','document_poll');

        // получим идентификатор оценки
        $assessment_id = $this->post('?i', 'assessment_id');

        // сохраняем в базе
        Type_Assessment_Main::setDocument($document_info, $assessment_id);

    }

    protected function tryUpload() {

        // получим id оценки
        $assessment_id = $this->post('?i', 'assessment_id');

        // метка создаем ли новую оценку
        $is_create = false;

        // если не передали идентификатор оценки, то создадим новую
        if ($assessment_id == -1) {

            // сформируем id
            $assessment_id = rand(0,10000);
            $is_create = true;
        }

        // получим название файла
        $file_name = $assessment_id . '.dbup';

        // объект оценки
        $audit_object = $this->post('?s', 'audit_object');

        // адрес объекта оценки
        $address = $this->post('?s', 'address');

        // идентификатор аудитора
        $auditor_id = $this->user->user_id;

        debug('upload = ' . $this->user->user_id);

        // ссылка на файл оценки
        $assessment_link = 'http://' . $_SERVER['HTTP_HOST'] . '/assessment/' . $file_name;

        // путь к папке с файлами оценки
        $uploads_dir = '../assessment';

        // получим сам файл
        $tmp_name = $_FILES["file"]["tmp_name"];

        // сохраняем файл
        move_uploaded_file($tmp_name, "$uploads_dir/$file_name");

        // если создаем новую оценку
        if ($is_create) {

            // запишем в базу
            Type_Assessment_Main::set($assessment_id, $audit_object, $address, $auditor_id, $assessment_link);
        }

        return $this->ok();
    }

}