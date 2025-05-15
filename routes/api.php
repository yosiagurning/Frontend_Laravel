<?php

use App\Http\Controllers\OfficerController;

Route::get('/sync-officers', [OfficerController::class, 'syncOfficers']);
Route::get('/officers', [OfficerController::class, 'getOfficers']);
Route::post('/officers', [OfficerController::class, 'store']);
Route::delete('/officers/{id}', [OfficerController::class, 'destroy']);