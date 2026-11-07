<?php

namespace App\DTO;

readonly class PostDTO
{
    public function __construct(
        public int     $id,
        public string  $title,
        public string  $slug,
        public ?string $description,
        public ?string $image,
        public int     $views,
        public ?string $publishedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'views' => $this->views,
            'published_at' => $this->publishedAt,
        ];
    }
}