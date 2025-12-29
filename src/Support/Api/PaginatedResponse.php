<?php

namespace Spanvel\Support\Api;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PaginatedResponse
{
    /**
     * Build a standardized paginated API payload from a Laravel paginator.
     *
     * @return array{
     *   data: array<mixed>,
     *   pagination: array{
     *     mode: 'simple'|'length_aware',
     *     current_page: int,
     *     per_page: int,
     *     from: int|null,
     *     to: int|null,
     *     has_more: bool,
     *     total: int|null,
     *     last_page: int|null,
     *   }
     * }
     */
    public static function from(LengthAwarePaginator|Paginator $paginator): array
    {
        $isLengthAware = $paginator instanceof LengthAwarePaginator;

        return [
            'data' => $paginator->items(),

            'pagination' => [
                'mode'         => $isLengthAware ? 'length_aware' : 'simple',

                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
                'has_more'     => $paginator->hasMorePages(),

                'total'        => $isLengthAware ? $paginator->total() : null,
                'last_page'    => $isLengthAware ? $paginator->lastPage() : null,
            ],
        ];
    }
}
