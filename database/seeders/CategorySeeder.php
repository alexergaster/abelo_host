<?php

namespace Database\Seeders;

use PDO;

class CategorySeeder
{
    public function run(PDO $pdo): void
    {
        $categories = [
            [
                'name' => 'PHP',
                'slug' => 'php',
                'description' => 'Articles about PHP and web development.'
            ],
            [
                'name' => 'MySQL',
                'slug' => 'mysql',
                'description' => 'Working with MySQL databases.'
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'description' => 'Articles about JavaScript.'
            ],
            [
                'name' => 'DevOps',
                'slug' => 'devops',
                'description' => 'Docker, servers, and automation.'
            ],
        ];

        $sql = "
            INSERT INTO categories (name, slug, description)
            VALUES (:name, :slug, :description)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                description = VALUES(description)
        ";

        $stmt = $pdo->prepare($sql);

        foreach ($categories as $category) {
            $stmt->execute($category);
        }
    }
}