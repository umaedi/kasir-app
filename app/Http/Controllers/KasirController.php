<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class KasirController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if($request->ajax()) {
            try {
            $query = Product::query();
            
            // Filter by category
            if ($request->has('category')) {
                $query->byCategory($request->category);
            }
            
            // Filter by availability
            if ($request->has('available') && $request->available == 'true') {
                $query->available();
            }
            
            // Filter by active status
            if ($request->has('active') && $request->active == 'true') {
                $query->active();
            }
            
            // Search products
            if ($request->has('search')) {
                $query->search($request->search);
            }
            
            // Filter low stock
            if ($request->has('low_stock') && $request->low_stock == 'true') {
                $query->whereRaw('stock <= min_stock');
            }
            
            $products = $query->orderBy('name')
                            ->paginate($request->get('per_page', 20));
            
            return response()->json([
                'success' => true,
                'data' => $products,
                'message' => 'Data produk berhasil diambil.'
            ]);
            
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data produk.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        return view('kasir.index',['title' => 'Aplikasi kasir POS']);
    }
}
