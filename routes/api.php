<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionsController;


Route::middleware(['auth:sanctum'])->get('/user', [UserController::class, 'index']);

Route::middleware(['auth:sanctum'])->get('/transactions', [TransactionsController::class, 'index']);
Route::middleware(['auth:sanctum'])->post('/transactions', [TransactionsController::class, 'store']);
Route::middleware(['auth:sanctum'])->get('/transactions/{id}', [TransactionsController::class, 'show']);
Route::middleware(['auth:sanctum'])->patch('/transactions/{id}', [TransactionsController::class, 'update']);