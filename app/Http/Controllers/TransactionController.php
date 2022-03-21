<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\PaymentRecord;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::query();

        if (request()->user() && request()->user()->type == 'customer') {
            $transactions->where('payer_id', request()->user()->id);
        }

        return response()->json(TransactionResource::collection($transactions->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Transaction::create($request->only(['total', 'due_on', 'vat', 'is_vat_inclusive', 'payer_id', 'category_id', 'sub_category_id']));

        return response()->json([
            "status" => 'You have successfully created a Transaction!',
            "data" => new TransactionResource($data)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return response()->json(
            new TransactionResource(
                $transaction->load(['records', 'category', 'subCategory', 'payer'])
            )
        );
    }

    /**
     * Get Payments belongs to selected transactions
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function getPayments($id)
    {
        $transaction = Transaction::where('id', $id);

        if (request()->user() && request()->user()->type == 'customer') {
            $transaction->where('payer_id', request()->user()->id);
        }

        return response()->json($transaction->firstOrFail()->records);
    }

    /**
     * Get Payments belongs to selected transactions
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function addPayment(Request $request, $id)
    {
        $attr = array_merge($request->only(['amount', 'paid_on', 'details']), ["transaction_id" => $id]);
        $data = PaymentRecord::create($attr);

        return response()->json([
            "status" => 'You have successfully created a Transaction Payment!',
            "data" => $data
        ]);
    }

    /**
     * Remove Payments
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function removePayment(Request $request, $id, $pid)
    {
         Transaction::findOrFail($id)->records()->findOrFail($pid)->delete();

        return response()->json([
            "status" => 'You have successfully deleted a Payment!'
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $transaction->update($request->only(['total', 'due_on', 'vat', 'is_vat_inclusive', 'payer_id', 'category_id', 'sub_category_id']));

        return response()->json([
            "status" => 'You have successfully updated a Transaction!',
            "data" => new TransactionResource($transaction)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json([
            "status" => 'You have successfully deleted a Transaction!'
        ]);
    }
}
