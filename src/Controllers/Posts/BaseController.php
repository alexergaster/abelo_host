<?php

namespace App\Controllers\Posts;

use App\Kernal\Database;
use App\Repositories\PostRepository;
use App\Services\PostService;

abstract class BaseController
{
    protected readonly PostService $service;

    public function __construct()
    {
        $repository = new PostRepository(Database::getConnection());
        $this->service = new PostService($repository);
    }
}