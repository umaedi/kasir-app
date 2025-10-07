<?php

namespace App\Http\Controllers\CMS;
use App\Http\Controllers\Controller;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
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
            
            return response()->json([
                'success' => true,
                'data' => $transactions,
                'message' => 'Data transaksi berhasil diambil.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store multiple transactions (for sync from POS)
     */
    public function storeBatch(Request $request): JsonResponse
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
    public function store(StoreTransactionRequest $request): JsonResponse
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

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $transaction,
                'message' => 'Data transaksi berhasil diambil.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daily sales report
     */
    public function dailyReport(Request $request): JsonResponse
    {
        try {
            $date = $request->get('date', Carbon::today()->toDateString());
            
            $report = Transaction::onDate(Carbon::parse($date))
                ->select(
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(total_amount) as total_sales'),
                    DB::raw('AVG(total_amount) as average_transaction'),
                    DB::raw('MAX(total_amount) as highest_sale'),
                    DB::raw('MIN(total_amount) as lowest_sale')
                )
                ->first();
            
            $hourlySales = Transaction::onDate(Carbon::parse($date))
                ->select(
                    DB::raw('HOUR(transaction_date) as hour'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(total_amount) as total_sales')
                )
                ->groupBy(DB::raw('HOUR(transaction_date)'))
                ->orderBy('hour')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $date,
                    'summary' => $report,
                    'hourly_sales' => $hourlySales
                ],
                'message' => 'Laporan harian berhasil diambil.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil laporan harian.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sales statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $startDate = $request->get('start_date', Carbon::today()->subDays(7)->toDateString());
            $endDate = $request->get('end_date', Carbon::today()->toDateString());
            
            $stats = Transaction::betweenDates(
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            )
            ->select(
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('AVG(total_amount) as average_sale'),
                DB::raw('MAX(total_amount) as highest_sale'),
                DB::raw('MIN(total_amount) as lowest_sale'),
                DB::raw('DATE(transaction_date) as date')
            )
            ->groupBy(DB::raw('DATE(transaction_date)'))
            ->orderBy('date')
            ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'period' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ],
                    'statistics' => $stats
                ],
                'message' => 'Statistik penjualan berhasil diambil.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik penjualan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete($id);
        return $this->success('', 'Transaksi berhasil dihapus');
    }
}