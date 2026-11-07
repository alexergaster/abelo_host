<?php

use App\Controllers\Categories\IndexController;
use App\Controllers\Categories\ShowController;
use App\Controllers\Posts\ShowController as PostShowController;
use App\Kernal\Router;

/** @var Router $router */


$router->get('/', IndexController::class);
$router->get('/categories/{slug}', ShowController::class);
$router->get('/posts/{id}', PostShowController::class);