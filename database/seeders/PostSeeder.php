<?php

declare(strict_types=1);

namespace Database\Seeders;

use PDO;

class PostSeeder
{
    public function run(PDO $pdo): void
    {
        $posts = [
            [
                'title' => 'What’s New in PHP 8.2',
                'slug' => 'whats-new-in-php-82',
                'description' => 'An overview of PHP 8.2 features.',
                'content' => 'In this article, we will explore the key features of PHP 8.2 and how to use them.',
                'image' => '/assets/images/php-82.jpg',
                'views' => 150,
                'published_at' => '2026-10-20 10:00:00',
                'categories' => ['php'],
                'tags' => ['php-8', 'oop']
            ],
            [
                'title' => 'Working with MySQL Using PDO',
                'slug' => 'working-with-mysql-pdo',
                'description' => 'How to properly work with MySQL using PDO.',
                'content' => 'PDO provides a unified interface for interacting with databases.',
                'image' => '/assets/images/pdo.jpg',
                'views' => 320,
                'published_at' => '2026-10-22 12:00:00',
                'categories' => ['php', 'mysql'],
                'tags' => ['pdo', 'mysql']
            ],
            [
                'title' => 'Docker for PHP Developers',
                'slug' => 'docker-for-php-developer',
                'description' => 'The basics of using Docker in PHP projects.',
                'content' => 'Docker helps create isolated development environments.',
                'image' => '/assets/images/docker.jpg',
                'views' => 95,
                'published_at' => '2026-10-25 09:00:00',
                'categories' => ['devops', 'php'],
                'tags' => ['docker', 'php-8']
            ],
            [
                'title' => 'JavaScript Basics',
                'slug' => 'javascript-basics',
                'description' => 'An introduction to JavaScript fundamentals.',
                'content' => 'JavaScript is used to create interactive web interfaces.',
                'image' => '/assets/images/javascript.jpg',
                'views' => 210,
                'published_at' => '2026-10-27 14:00:00',
                'categories' => ['javascript'],
                'tags' => ['javascript']
            ],
            [
                'title' => 'SQL Query Optimization',
                'slug' => 'sql-query-optimization',
                'description' => 'Indexes and query optimization in MySQL.',
                'content' => 'Proper use of indexes helps improve SQL query performance.',
                'image' => '/assets/images/sql.jpg',
                'views' => 450,
                'published_at' => '2026-10-29 16:00:00',
                'categories' => ['mysql'],
                'tags' => ['mysql', 'performance']
            ],
        ];

        $postSql = "
            INSERT INTO posts (
                title,
                slug,
                description,
                content,
                image,
                views,
                status,
                published_at
            )
            VALUES (
                :title,
                :slug,
                :description,
                :content,
                :image,
                :views,
                'published',
                :published_at
            )
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                description = VALUES(description),
                content = VALUES(content),
                image = VALUES(image),
                views = VALUES(views),
                status = 'published',
                published_at = VALUES(published_at)
        ";

        $postStmt = $pdo->prepare($postSql);

        $findPost = $pdo->prepare(
            "SELECT id FROM posts WHERE slug = :slug"
        );

        $findCategory = $pdo->prepare(
            "SELECT id FROM categories WHERE slug = :slug"
        );

        $findTag = $pdo->prepare(
            "SELECT id FROM tags WHERE slug = :slug"
        );

        $attachCategory = $pdo->prepare("
            INSERT INTO post_categories (post_id, category_id)
            VALUES (:post_id, :category_id)
            ON DUPLICATE KEY UPDATE post_id = VALUES(post_id)
        ");

        $attachTag = $pdo->prepare("
            INSERT INTO post_tags (post_id, tag_id)
            VALUES (:post_id, :tag_id)
            ON DUPLICATE KEY UPDATE post_id = VALUES(post_id)
        ");

        foreach ($posts as $post) {

            $postStmt->execute([
                'title' => $post['title'],
                'slug' => $post['slug'],
                'description' => $post['description'],
                'content' => $post['content'],
                'image' => $post['image'],
                'views' => $post['views'],
                'published_at' => $post['published_at'],
            ]);

            $findPost->execute(['slug' => $post['slug']]);
            $postId = (int) $findPost->fetchColumn();

            foreach ($post['categories'] as $categorySlug) {
                $findCategory->execute(['slug' => $categorySlug]);
                $categoryId = $findCategory->fetchColumn();

                if ($categoryId === false) {
                    throw new \RuntimeException(
                        "Category not found: {$categorySlug}"
                    );
                }

                $attachCategory->execute([
                    'post_id' => $postId,
                    'category_id' => (int) $categoryId,
                ]);
            }

            foreach ($post['tags'] as $tagSlug) {
                $findTag->execute(['slug' => $tagSlug]);
                $tagId = $findTag->fetchColumn();

                if ($tagId === false) {
                    throw new \RuntimeException(
                        "Tag not found: {$tagSlug}"
                    );
                }

                $attachTag->execute([
                    'post_id' => $postId,
                    'tag_id' => (int) $tagId,
                ]);
            }
        }
    }
}