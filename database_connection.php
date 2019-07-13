<?
require_once('database_config.php');

$dbh = new PDO(sprintf("mysql:host=%s; dbname=%s; charset=utf8", $db_host, $db_name), $db_user, $db_password);
