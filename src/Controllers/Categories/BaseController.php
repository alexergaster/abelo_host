<?php

namespace App\Controllers\Categories;

use App\Kernal\Database;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;

abstract class BaseController
{
    protected readonly CategoryService $service;

    public function __construct()
    {
        $repository = new CategoryRepository(Database::getConnection());
        $this->service = new CategoryService($repository);
    }
}