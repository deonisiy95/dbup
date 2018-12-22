<?php
/**
 * Используется для тестирования методов
 */

require_once __DIR__ . "/../start.php";

//db::connect();

//console(db::query('SELECT * FROM bdup_db.session WHERE session_id = \'a22b7c8bec2a20f517a3da91cf90c117d5fccc04\''));

//$r = new Type_User_Session();
//$r->start();

//$result = Type_User_Main::create('dens', '122', '312');
//$row = Type_User_Main::getUserOrganisation(5);

//console($row);

//console(Type_Assessment_Main::getAll(5));
//console(ApiV1_Handler::doStart('auth/tryLogin', ['username'=> '2', 'password'=>'32']));

//$query_str = sprintf("INSERT INTO `assessment` (audit_object, address, auditor_id, assessment_link, created_at) VALUES ('%s', '%s', %g, '%s', %g)",
//    '2234',
//    '12',
//    12,
//    '213',
//    time()
//);

console(sha1(123));
