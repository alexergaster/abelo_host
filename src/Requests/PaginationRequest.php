<?php

namespace App\Requests;

use const App\Controllers\Posts\POSTS_PER_PAGE;

const MAX_PER_PAGE = 100;

readonly class PaginationRequest
{
    private int $page;
    private int $perPage;

    public function __construct(array $query, int $defaultPerPage = POSTS_PER_PAGE, int $maxPerPage = MAX_PER_PAGE)
    {
        $this->page = $this->getPositiveInt($query['page'] ?? 1, 1);
        $this->perPage = min($this->getPositiveInt($query['perPage'] ?? $defaultPerPage, $defaultPerPage), $maxPerPage);
    }

    private function getPositiveInt(mixed $value, int $default): int
    {
        $result = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            [
                'options' => ['min_range' => 1, 'max_range' => PHP_INT_MAX]
            ]
        );

        return $result === false ? $default : $result;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}