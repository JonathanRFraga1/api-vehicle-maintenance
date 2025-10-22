<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class VehicleController extends Controller
{
    use ApiResponser;

    /**
     * Função responsável por listar os veículos de um usuário
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = auth('api')->user()->id;

            $perPage = $request->input('per_page', 15);
            $perPage = ($perPage > 15) ? 15 : $perPage;

            $vehicles = Vehicle::query()
                ->where('user_id', '=', $userId)
                ->paginate($perPage);

            $vehiclesResponse = VehicleResource::collection($vehicles);

            return $this->success($vehiclesResponse, 'List of vehicles');
        } catch (Throwable $t) {
            return $this->error('Error on list vehicles', 500);
        }
    }

    /**
     * Função responsável por armazenar um novo veículo de um usuário
     *
     * @param StoreVehicleRequest $request
     * @return JsonResponse
     */
    public function store(StoreVehicleRequest $request): JsonResponse
    {
        try {
            $vehicle = new Vehicle($request->validated());
            $vehicle->user_id = auth('api')->user()->id;
            $vehicle->save();

            return $this->success(
                new VehicleResource($vehicle),
                'Vehicle stored'
            );
        } catch (Throwable $t) {
            return $this->error('Error on store vehicle', 500);
        }
    }

    /**
     * Função responsável por retornar os dados de um veículo de um usuário
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(Vehicle $vehicle): JsonResponse
    {
        try {
            $this->authorize('view', $vehicle);

            return $this->success(
                new VehicleResource($vehicle),
                'Vehicle found'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Vehicle not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on search vehicle', 500);
        }
    }

    /**
     * Função responsável por atualizar um veículo de um usuário
     *
     * @param UpdateVehicleRequest $request
     * @param Vehicle $vehicle
     * @return JsonResponse
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        try {
            $this->authorize('update', $vehicle);

            $vehicle->fill($request->validated());
            $vehicle->save();

            return $this->success(
                new VehicleResource($vehicle),
                'Vehicle updated'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Vehicle not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on update vehicle', 500);
        }
    }

    /**
     * Função responsável por remover um veículo de um usuário
     *
     * @param Vehicle $vehicle
     * @return JsonResponse
     */
    public function destroy(Vehicle $vehicle): JsonResponse
    {
        try {
            $this->authorize('delete', $vehicle);

            $vehicle->delete();

            return $this->success(null, 'Vehicle removed');
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Vehicle not found', 404);
        } catch (Throwable $t) {
            return $this->error('Error on remove vehicle', 500);
        }
    }
}
