<?php

namespace App\Http\Controllers\CMS;
use App\Http\Controllers\Controller;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesReportController extends Controller
{
    /**
     * Get sales report by product
     */
    public function salesByProduct(Request $request)
    {
        if($request->ajax()) {
            try {
                $startDate = $request->get('start_date', Carbon::today()->subDays(30)->toDateString());
                $endDate = $request->get('end_date', Carbon::today()->toDateString());
                $productId = $request->get('product_id');
                $category = $request->get('category');
            
                $query = TransactionItem::with('product')
                    ->betweenDates(
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    )
                    ->select([
                        'product_id',
                        'product_name',
                        DB::raw('SUM(quantity) as total_quantity'),
                        DB::raw('SUM(subtotal) as total_sales'),
                        DB::raw('AVG(product_price) as average_price')
                    ])
                    ->groupBy('product_id', 'product_name')
                    ->orderBy('total_sales', 'desc');

                // Filter by product
                if ($productId) {
                    $query->where('product_id', $productId);
                }

                // Filter by category
                if ($category) {
                    $query->whereHas('product', function ($q) use ($category) {
                        $q->where('category', $category);
                    });
                }

                $salesData = $query->get();

                $report = $salesData->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'total_quantity' => (int) $item->total_quantity,
                        'total_sales' => (float) $item->total_sales,
                        'average_price' => (float) $item->average_price,
                        'product' => $item->product ? [
                            'category' => $item->product->category,
                            'image' => $item->product->image,
                            'current_price' => $item->product->price
                        ] : null
                    ];
                });

                $summary = [
                    'period' => [
                        'start_date' => $startDate,
                        'end_date' => $endDate
                    ],
                    'total_products' => $salesData->count(),
                    'total_quantity' => $salesData->sum('total_quantity'),
                    'total_sales' => $salesData->sum('total_sales'),
                    'average_sale_per_product' => $salesData->avg('total_sales')
                ];

                if($request->load == 'summary') {
                    return $this->success($summary, 'Data summary');
                }else {
                    return view('cms.sales._data_table', compact('report'));
                }
            } catch (\Exception $e) {
                Log::error('Sales by product error: ' . $e->getMessage());
                return $this->error('Gagal mengambil laporan penjualan.');
            }
        }

        return view('cms.sales.index');

    }

    /**
     * Get sales report by category
     */
    public function salesByCategory(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::today()->subDays(30)->toDateString());
            $endDate = $request->get('end_date', Carbon::today()->toDateString());

            $salesData = TransactionItem::betweenDates(
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                )
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->select([
                    'products.category',
                    DB::raw('SUM(quantity) as total_quantity'),
                    DB::raw('SUM(subtotal) as total_sales'),
                    DB::raw('COUNT(DISTINCT product_id) as total_products')
                ])
                ->groupBy('products.category')
                ->orderBy('total_sales', 'desc')
                ->get();

            $report = $salesData->map(function ($item) {
                return [
                    'category' => $item->category,
                    'total_quantity' => (int) $item->total_quantity,
                    'total_sales' => (float) $item->total_sales,
                    'total_products' => (int) $item->total_products,
                    'average_sale_per_product' => (float) $item->total_sales / $item->total_products
                ];
            });

            $summary = [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ],
                'total_categories' => $salesData->count(),
                'total_quantity' => $salesData->sum('total_quantity'),
                'total_sales' => $salesData->sum('total_sales')
            ];

            return $this->success([
                'summary' => $summary,
                'categories' => $report
            ], 'Laporan penjualan per kategori berhasil diambil.');

        } catch (\Exception $e) {
            Log::error('Sales by category error: ' . $e->getMessage());
            return $this->error('Gagal mengambil laporan penjualan per kategori.');
        }
    }

    /**
     * Get daily sales trend for a product
     */
    public function productSalesTrend(Request $request, $productId)
    {
        try {
            $days = $request->get('days', 30);
            $startDate = Carbon::today()->subDays($days);
            $endDate = Carbon::today();

            $trendData = TransactionItem::where('product_id', $productId)
                ->betweenDates($startDate, $endDate)
                ->select([
                    DB::raw('DATE(transaction_items.created_at) as date'),
                    DB::raw('SUM(quantity) as daily_quantity'),
                    DB::raw('SUM(subtotal) as daily_sales')
                ])
                ->groupBy(DB::raw('DATE(transaction_items.created_at)'))
                ->orderBy('date')
                ->get();

            $product = Product::find($productId);

            return $this->success([
                'product' => $product ? [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'current_price' => $product->price
                ] : null,
                'period' => [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'days' => $days
                ],
                'trend' => $trendData,
                'summary' => [
                    'total_quantity' => $trendData->sum('daily_quantity'),
                    'total_sales' => $trendData->sum('daily_sales'),
                    'average_daily_sales' => $trendData->avg('daily_sales')
                ]
            ], 'Trend penjualan produk berhasil diambil.');

        } catch (\Exception $e) {
            Log::error('Product sales trend error: ' . $e->getMessage());
            return $this->error('Gagal mengambil trend penjualan produk.');
        }
    }

    /**
     * Get top selling products
     */
    public function topSellingProducts(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $days = $request->get('days', 30);

            $startDate = Carbon::today()->subDays($days);
            $endDate = Carbon::today();

            $topProducts = TransactionItem::with('product')
                ->betweenDates($startDate, $endDate)
                ->select([
                    'product_id',
                    'product_name',
                    DB::raw('SUM(quantity) as total_quantity'),
                    DB::raw('SUM(subtotal) as total_sales')
                ])
                ->groupBy('product_id', 'product_name')
                ->orderBy('total_sales', 'desc')
                ->limit($limit)
                ->get();

            return $this->success([
                'period' => [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'days' => $days
                ],
                'top_products' => $topProducts->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'total_quantity' => (int) $item->total_quantity,
                        'total_sales' => (float) $item->total_sales,
                        'product' => $item->product ? [
                            'category' => $item->product->category,
                            'image' => $item->product->image
                        ] : null
                    ];
                })
            ], 'Data produk terlaris berhasil diambil.');

        } catch (\Exception $e) {
            Log::error('Top selling products error: ' . $e->getMessage());
            return $this->error('Gagal mengambil data produk terlaris.');
        }
    }
}