<?php
/*
$driver = 'mysql';
$config = http_build_query(data: [
  'host' => 'localhost',
  'port' => 3306,
  'dbname' => 'phpiggy'
], arg_separator: ';');

$dsn = "{$driver}:{$config}";
$username = 'root';
$password = '';

try {
  $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
  die("Unable to connect to database");
}
*/

include __DIR__ . '/src/Framework/Database.php';

use Framework\Database;

$db = new Database('mysql', [
  'host' => 'localhost',
  'port' => 3306,
  'dbname' => 'phpiggy'
], 'root', '');

$sqlFile = file_get_contents("./database.sql");

$db->query($sqlFile);
