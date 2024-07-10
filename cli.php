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

require __DIR__ . '/vendor/autoload.php';

use Framework\Database;
use App\Config\Paths;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(Paths::ROOT);
$dotenv->load();

$db = new Database($_ENV['DB_DRIVER'], [
  'host' => $_ENV['DB_HOST'],
  'port' => $_ENV['DB_PORT'],
  'dbname' => $_ENV['DB_NAME']
], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$sqlFile = file_get_contents("./database.sql");

$db->query($sqlFile);
