<?php

use App\Http\Controllers\Api\BookController;

Route::apiResource('books', BookController::class);
