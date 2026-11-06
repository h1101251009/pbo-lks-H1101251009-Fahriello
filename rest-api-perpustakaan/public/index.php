<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require __DIR__ . '/../vendor/autoload.php';

use App\Config\Env;
use App\Contracts\BookRepositoryInterface;
use App\Contracts\CategoryRepositoryInterface;
use App\Controllers\BookController;
use App\Controllers\CategoryController;
use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Database\Connection;
use App\Repositories\MySQLBookRepository;
use App\Repositories\MySQLCategoryRepository;
use App\Services\BookService;
use App\Services\CategoryService;

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

// Binding Category
$container->bind(
    CategoryRepositoryInterface::class,
    fn(Container $c) => new MySQLCategoryRepository($c->make(PDO::class))
);

$container->bind(
    CategoryService::class,
    fn(Container $c) => new CategoryService($c->make(CategoryRepositoryInterface::class))
);

$request = new Request();

$container->bind(
    BookController::class,
    fn(Container $c) => new BookController($c->make(BookService::class), $request)
);

$container->bind(
    CategoryController::class,
    fn(Container $c) => new CategoryController($c->make(CategoryService::class), $request)
);

// Routing
try {
    $controller = $container->make(BookController::class);
    $categoryController = $container->make(CategoryController::class);

    $router = new Router();
    $router->get('/api/books', [$controller, 'index']);
    $router->get('/api/books/{id}', [$controller, 'show']);
    $router->post('/api/books', [$controller, 'store']);
    $router->put('/api/books/{id}', [$controller, 'update']);
    $router->delete('/api/books/{id}', [$controller, 'destroy']);

    // Route Category
    $router->get('/api/categories', [$categoryController, 'index']);
    $router->get('/api/categories/{id}', [$categoryController, 'show']);
    $router->post('/api/categories', [$categoryController, 'store']);
    $router->put('/api/categories/{id}', [$categoryController, 'update']);
    $router->delete('/api/categories/{id}', [$categoryController, 'destroy']);

    $router->dispatch($request);
} catch (\Throwable $e) {
    Response::json([
        'success' => false,
        'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
    ], 500);
}