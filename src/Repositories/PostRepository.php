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

    public function incrementViews(int $postId): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE posts SET views = views + 1
            WHERE id = :id AND status = 'published'
        ");

        $stmt->execute(['id' => $postId]);
    }

    public function getRelatedPosts(int $postId, int $limit = 3): array
    {
        $sql = "
        SELECT
            p.id,
            p.title,
            p.slug,
            p.description,
            p.image,
            p.views,
            p.published_at,
            (
                COUNT(DISTINCT pc.category_id) * 3 +
                COUNT(DISTINCT pt.tag_id) * 2
            ) AS relevance_score

        FROM posts p

        LEFT JOIN post_categories pc
            ON pc.post_id = p.id
            AND pc.category_id IN (
                SELECT category_id
                FROM post_categories
                WHERE post_id = :category_post_id
            )

        LEFT JOIN post_tags pt
            ON pt.post_id = p.id
            AND pt.tag_id IN (
                SELECT tag_id
                FROM post_tags
                WHERE post_id = :tag_post_id
            )

        WHERE p.id != :current_post_id
          AND p.status = 'published'

        GROUP BY
            p.id,
            p.title,
            p.slug,
            p.description,
            p.image,
            p.views,
            p.published_at

        HAVING relevance_score > 0

        ORDER BY
            relevance_score DESC,
            p.published_at DESC,
            p.id DESC

        LIMIT :limit
    ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':category_post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':tag_post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':current_post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', max(1, min($limit, 20)), PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}