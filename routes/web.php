<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status'        => 'success',
        'message'       => 'API de Gerenciamento de Veículos em execução.',
        'documentation' => 'Consulte a coleção Postman para detalhes dos endpoints.'
    ]);
});
