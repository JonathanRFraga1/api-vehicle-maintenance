<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceTypeResource;
use App\Models\ServiceType;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
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

            $serviceTypes = QueryBuilder::for(ServiceType::class)
                ->allowedFilters([
                    'description',
                    'identifier',
                ])
                ->paginate($perPage);

            $serviceTypesResponse = ServiceTypeResource::collection($serviceTypes);

            return $this->success($serviceTypesResponse, 'List of service types');
        } catch (Throwable $t) {
            $this->logError('Error on list service types', $t);
            return $this->error('Error on list service types', 500);
        }
    }
}
