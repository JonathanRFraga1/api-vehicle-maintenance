<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Http\Resources\MaintenanceResource;
use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class MaintenanceController extends Controller
{
    use ApiResponser;

    const MAX_PAGE_SIZE = 15;

    /**
     * Função responsável por listar as manutenções realizadas pelo usuário
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = auth('api')->user()->id;

            $perPage = $request->input('per_page', self::MAX_PAGE_SIZE);
            $perPage = ($perPage > self::MAX_PAGE_SIZE) ? self::MAX_PAGE_SIZE : $perPage;

            $maintenances = QueryBuilder::for(Maintenance::class)
                ->with(['vehicle'])
                ->whereHas('vehicle', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->allowedFilters([
                    'vehicle_id',
                    'service_date',
                    'mileage',
                    'vehicle.plate'
                ])
                ->allowedSorts(['vehicle_id', 'service_date', 'mileage'])
                ->paginate($perPage);

            $maintenancesResponse = MaintenanceResource::collection($maintenances);

            return $this->success($maintenancesResponse, 'List of maintenances');
        } catch (Throwable $t) {
            return $this->error('Error on list maintenances', 500);
        }
    }

    /**
     * Função responsável por armazenar uma função realizada pelo usuário
     *
     * @param StoreMaintenanceRequest $request
     * @return JsonResponse
     */
    public function store(StoreMaintenanceRequest $request): JsonResponse
    {
        try {
            $vehicle = Vehicle::findOrFail($request->vehicle_id);
            $this->authorize('addMaintenance', $vehicle);

            $maintenance = new Maintenance($request->validated());
            $maintenance->save();

            return $this->success(
                new MaintenanceResource($maintenance),
                'Maintenance stored'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Vehicle not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on store maintenance', 500);
        }
    }

    /**
     * Função responsável por retornar os dados de uma manutenção realizada pelo usuário
     *
     * @param Maintenance $maintenance
     * @return JsonResponse
     */
    public function show(Maintenance $maintenance): JsonResponse
    {
        try {
            $this->authorize('view', $maintenance);

            return $this->success(
                new MaintenanceResource($maintenance),
                'Maintenance found'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenance not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on search maintenance', 500);
        }
    }

    /**
     * Função responsável por retornar os dados de manutenção de um veículo
     *
     * @param Request $request
     * @param Vehicle $vehicle
     * @return JsonResponse
     */
    public function byVehicle(Request $request, Vehicle $vehicle): JsonResponse
    {
        try {
            $this->authorize('view', $vehicle);

            $userId = auth('api')->user()->id;

            $perPage = $request->input('per_page', self::MAX_PAGE_SIZE);
            $perPage = ($perPage > self::MAX_PAGE_SIZE) ? self::MAX_PAGE_SIZE : $perPage;

            $maintenances = QueryBuilder::for(Maintenance::class)
                ->with(['vehicle'])
                ->where('vehicle_id', '=', $vehicle->id)
                ->whereHas('vehicle', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->allowedFilters([
                    'service_date',
                    'mileage',
                ])
                ->allowedSorts(['id', 'service_date', 'mileage'])
                ->paginate($perPage);

            $maintenancesResponse = MaintenanceResource::collection($maintenances);

            return $this->success($maintenancesResponse, 'List of maintenances');
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenances not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on search maintenances', 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance): JsonResponse
    {
        try {
            $this->authorize('update', $maintenance);

            $vehicle = Vehicle::findOrFail($request->vehicle_id);
            $this->authorize('addMaintenance', $vehicle);

            $maintenance->fill($request->validated());
            $maintenance->save();

            return $this->success(
                new MaintenanceResource($maintenance),
                'Maintenance updated'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenance not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on update maintenance', 500);
        }
    }

    /**
     * Função responsável por remover uma manutrn
     *
     * @param Maintenance $maintenance
     * @return JsonResponse
     */
    public function destroy(Maintenance $maintenance): JsonResponse
    {
        try {
            $this->authorize('delete', $maintenance);

            $maintenance->delete();

            return $this->success(null, 'Maintenance removed');
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenance not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on remove maintenance', 500);
        }
    }
}
