<?php

namespace App\DTO;

use DateTimeImmutable;

const ALLOWED_SORT_FIELDS = ['newest', 'oldest', 'views_desc', 'views_asc'];

readonly class PostFilter
{
    public function __construct(
        public ?int    $viewsMin = null,
        public ?int    $viewsMax = null,
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public string  $sort = 'newest'
    )
    {
    }

    public static function fromQuery(array $query): self
    {
        $viewsMin = self::parseViews($query['views_min'] ?? null);
        $viewsMax = self::parseViews($query['views_max'] ?? null);

        if ($viewsMin !== null && $viewsMax !== null && $viewsMin > $viewsMax) {
            [$viewsMin, $viewsMax] = [$viewsMax, $viewsMin];
        }

        $sort = $query['sort'] ?? 'newest';

        if (!is_string($sort) || !in_array($sort, ALLOWED_SORT_FIELDS, true)) {
            $sort = 'newest';
        }

        return new self(
            viewsMin: $viewsMin,
            viewsMax: $viewsMax,
            dateFrom: self::parseDate($query['date_from'] ?? null),
            dateTo: self::parseDate($query['date_to'] ?? null),
            sort: $sort
        );
    }

    private static function parseViews(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $result = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

        return $result === false ? null : $result;
    }

    private static function parseDate(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        if (!$date || $date->format('Y-m-d') !== $value) {
            return null;
        }

        return $value;
    }
}