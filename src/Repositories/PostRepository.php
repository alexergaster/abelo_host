<?php

namespace App\Repositories;

use App\DTO\PostFilter;
use DateTimeImmutable;
use PDO;

readonly class PostRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @throws \Exception
     */
    private function buildConditions(PostFilter $filter): array
    {
        $conditions = ["status = 'published'", "published_at <= NOW()",];

        $params = [];

        if ($filter->viewsMin !== null) {
            $conditions[] = 'views >= :views_min';
            $params['views_min'] = $filter->viewsMin;
        }

        if ($filter->viewsMax !== null) {
            $conditions[] = 'views <= :views_max';
            $params['views_max'] = $filter->viewsMax;
        }

        if ($filter->dateFrom !== null) {
            $conditions[] = 'published_at >= :date_from';
            $params['date_from'] = $filter->dateFrom . ' 00:00:00';
        }

        if ($filter->dateTo !== null) {
            $conditions[] = 'published_at < :date_to';
            $params['date_to'] = (new DateTimeImmutable($filter->dateTo))
                ->modify('+1 day')
                ->format('Y-m-d 00:00:00');
        }

        return [implode(' AND ', $conditions), $params,];
    }

    /**
     * @throws \Exception
     */
    public function getPosts(PostFilter $filter, int $limit = 6, int $offset = 0): ?array
    {
        [$where, $params] = $this->buildConditions($filter);

        $sortOptions = [
            'newest' => 'published_at DESC, id DESC',
            'oldest' => 'published_at ASC, id ASC',
            'views_desc' => 'views DESC, id DESC',
            'views_asc' => 'views ASC, id ASC',
        ];

        $orderBy = $sortOptions[$filter->sort];

        $stmt = $this->pdo->prepare("
            SELECT id, title, slug, description, image, views, published_at
            FROM posts
           WHERE {$where}
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ");

        foreach ($params as $name => $value) {
            $stmt->bindValue(':' . $name, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws \Exception
     */
    public function countPosts(PostFilter $filter): int
    {
        [$where, $params] = $this->buildConditions($filter);

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM posts
            WHERE {$where}
        ");

        foreach ($params as $name => $value) {
            $stmt->bindValue(':' . $name, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();

        return (int)$stmt->fetchColumn();
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