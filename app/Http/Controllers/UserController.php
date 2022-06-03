<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role == 0) $user->scope = 'admin';

        $user->incomes = $user->transactions()
            ->where(['type' => 'INCOME', 'status' => 'ACCEPTED'])
            ->sum('value');
        $user->expenses = $user->transactions()->where('type', 'PURCHASE')->sum('value');

        $user->balance = $user->incomes - $user->expenses;

        return new UserResource($user);
    }
}