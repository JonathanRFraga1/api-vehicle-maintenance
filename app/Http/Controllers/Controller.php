<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ApiResponser, ValidatesRequests;

    /**
     * @param string $message
     * @param Throwable $exception
     * @return void
     */
    protected function logError(string $message, Throwable $exception): void
    {
        $context = [
            'error_message' => $exception->getMessage(),
            'file'          => $exception->getFile(),
            'line'          => $exception->getLine(),
            'url'           => request()->fullUrl(),
            'method'        => request()->method(),
            'user_id'       => Auth::check() ? Auth::id() : 'guest',
            'ip'            => request()->ip()
        ];

        $channel = config('logging.channels.api') ? 'api' : 'daily';
        Log::channel($channel)->error($message, $context);
    }
}
