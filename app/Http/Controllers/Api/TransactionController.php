<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Transaction\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $result = Transaction::with('category')->get();
        // Get collection of resource transactions
        $result = TransactionResource::collection($result);

        // Return result
        return response()->json($result, Response::HTTP_ACCEPTED);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        // Copy data in array
        $data = $request->validated();

        // Create transaction in database
        // $transaction = Transaction::create($data);
        $transaction = auth()->user()->transactions()->create($data);

        // Create resource
        $transaction = new TransactionResource($transaction);

        // Return result
        return response()->json($transaction, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find
        $transaction = Transaction::find($id);
        if (!$transaction)
            return response()->json(['message' => "L'information demandé n'existe pas"], Response::HTTP_NOT_FOUND);

        // Create resource
        $result = new TransactionResource($transaction);

        // Return result
        return response()->json($result, Response::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, $id)
    {
        // Copy data in array
        $data = $request->validated();

        // Find
        $transaction = Transaction::find($id);
        if (!$transaction)
            return response()->json(['message' => "L'information demandé n'existe pas"], Response::HTTP_NOT_FOUND);

        // Update
        $transaction->update($data);

        // Create resource
        $result = new TransactionResource($transaction);

        // Return result
        return response()->json($result, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find
        $transaction = Transaction::find($id);
        if (!$transaction)
            return response()->json(['message' => "L'information demandé n'existe pas"], Response::HTTP_NOT_FOUND);

        // Update
        $transaction->delete();

        // Return result
        return response()->json(["message" => "Suppression reussie"], Response::HTTP_OK);
    }
}
