<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait ApiResponser
{
    /**
     * Normalização de response de success
     *
     * @param [type] $data
     * @param string|null|null $message
     * @param integer $code
     * @return JsonResponse
     */
    protected function success($data, string|null $message = null, int $code = 200): JsonResponse
    {
        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            /**
             * @var AnonymousResourceCollection $paginator
             */
            $paginator = $data->resource;

            $paginationData = $paginator->toArray();

            unset($paginationData['data']);

            return response()->json(array_merge(
                [
                    'status'  => 'success',
                    'message' => $message,
                    'data'    => $data->collection
                ],
                $paginationData
            ), $code);
        }

        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * Normalização de response de error
     *
     * @param string $message
     * @param integer $code
     * @param [type] $errors
     * @return JsonResponse
     */
    protected function error(string $message, int $code, $errors = null): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }
}
