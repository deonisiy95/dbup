<?php

Class ApiV1_Profile extends ApiV1_Default {

    // Поддерживаемые методы. Регистр не имеет значение */
    protected $allow_methods = [
        'get', 'getAssessmentList'
    ];

    // получение информации о профиле
    protected function get() {

        // получим информацию из бд
        $row = Type_User_Main::getFullInfo($this->user->user_id);

        // определим результат
        $output = [
            'full_name' => $row['full_name'],
            'role' => $row['role'],
            'organisation_name' => $row['organisation_name'],
            'address' => $row['address'],
        ];

        return $this->ok($output);
    }


}