<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [ApiController::class, 'register']);

Route::post('/login', [ApiController::class, 'login']);

Route::delete('/logout', [ApiController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/friend/{user}/add', [ApiController::class, 'addFriend'])->middleware('auth:sanctum');

Route::patch('/update/{conversation}', [ApiController::class, 'updateConversation'])->middleware('auth:sanctum');

Route::post('/send', [ApiController::class, 'sendMessage'])->middleware('auth:sanctum');

