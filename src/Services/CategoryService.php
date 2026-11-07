<?php

namespace App\Services;

use App\DTO\CategoryDTO;
use App\DTO\PostDTO;
use App\Repositories\CategoryRepository;

readonly class CategoryService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function getPostsInCategory(int $limit = 3): ?array
    {
        $rows = $this->categoryRepository->getPostsInCategory($limit);

        $categories = [];

        foreach ($rows as $row) {
            $categoryId = (int)$row['category_id'];

            if (!isset($categories[$categoryId])) {
                $categories[$categoryId] = new CategoryDTO(
                    id: $categoryId,
                    name: $row['category_name'],
                    slug: $row['category_slug'],
                    description: $row['category_description'],
                );
            }

            $categories[$categoryId]->addPost(
                new PostDTO(
                    id: (int)$row['post_id'],
                    title: $row['post_title'],
                    slug: $row['post_slug'],
                    description: $row['post_description'],
                    image: $row['post_image'],
                    views: (int)$row['post_views'],
                    publishedAt: $row['post_published_at'],
                )
            );
        }

        return array_map(fn(CategoryDTO $category) => $category->toArray(), array_values($categories));
    }
}