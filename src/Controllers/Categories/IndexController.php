<?php

namespace App\Controllers\Categories;

class IndexController extends BaseController
{
    public function __invoke(): void
    {
        $categories = $this->service->getPostsInCategory(3);

        header('Content-Type: json-application; charset=utf-8');

        echo json_encode(['data' => $categories]);
    }
}