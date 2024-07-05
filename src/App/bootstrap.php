<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use App\Config\Paths;
use App\Controllers\{HomeController, AboutController, RegisterController};
use Framework\App;
use function App\Config\{registerMiddleware};

$app = new App(Paths::SOURCE . "App/container-definitions.php");

$app->get('/', [HomeController::class, 'home']);
$app->get('/about', [AboutController::class, 'about']);
$app->get('/register', [RegisterController::class, 'registerView']);
$app->post('/register', [RegisterController::class, 'register']);

registerMiddleware($app);

return $app;
