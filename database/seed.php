<?php


use App\Kernal\Database;

use Database\Seeders\CategorySeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\TagSeeder;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $pdo = Database::getConnection();

    $pdo->beginTransaction();

    (new CategorySeeder())->run($pdo);

    (new TagSeeder())->run($pdo);

    (new PostSeeder())->run($pdo);

    $pdo->commit();

    echo "Database seeding completed successfully!" . PHP_EOL;

} catch (Throwable $e) {

    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo "Seeding failed: " . $e->getMessage() . PHP_EOL;

    exit(1);
}
