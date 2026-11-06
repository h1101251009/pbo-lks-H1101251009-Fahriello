<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Config\Env;
use App\Contracts\BookRepositoryInterface;
use App\Controllers\BookController;
use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Database\Connection;
use App\Repositories\MySQLBookRepository;
use App\Services\BookService;

Env::load(__DIR__ . '/../.env');

// Binding Container
$container = new Container();

$container->bind(PDO::class, fn() => Connection::get());

$container->bind(
    BookRepositoryInterface::class,
    fn(Container $c) => new MySQLBookRepository($c->make(PDO::class))
);

$container->bind(
    BookService::class,
    fn(Container $c) => new BookService($c->make(BookRepositoryInterface::class))
);

$request = new Request();

$container->bind(
    BookController::class,
    fn(Container $c) => new BookController($c->make(BookService::class), $request)
);

// Routing
try {
    $controller = $container->make(BookController::class);

    $router = new Router();
    $router->get('/api/books', [$controller, 'index']);
    $router->get('/api/books/{id}', [$controller, 'show']);
    $router->post('/api/books', [$controller, 'store']);
    $router->put('/api/books/{id}', [$controller, 'update']);
    $router->delete('/api/books/{id}', [$controller, 'destroy']);

    $router->dispatch($request);
} catch (\Throwable $e) {
    Response::json([
        'success' => false,
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
    ], 500);
}

