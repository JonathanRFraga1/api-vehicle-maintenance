<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceTypeResource;
use App\Models\ServiceType;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ServiceTypeController extends Controller
{
    use ApiResponser;

    const MAX_PAGE_SIZE = 15;

    /**
     * Função responsável por listar os tipos de serviço para uso nas manutenções
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', self::MAX_PAGE_SIZE);
            $perPage = ($perPage > self::MAX_PAGE_SIZE) ? self::MAX_PAGE_SIZE : $perPage;

            $serviceTypes = ServiceType::query()
                ->paginate($perPage);

            $serviceTypesResponse = ServiceTypeResource::collection($serviceTypes);

            return $this->success($serviceTypesResponse, 'List of service types');
        } catch (Throwable $t) {
            return $this->error('Error on list service types', 500);
        }
    }
}
