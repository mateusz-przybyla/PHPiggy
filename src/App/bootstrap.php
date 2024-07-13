<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use App\Config\Paths;
use App\Controllers\{HomeController, AboutController, AuthController};
use Dotenv\Dotenv;
use Framework\App;

use function App\Config\{registerMiddleware};

$dotenv = Dotenv::createImmutable(Paths::ROOT);
$dotenv->load();

$app = new App(Paths::SOURCE . "App/container-definitions.php");

$app->get('/', [HomeController::class, 'home']);
$app->get('/about', [AboutController::class, 'about']);
$app->get('/register', [AuthController::class, 'registerView']);
$app->post('/register', [AuthController::class, 'register']);
$app->get('/login', [AuthController::class, 'loginView']);
$app->post('/login', [AuthController::class, 'login']);

registerMiddleware($app);

return $app;
