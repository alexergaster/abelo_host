<?php

namespace App\Repositories;

use PDO;

readonly class CategoryRepository
{
    public function __construct(private PDO $pdo)
    {
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