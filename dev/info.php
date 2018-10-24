<?php
/**
 * Используется для тестирования методов
 */

require_once __DIR__ . "/../start.php";

db::connect();

//console(db::query('SELECT * FROM bdup_db.session WHERE session_id = \'a22b7c8bec2a20f517a3da91cf90c117d5fccc04\''));

//$r = new Type_User_Session();
//$r->start();

//$result = Type_User_Main::create('dens', '122', '312');
//$row = Type_User_Main::getUserOrganisation(5);

//console($row);

console(sprintf('SELECT * FROM `user` as user, `organisation` as organisation  WHERE `user_id` = %g and organisation.organisation_id = user.organisation_id', 5));