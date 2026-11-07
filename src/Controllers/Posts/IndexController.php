<?php

namespace App\Controllers\Posts;

use App\DTO\PostFilter;
use App\Requests\PaginationRequest;

const POSTS_PER_PAGE = 6;

class IndexController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function __invoke(): void
    {
        $filter = PostFilter::fromQuery($_GET);
        $pagination = new PaginationRequest($_GET);

        header('Content-Type: json-application; charset=utf-8');

        echo json_encode(['data' => $this->service->getPosts($filter, $pagination->getPage(), $pagination->getPerPage())]);
    }
}