<?php

namespace App\Services;

use App\DTO\PostFilter;
use App\Repositories\PostRepository;

readonly class PostService
{
    public function __construct(private PostRepository $postRepository)
    {
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