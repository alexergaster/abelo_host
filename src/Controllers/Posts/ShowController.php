<?php

namespace App\Controllers\Posts;


class ShowController extends BaseController
{
    public function __invoke(string $id): void
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'id' => (int) $id,
            'message' => 'Post found',
        ], JSON_THROW_ON_ERROR);
    }
}