<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use App\Controllers\HomeController;
use Framework\App;

$app = new App();

$app->get('/phpiggy/public/', [HomeController::class, 'home']);

$app->run();

return $app;
