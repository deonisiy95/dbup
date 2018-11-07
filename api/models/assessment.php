<?php

class Type_Assessment_Main {

    // получаем из базы доступные оценки пользователя
    public static function getAll($user_id){

        $row = db::query(sprintf('SELECT assessment.* FROM  user_assessment as user_assessment, assessment as assessment  WHERE user_assessment.user_id = %g and user_assessment.assessment_id = assessment.assessment_id', $user_id));
        return $row;
    }

    // сохранить в базе информацию об оценке
    public static function set() {

        $query_str = sprintf("INSERT INTO `assessment` (audit_object, address, auditor_id, assessment_link, created_at) VALUES ('%s', '%s', %g, %g)"

        );

    }



}