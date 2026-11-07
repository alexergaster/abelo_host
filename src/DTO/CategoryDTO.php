<?php

namespace App\DTO;

class CategoryDTO
{
    private array $posts = [];

    public function __construct(
        public readonly int     $id,
        public readonly string  $name,
        public readonly string  $slug,
        public readonly ?string $description,
    )
    {
    }

    public function addPost(PostDTO $post): void
    {
        $this->posts[] = $post;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'posts' => array_map(fn(PostDTO $post) => $post->toArray(), $this->posts),
        ];
    }
}