<?php

namespace App\Services;

use App\DTO\CategoryDTO;
use App\DTO\PostDTO;
use App\DTO\PostFilter;
use App\Repositories\CategoryRepository;

readonly class CategoryService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    /**
     * @throws \Exception
     */
    public function getPostsByCategory(string $slug, PostFilter $filter, int $page = 1, int $perPage = 6): ?array
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if ($category === null) {
            return null;
        }

        $page = max(1, $page);
        $perPage = max(1, min($perPage, 100));

        $total = $this->categoryRepository->countCategoryPosts((int) $category['id'], $filter);
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $posts = $this->categoryRepository->getCategoryPosts((int) $category['id'], $filter, $perPage, $offset);

        return [
            'category' => $category,
            'posts' => $posts,
            'pagination' => [
                'currentPage' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'totalPages' => $totalPages,
                'hasPrevious' => $page > 1,
                'hasNext' => $page < $totalPages,
                'previousPage' => $page > 1 ? $page - 1 : null,
                'nextPage' => $page < $totalPages ? $page + 1 : null,
            ],
        ];
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