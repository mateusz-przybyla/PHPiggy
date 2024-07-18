<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use App\Config\Paths;
use App\Controllers\{
  HomeController,
  AboutController,
  AuthController,
  TransactionController,
  ReceiptController,
  ErrorController
};
use Dotenv\Dotenv;
use Framework\App;
use App\Middleware\{
  AuthRequiredMiddleware,
  GuestOnlyMiddleware
};

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
$app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);
$app->get('/transaction', [TransactionController::class, 'createView'])->add(AuthRequiredMiddleware::class);
$app->post('/transaction', [TransactionController::class, 'create'])->add(AuthRequiredMiddleware::class);
$app->get('/transaction/{transaction}', [TransactionController::class, 'editView'])->add(AuthRequiredMiddleware::class);
$app->post('/transaction/{transaction}', [TransactionController::class, 'edit'])->add(AuthRequiredMiddleware::class);
$app->delete('/transaction/{transaction}', [TransactionController::class, 'delete'])->add(AuthRequiredMiddleware::class);
$app->get('/transaction/{transaction}/receipt', [ReceiptController::class, 'uploadView'])->add(AuthRequiredMiddleware::class);
$app->post('/transaction/{transaction}/receipt', [ReceiptController::class, 'upload'])->add(AuthRequiredMiddleware::class);
$app->get('/transaction/{transaction}/receipt/{receipt}', [ReceiptController::class, 'download'])->add(AuthRequiredMiddleware::class);
$app->delete('/transaction/{transaction}/receipt/{receipt}', [ReceiptController::class, 'delete'])->add(AuthRequiredMiddleware::class);

$app->setErrorHandler([ErrorController::class, 'notFound']);

registerMiddleware($app);

return $app;
