<?php

namespace App\Controllers\Posts;


use App\Kernal\Database;
use App\Repositories\PostRepository;

class ShowController extends BaseController
{
    /**
     * @throws \JsonException
     */
    public function __invoke(string $id): void
    {
        $repository = new PostRepository(Database::getConnection());

        $post = $repository->getPost((int)$id);

        header('Content-Type: application/json; charset=utf-8');

        if ($post === null) {
            http_response_code(404);

            echo json_encode(['success' => false, 'error' => 'Post not found'], JSON_THROW_ON_ERROR);

            return;
        }

        echo json_encode(['success' => true, 'data' => $post], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }
}