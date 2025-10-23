<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceAlertRequest;
use App\Http\Requests\UpdateMaintenanceAlertRequest;
use App\Http\Resources\MaintenanceAlertResource;
use App\Models\MaintenanceAlert;
use App\Models\Vehicle;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class MaintenanceAlertController extends Controller
{
    use ApiResponser;

    const MAX_PAGE_SIZE = 15;

     /**
     * Função responsável por listar os alertas de manutenção cadastrados pelo usuário
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

            $maintenanceAlerts = MaintenanceAlert::query()
                ->with(['vehicle'])
                ->whereHas('vehicle', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->paginate($perPage);

            $maintenanceAlertsResponse = MaintenanceAlertResource::collection($maintenanceAlerts);

            return $this->success($maintenanceAlertsResponse, 'List of maintenance alerts');
        } catch (Throwable $t) {
            return $this->error('Error on list maintenance alerts', 500);
        }
    }

    /**
     * Função responsável por armazenar um alerta de manutenção para um veículo do usuário
     *
     * @param StoreMaintenanceAlertRequest $request
     * @return JsonResponse
     */
    public function store(StoreMaintenanceAlertRequest $request): JsonResponse
    {
        try {
            $vehicle = Vehicle::findOrFail($request->vehicle_id);
            $this->authorize('addMaintenance', $vehicle);

            $maintenanceAlert = new MaintenanceAlert($request->validated());
            $maintenanceAlert->status = 'waiting_send';
            $maintenanceAlert->save();

            return $this->success(
                new MaintenanceAlertResource($maintenanceAlert),
                'Maintenance alert stored'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Vehicle not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on store maintenance alert', 500);
        }
    }

    /**
     * Função responsável por retornar os dados de um alerta de manutenção adicionado pelo usuário
     *
     * @param MaintenanceAlert $maintenanceAlert
     * @return JsonResponse
     */
    public function show(MaintenanceAlert $maintenanceAlert): JsonResponse
    {
        try {
            $this->authorize('view', $maintenanceAlert);

            return $this->success(
                new MaintenanceAlertResource($maintenanceAlert),
                'Maintenance alert found'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenance alert not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on search maintenance alert', 500);
        }
    }

    /**
     * Função responsável por retornar os alertas de manutenção de um veículo
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

            $maintenanceAlerts = MaintenanceAlert::query()
                ->with(['vehicle'])
                ->where('vehicle_id', '=', $vehicle->id)
                ->whereHas('vehicle', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->paginate($perPage);

            $maintenanceAlertsResponse = MaintenanceAlertResource::collection($maintenanceAlerts);

            return $this->success($maintenanceAlertsResponse, 'List of maintenance alerts');
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenance alerts not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on search maintenance alerts', 500);
        }
    }


    /**
     * Função responsável por atualizar os dados de um alerta de manutenção cadastrado pelo usuário
     *
     * @param UpdateMaintenanceAlertRequest $request
     * @param MaintenanceAlert $maintenanceAlert
     * @return JsonResponse
     */
    public function update(UpdateMaintenanceAlertRequest $request, MaintenanceAlert $maintenanceAlert): JsonResponse
    {
        try {
            $this->authorize('update', $maintenanceAlert);

            $vehicle = Vehicle::findOrFail($request->vehicle_id);
            $this->authorize('addMaintenance', $vehicle);

            $maintenanceAlert->fill($request->validated());
            $maintenanceAlert->save();

            return $this->success(
                new MaintenanceAlertResource($maintenanceAlert),
                'Maintenance alert updated'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenance alert not found', 404);
        } catch (Throwable $t) {
            dd($t);
            return $this->error('Error on update maintenance alert', 500);
        }
    }

    /**
     * Função responsável por remover um alerta de manutenção cadastrado pelo usuário
     *
     * @param MaintenanceAlert $maintenanceAlert
     * @return JsonResponse
     */
    public function destroy(MaintenanceAlert $maintenanceAlert): JsonResponse
    {
        try {
            $this->authorize('delete', $maintenanceAlert);

            $maintenanceAlert->delete();

            return $this->success(null, 'Maintenance alert removed');
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenance alert not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on remove maintenance alert', 500);
        }
    }
}
