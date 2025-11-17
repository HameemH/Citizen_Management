<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FakeNidStatusController;

Route::get('/nid/{nidNumber}', [FakeNidStatusController::class, 'show'])
    ->where('nidNumber', '\\d{10}')
    ->name('api.nid.status');
