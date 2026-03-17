<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    public function success(mixed $data = null, string $message = '', int $status = 200, array $extra = []): JsonResponse
    {

        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data->resource instanceof LengthAwarePaginator) {
            $response['data'] = $data->items();
            $response['meta'] = [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
            ];
        } else {
            $response['data'] = $data;
        }

        $response = array_merge($response, $extra);

        return response()->json($response, $status);
    }

    public function fail(string $message = '', int $status = 200, array $extra = []): JsonResponse
    {

        $response = [
            'success' => false,
            'data' => null,
            'message' => $message,
            'errors' => null,
        ];

        $response = array_merge($response, $extra);

        return response()->json($response, $status);
    }
}
