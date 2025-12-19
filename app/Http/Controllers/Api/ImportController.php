<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function import(
        Request $request,
        ImportService $service
    ) {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt', 'max:10240']]);

        $result = $service->import($request->file('file'));

        return response($result);
    }
}
