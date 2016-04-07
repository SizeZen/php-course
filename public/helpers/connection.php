<?php
require_once '../vendor/autoload.php';

$usr = 'root';
$pwd = 'root';
$dsn = 'mysql:host=localhost;dbname=couse;charset=utf8';

$pdo = new \Slim\PDO\Database($dsn, $usr, $pwd);

return $pdo;
