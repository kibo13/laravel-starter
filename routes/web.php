<?php

use Illuminate\Support\Facades\Route;

// Main status endpoint
Route::get('/', function () {
    return response()->json([
        'message' => 'Laravel API is running!',
        'version' => '1.0.0',
        'timestamp' => now(),
        'status' => 'ok'
    ]);
});
