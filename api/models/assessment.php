<?php

class Type_Assessment_Main {

    // получаем из базы доступные оценки пользователя
    public static function getAll($user_id){

        $row = db::query(sprintf('SELECT assessment.*, document.document_poll FROM  user_assessment as user_assessment, assessment as assessment, document as document  WHERE user_assessment.user_id = %g and user_assessment.assessment_id = assessment.assessment_id and document.assessment_id = assessment.assessment_id', $user_id));
        return $row;
    }

    // получаем из базы доступные оценки пользователя
    public static function setDocument($document_poll, $assessment_id){

        // занесем информацию об оценке в базу
        $query_str = sprintf("UPDATE `document` SET document_poll = '%s' WHERE assessment_id = %g",
            $document_poll,
            $assessment_id
        );

        db::query($query_str);
    }

    // сохранить в базе информацию об оценке
    public static function set($assessment_id, $audit_object, $address, $auditor_id, $assessment_link) {

        // занесем информацию об оценке в базу
        $query_str = sprintf("INSERT INTO `assessment` (assessment_id, audit_object, address, auditor_id, assessment_link, created_at) VALUES (%g, '%s', '%s', %g, '%s', %g)",
            $assessment_id,
            $audit_object,
            $address,
            $auditor_id,
            $assessment_link,
            time()
        );

        db::query($query_str);

        // запишем ее к создателю
        $query_str = sprintf("INSERT INTO `user_assessment` (user_id, assessment_id) VALUES (%g, %g)",
            $auditor_id,
            $assessment_id
        );

        db::query($query_str);


    }



}