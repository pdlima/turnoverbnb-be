<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Models\Transaction;
use App\Http\Resources\TransactionsResource;
use Carbon\Carbon;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::where('status', 'PENDING')->get();
        return TransactionsResource::collection($transactions);
    }

    public function show($id)
    {
        return new TransactionsResource(Transaction::findOrFail($id));
    }

    public function store(Request $request)
    {
        $toValidate = [
            'description' => 'required',
            'type' => 'required',
            'value' => 'required|numeric',
        ];

        if ($request->type == 'INCOME') {
            $toValidate['image'] = 'required|file';
        }

        $this->validate($request, $toValidate);

        $user = $request->user();

        $user->incomes = $user->transactions()
            ->where(['type' => 'INCOME', 'status' => 'ACCEPTED'])
            ->sum('value');

        $user->expenses = $user->transactions()->where('type', 'PURCHASE')->sum('value');

        $user->balance = $user->incomes - $user->expenses;

        if ($request->type == 'PURCHASE') {
            if ($user->balance < $request->value) return response(['message' => 'Not enough funds.'], 400);
        }

        $transaction = new Transaction;

        $transaction->user_id = $user->id;
        $transaction->description = $request->description;
        $transaction->type = $request->type;
        $transaction->value = $request->value;
        $transaction->date = $request->date;

        if ($request->type == 'INCOME') {
            $name = uniqid(date('HisYmd'));

            $extension = $request->image->extension();
            $nameFile = "{$name}.{$extension}";

            $s3 = Storage::disk('s3');

            $path = $s3->put('checks', $request->image);

            $transaction->image = $s3->url($path);
            $transaction->status = 'PENDING';
            $transaction->date = Carbon::now()->format('d/m/Y, g:i A');
        }

        $transaction->save();

        return [true];
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['status' => 'required']);

        if ($request->user()->role == 0) {
            $transaction = Transaction::find($id);
            $transaction->status = $request->status;
            $transaction->save();
        }

        return [true];
    }
}