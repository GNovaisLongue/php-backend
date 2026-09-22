<?php

declare(strict_types=1);

use App\Controllers\ProductController;
use App\Controllers\UserController;
use App\Http\Response;

/**
 * Ordered REST endpoints.
 *
 *  1. Health / landing
 *  2. Products CRUD
 *  3. Users CRUD
 *  4. Demo dynamic route
 */

$productController = new ProductController();
$userController = new UserController();

// ---------------------------------------------------------------------------
// 1. Health / landing
// ---------------------------------------------------------------------------
$obRouter->get('/', [
    function () {
        return Response::html((string) file_get_contents(__DIR__ . '/../views/index.php'));
    },
]);

// BC alias for `php -S` setups that hit /index.php directly.
$obRouter->get('/index.php', [
    function () {
        return Response::html((string) file_get_contents(__DIR__ . '/../views/index.php'));
    },
]);

// ---------------------------------------------------------------------------
// 2. Products
// ---------------------------------------------------------------------------
$obRouter->get('/products', [
    function () use ($productController) {
        return $productController->index();
    },
]);

$obRouter->get('/product/{sku}', [
    function ($sku) use ($productController) {
        return $productController->show((string) $sku);
    },
]);

$obRouter->post('/product', [
    function ($request) use ($productController) {
        return $productController->store($request);
    },
]);

$obRouter->delete('/product/{sku}', [
    function ($sku) use ($productController) {
        return $productController->destroy((string) $sku);
    },
]);

// Mass delete: DELETE /products with JSON body { "skus": [...] }.
$obRouter->delete('/products', [
    function ($request) use ($productController) {
        return $productController->destroyMany($request);
    },
]);

// ---------------------------------------------------------------------------
// 3. Users
// ---------------------------------------------------------------------------
$obRouter->get('/users', [
    function () use ($userController) {
        return $userController->index();
    },
]);

$obRouter->get('/user/{id}', [
    function ($id) use ($userController) {
        return $userController->show($id);
    },
]);

$obRouter->post('/user', [
    function ($request) use ($userController) {
        return $userController->store($request);
    },
]);

$obRouter->delete('/user/{id}', [
    function ($id) use ($userController) {
        return $userController->destroy($id);
    },
]);

// ---------------------------------------------------------------------------
// 4. Demo dynamic route
// ---------------------------------------------------------------------------
$obRouter->get('/about/{idPage}', [
    function ($idPage) {
        return Response::html('Page ' . htmlspecialchars((string) $idPage, ENT_QUOTES, 'UTF-8'));
    },
]);
