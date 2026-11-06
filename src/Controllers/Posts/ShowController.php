<?php

namespace App\Controllers\Posts;


use Smarty\Exception;
use Smarty\Smarty;

const RELATED_POSTS_LIMIT = 3;

class ShowController extends BaseController
{

    /**
     * @throws \JsonException
     * @throws Exception
     */
    public function __invoke(string $id): void
    {
        if (!ctype_digit($id) || (int)$id <= 0) {
            http_response_code(400);

            echo json_encode(['success' => false, 'error' => 'Invalid post ID',], JSON_THROW_ON_ERROR);

            return;
        }

        $post = $this->service->getPostById($id);

        if ($post === null) {
            http_response_code(404);

            echo json_encode(['success' => false, 'error' => 'Post not found'], JSON_THROW_ON_ERROR);

            return;
        }

        $smarty = new Smarty();

        $smarty->setTemplateDir(dirname(__DIR__, 3) . '/templates');
        $smarty->setCompileDir(dirname(__DIR__, 3) . '/templates_c');

        $smarty->assign([
            'post' => $post,
            'relatedPosts' => $this->service->getRelatedPosts($id, RELATED_POSTS_LIMIT),
        ]);

        header('Content-Type: text/html; charset=utf-8');

        $smarty->display('post.tpl');
    }
}