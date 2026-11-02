<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/StockController.php';
require_once __DIR__ . '/../controllers/SummaryController.php';

$db = (new Database())->connect();

$uri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/products' && $method === 'GET')
{
    $controller = new ProductController($db);

    $controller->index();
}

if (
    preg_match('#^/products/(\d+)$#', $uri, $matches)
    && $method === 'GET'
)
{
    $controller = new ProductController($db);

    $controller->show($matches[1]);
}
if ($uri === '/products' && $method === 'POST')
{
    $controller = new ProductController($db);

    $controller->store();
}
if (
    preg_match('#^/products/(\d+)$#', $uri, $matches)
    && $method === 'PUT'
)
{
    $controller = new ProductController($db);

    $controller->update($matches[1]);
}
if (
    preg_match('#^/products/(\d+)$#', $uri, $matches)
    && $method === 'DELETE'
)
{
    $controller = new ProductController($db);

    $controller->delete($matches[1]);
}
if (
    preg_match('#^/products/category/(\d+)$#', $uri, $matches)
    && $method === 'GET'
)
{
    $controller = new ProductController($db);

    $controller->category($matches[1]);
}
if (
    $uri === '/stock/in'
    && $method === 'POST'
)
{
    $controller = new StockController($db);

    $controller->stockIn();
}
if (
    $uri === '/stock/out'
    && $method === 'POST'
)
{
    $controller = new StockController($db);

    $controller->stockOut();
}
if (
    preg_match('#^/products/(\d+)/movements$#', $uri, $matches)
    && $method === 'GET'
)
{
    $controller = new StockController($db);

    $controller->history($matches[1]);
}
if (
    $uri === '/summary'
    && $method === 'GET'
)
{
    $controller = new SummaryController($db);

    $controller->index();
}
