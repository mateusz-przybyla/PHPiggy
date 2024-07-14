<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use App\Config\Paths;
use App\Controllers\{HomeController, AboutController, AuthController};
use Dotenv\Dotenv;
use Framework\App;
use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware};

use function App\Config\{registerMiddleware};

$dotenv = Dotenv::createImmutable(Paths::ROOT);
$dotenv->load();

$app = new App(Paths::SOURCE . "App/container-definitions.php");

$app->get('/', [HomeController::class, 'home'])->add(AuthRequiredMiddleware::class);
$app->get('/about', [AboutController::class, 'about']);
$app->get('/register', [AuthController::class, 'registerView'])->add(GuestOnlyMiddleware::class);
$app->post('/register', [AuthController::class, 'register'])->add(GuestOnlyMiddleware::class);
$app->get('/login', [AuthController::class, 'loginView'])->add(GuestOnlyMiddleware::class);
$app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);

registerMiddleware($app);

return $app;
