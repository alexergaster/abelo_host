<?php

namespace App\Services;

use App\Repositories\PostRepository;

readonly class PostService
{
    public function __construct(private PostRepository $postRepository)
    {
    }

    public function getPosts(int $page = 1, int $perPage = 6): array
    {
        $page = max(1, $page);
        $perPage = max(1, min($perPage, 100));

        $total = $this->postRepository->countPosts();
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $posts = $this->postRepository->getPosts($perPage, $offset);

        return [
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
            ]
        ];
    }

    public function getRelatedPosts(int $postId, int $limit = 3): array
    {
        return $this->postRepository->getRelatedPosts($postId, $limit);
    }

    public function getPostById(string $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $this->postRepository->incrementViews($id);

        return $this->postRepository->getPost($id);
    }
}