CREATE DATABASE  IF NOT EXISTS `bdup_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор пользователя',
  `username` varchar(45) NOT NULL COMMENT 'Логин пользователя',
  `auth_hash` varchar(45) NOT NULL COMMENT 'Хэш от пароля',
  `organisation_id` int(11) NOT NULL COMMENT 'Идентификатор организации',
  `created_at` int(11) NOT NULL COMMENT 'Время создания пользователя',
  `role` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
)

CREATE TABLE `assessment` (
  `assessment_id` int(11) NOT NULL,
  `audit_object` varchar(100) NOT NULL,
  `address` varchar(45) NOT NULL,
  `auditor_id` int(11) NOT NULL,
  `assessment_link` varchar(256) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`assessment_id`)
)

CREATE TABLE `session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `ua_hash` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)

CREATE TABLE `organisation` (
  `organisation_id` int(11) NOT NULL AUTO_INCREMENT,
  `organisation_name` varchar(45) NOT NULL,
  `address` varchar(45) NOT NULL,
  PRIMARY KEY (`organisation_id`)
)

CREATE TABLE `user_assessment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)