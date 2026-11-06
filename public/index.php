<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Kernal\Router;

$router = new Router();

require_once dirname(__DIR__) . '/vendor/autoload.php';

$router->load(dirname(__DIR__) . '/routes/web.php');

$router->load(dirname(__DIR__) . '/routes/api.php', '/api');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
