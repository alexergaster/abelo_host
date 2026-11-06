<?php

namespace Database\Seeders;

use PDO;

class TagSeeder
{
    public function run(PDO $pdo): void
    {
        $tags = [
            ['name' => 'PHP 8', 'slug' => 'php-8'],
            ['name' => 'OOP', 'slug' => 'oop'],
            ['name' => 'PDO', 'slug' => 'pdo'],
            ['name' => 'MySQL', 'slug' => 'mysql'],
            ['name' => 'Docker', 'slug' => 'docker'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'SCSS', 'slug' => 'scss'],
            ['name' => 'Performance', 'slug' => 'performance'],
        ];

        $sql = "
            INSERT INTO tags (name, slug)
            VALUES (:name, :slug)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name)
        ";

        $stmt = $pdo->prepare($sql);

        foreach ($tags as $tag) {
            $stmt->execute($tag);
        }
    }
}
