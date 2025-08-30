<?php

use App\Http\Controllers\Api\V1\TransferController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/transfer', TransferController::class)->name('transfer');
