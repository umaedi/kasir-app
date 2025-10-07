<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController
{

        /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = Transaction::query();
            
            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->betweenDates($startDate, $endDate);
            }
            
            // Filter by specific date
            if ($request->has('date')) {
                $query->onDate(Carbon::parse($request->date));
            }
            
            // Search by transaction ID
            if ($request->has('search')) {
                $query->where('transaction_id', 'like', '%' . $request->search . '%');
            }
            
            // Order by latest first
            $transactions = $query->orderBy('transaction_date', 'desc')
                                ->paginate($request->get('per_page', 15));
            
            return view('cms.transactions._data_table', $transactions);
        }
        return view('cms.transactions.index', [
            'title' => 'CMS | Transactions',
        ]);
    }


    /**
     * Store multiple transactions (for sync from POS)
     */
    public function storeBatch(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $transactions = $request->input('transactions', []);
            $savedTransactions = [];
            $errors = [];
            
            foreach ($transactions as $index => $transactionData) {
                try {
                    $validator = validator($transactionData, [
                        'transaction_id' => 'required|string|unique:transactions,transaction_id',
                        'total_amount' => 'required|numeric|min:0',
                        'paid_amount' => 'required|numeric|min:0',
                        'change_amount' => 'required|numeric|min:0',
                        'items' => 'required|array',
                        'transaction_date' => 'required|date'
                    ]);
                    
                    if ($validator->fails()) {
                        $errors[] = [
                            'index' => $index,
                            'transaction_id' => $transactionData['transaction_id'] ?? 'unknown',
                            'errors' => $validator->errors()->toArray()
                        ];
                        continue;
                    }
                    
                    $transaction = Transaction::create([
                        'transaction_id' => $transactionData['transaction_id'],
                        'total_amount' => $transactionData['total_amount'],
                        'paid_amount' => $transactionData['paid_amount'],
                        'change_amount' => $transactionData['change_amount'],
                        'items' => $transactionData['items'],
                        'transaction_date' => Carbon::parse($transactionData['transaction_date']),
                        'user_id' => $request->user()->id // Tambahkan user_id
                    ]);
                    
                    // Create transaction items
                    if (isset($transactionData['items']) && is_array($transactionData['items'])) {
                        $transaction->createItemsFromCart($transactionData['items']);
                    }

                    $savedTransactions[] = $transaction;
                    
                } catch (\Exception $e) {
                    $errors[] = [
                        'index' => $index,
                        'transaction_id' => $transactionData['transaction_id'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ];
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Proses sync transaksi selesai.',
                'saved_count' => count($savedTransactions),
                'error_count' => count($errors),
                'saved_transactions' => $savedTransactions,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(StoreTransactionRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $transaction = Transaction::create([
                'transaction_id' => $request->transaction_id,
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->change_amount,
                'items' => $request->items,
                'transaction_date' => Carbon::parse($request->transaction_date)
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $transaction,
                'message' => 'Transaksi berhasil disimpan.'
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
