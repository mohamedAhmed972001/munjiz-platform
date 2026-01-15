<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponseTrait
{
    protected function success($data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        $payload = [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($payload, $status);
    }

    protected function error(string $message = 'Error', array $errors = [], int $status = 400): JsonResponse
    {
        $payload = [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($payload, $status);
    }

    protected function paginated(LengthAwarePaginator $paginator, $resourceCollection = null): JsonResponse
    {
        $data = $resourceCollection ? $resourceCollection : $paginator->items();

        $meta = [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];

        $payload = [
            'status' => 'success',
            'message' => 'OK',
            'data' => $data,
            'meta' => $meta,
        ];

        return response()->json($payload, 200);
    }
}
