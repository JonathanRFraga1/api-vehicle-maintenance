<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceAttachmentRequest;
use App\Http\Resources\MaintenanceAttachmentResource;
use App\Models\Maintenance;
use App\Models\MaintenanceAttachment;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class MaintenanceAttachmentController extends Controller
{
    const MAX_PAGE_SIZE = 15;

    /**
     * Função responsável por listar os anexos de uma manutenção realizada em um veículo de um usuário
     *
     * @param Request $request
     * @param Maintenance $maintenance
     * @return JsonResponse
     */
    public function index(Request $request, Maintenance $maintenance): JsonResponse
    {
        try {
            $userId = auth('api')->user()->id;

            $perPage = $request->input('per_page', self::MAX_PAGE_SIZE);
            $perPage = ($perPage > self::MAX_PAGE_SIZE) ? self::MAX_PAGE_SIZE : $perPage;

            $maintenanceAttachments = MaintenanceAttachment::query()
                ->with(['maintenance.vehicle'])
                ->where('maintenance_id', '=', $maintenance->id)
                ->whereHas('maintenance.vehicle', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->paginate($perPage);

            $maintenanceAttachmentsResponse = MaintenanceAttachmentResource::collection($maintenanceAttachments);

            return $this->success($maintenanceAttachmentsResponse, 'List of maintenance attachments');
        } catch (Throwable $t) {
            $this->logError('Error on list maintenance attachments', $t);
            return $this->error('Error on list maintenance attachments', 500);
        }
    }

    /**
     * Função responsável por adicionar um anexo a um resgistro de manutenção realizado em um veículo de um usuário
     *
     * @param StoreMaintenanceAttachmentRequest $request
     * @param Maintenance $maintenance
     * @return JsonResponse
     */
    public function store(StoreMaintenanceAttachmentRequest $request, Maintenance $maintenance): JsonResponse
    {
        try {
            $this->authorize('addAttachment', $maintenance);

            $file = $request->file('attachment_file');
            $data = $request->validated();

            $path = $file->store('attachments/' . $maintenance->id, 'local');

            $attributes = [
                'maintenance_id' => $maintenance->id,
                'description'    => $data['description'],
                'file_name'      => $file->getClientOriginalName(),
                'file_path'      => $path,
                'mime_type'      => $file->getClientMimeType(),
                'file_size'      => $file->getSize(),
            ];

            $maintenanceAttachment = MaintenanceAttachment::create($attributes);

            return $this->success(
                new MaintenanceAttachmentResource($maintenanceAttachment),
                'Maintenance attachment stored'
            );
        } catch (ModelNotFoundException|NotFoundHttpException $e) {
            return $this->error('Maintenance not found', 404);
        } catch (Throwable $t) {
            $this->logError('Error on store maintenance attachment', $t);
            return $this->error('Error on store maintenance attachment', 500);
        }
    }

    /**
     * Função responsável por retornar o anexo enviado pelo usuário
     *
     * @param MaintenanceAttachment $attachment
     * @return void
     */
    public function show(MaintenanceAttachment $attachment)
    {
        try {
            $this->authorize('view', $attachment);

            if (!Storage::disk('local')->exists($attachment->file_path)) {
                abort(404, 'File not found.');
            }

            return Storage::disk('local')->download(
                $attachment->file_path,
                $attachment->file_name
            );

        } catch (ModelNotFoundException $e) {
            abort(404, 'Attachment not found.');
        } catch (Throwable $t) {
            $this->logError('Error on show maintenance attachment', $t);
            abort(500, 'Could not retrieve file.');
        }
    }


    /**
     * Função responsável por remover um anexo vinculado a uma manutenção de um veículo de um usuário
     *
     * @param MaintenanceAttachment $attachment
     * @return JsonResponse
     */
    public function destroy(MaintenanceAttachment $attachment): JsonResponse
    {
        try {
            $this->authorize('delete', $attachment);

            if (!Storage::disk('local')->exists($attachment->file_path)) {
                throw new Exception('File not found');
            }

            Storage::disk('local')->delete($attachment->file_path);

            $attachment->delete();

            return $this->success(null, 'Attachment removed');
        } catch (ModelNotFoundException $e) {
            return $this->error('Maintenance attachment not found', 404);
        } catch (Throwable $t) {
            $this->logError('Error on remove maintenance attachment', $t);
            return $this->error('Error on remove maintenance attachment', 500);
        }
    }
}
