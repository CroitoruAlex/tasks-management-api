<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    /**
     * Consistent JSON response for paginated data
     */
    protected function jsonResponseWithPagination(LengthAwarePaginator $paginator)
    {
        $executionTime = (microtime(true) - LARAVEL_START) * 1000;

        return response()->json([
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ],
            'timestamp' => now()->format('Y-m-d, H:i:s'),
            'execution_time' => number_format($executionTime, 2) . ' ms',
        ]);
    }
}
