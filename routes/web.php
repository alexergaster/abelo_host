<?php

use App\Controllers\Posts\IndexController;
use App\Controllers\Posts\ShowController;
use App\Kernal\Router;

/** @var Router $router */

$router->get('/posts', IndexController::class);
$router->get('/posts/{id}', ShowController::class);