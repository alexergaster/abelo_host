<?php

namespace App\Controllers\Posts;

use App\Services\PostService;

class BaseController
{
    protected PostService $postService;

    public function __construct()
    {
        $this->postService = new PostService();
    }
}