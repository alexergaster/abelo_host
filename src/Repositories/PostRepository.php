<?php

namespace App\Repositories;

use PDO;

readonly class PostRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getPost(string $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            return null;
        }

        $stmt = $this->pdo->prepare("
            SELECT c.* FROM categories c
            INNER JOIN post_categories pc ON pc.category_id = c.id
            WHERE pc.post_id = :id
        ");

        $stmt->execute(['id' => $id]);
        $post['categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare("
            SELECT t.* FROM tags t
            INNER JOIN post_tags pt ON pt.tag_id = t.id
            WHERE pt.post_id = :id
        ");

        $stmt->execute(['id' => $id]);
        $post['tags'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $post;
    }
}