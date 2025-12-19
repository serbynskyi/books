<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ImportController;

Route::apiResource('books', BookController::class);
Route::post('import', [ImportController::class, 'import']);
