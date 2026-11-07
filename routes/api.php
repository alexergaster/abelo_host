<?php

use App\Controllers\Categories\IndexController;
use App\Kernal\Router;

/** @var Router $router */

$router->get('/categories', IndexController::class);