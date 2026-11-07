<?php

use App\Controllers\Posts\IndexController;
use App\Kernal\Router;

/** @var Router $router */

$router->get('/posts', IndexController::class);