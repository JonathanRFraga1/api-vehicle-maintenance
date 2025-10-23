<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceAttachmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description'     => 'required|string|max:255',
            'attachment_file' => [
                'required',
                'file',
                'mimes:jpg,png,pdf,jpeg',
                'max:5120',
            ]
        ];
    }
}
