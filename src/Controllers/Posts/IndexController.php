<?php

namespace App\Controllers\Posts;

use App\Requests\PaginationRequest;

const POSTS_PER_PAGE = 6;

class IndexController extends BaseController
{
    public function __invoke(): void
    {
        $pagination = new PaginationRequest($_GET);

        header('Content-Type: json-application; charset=utf-8');

        echo json_encode(['data' => $this->service->getPosts($pagination->getPage(), $pagination->getPerPage())]);
    }
}