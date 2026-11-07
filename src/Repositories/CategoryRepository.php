<?php

namespace App\Repositories;

use App\DTO\PostFilter;
use DateTimeImmutable;
use PDO;

readonly class CategoryRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @throws \Exception
     */
    public function getCategoryPosts(int $categoryId, PostFilter $filter, int $limit, int $offset): array
    {
        [$where, $params] = $this->buildConditions($categoryId, $filter);

        $sortOptions = [
            'newest' => 'p.published_at DESC, p.id DESC',
            'oldest' => 'p.published_at ASC, p.id ASC',
            'views_desc' => 'p.views DESC, p.id DESC',
            'views_asc' => 'p.views ASC, p.id ASC',
        ];

        $orderBy = $sortOptions[$filter->sort] ?? $sortOptions['newest'];

        $sql = "
            SELECT
                p.id,
                p.title,
                p.slug,
                p.description,
                p.image,
                p.views,
                p.published_at
            FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE {$where}
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws \Exception
     */
    public function countCategoryPosts(int $categoryId, PostFilter $filter): int
    {
        [$where, $params] = $this->buildConditions($categoryId, $filter);

        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM posts p
            INNER JOIN post_categories pc ON pc.post_id = p.id
            WHERE {$where}
        ");

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    /**
     * @throws \Exception
     */
    private function buildConditions(int $categoryId, PostFilter $filter): array
    {
        $conditions = ["status = 'published'", "published_at <= NOW()", "pc.category_id = :category_id",];

        $params = ['category_id' => $categoryId,];

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

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("        
            SELECT id, name, slug, description
            FROM categories WHERE slug = :slug
            LIMIT 1
        ");

        $stmt->execute(['slug' => $slug]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getPostsInCategory(int $limit = 3): array
    {
        $sql = "
            SELECT
                c.id AS category_id, c.name AS category_name, c.slug AS category_slug, c.description AS category_description,

                p.id AS post_id,
                p.title AS post_title,
                p.slug AS post_slug,
                p.description AS post_description,
                p.image AS post_image,
                p.views AS post_views,
                p.published_at AS post_published_at

            FROM categories c

            INNER JOIN (
                SELECT
                    pc.category_id,
                    p.id,
                    p.title,
                    p.slug,
                    p.description,
                    p.image,
                    p.views,
                    p.published_at,

                    ROW_NUMBER() OVER ( PARTITION BY pc.category_id ORDER BY p.published_at DESC, p.id DESC ) AS row_num

                FROM post_categories pc

                INNER JOIN posts p ON p.id = pc.post_id

                WHERE p.status = 'published' AND p.published_at <= NOW()
            ) p ON p.category_id = c.id AND p.row_num <= :limit

            ORDER BY c.name ASC, p.published_at DESC, p.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}