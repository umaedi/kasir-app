<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $product = Product::create($request->validated());
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Produk berhasil ditambahkan.'
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $product,
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $product->update($request->validated());
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Produk berhasil diperbarui.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        DB::beginTransaction();
        
        try {
            $product->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product categories.
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = Product::select('category')
                                ->distinct()
                                ->orderBy('category')
                                ->pluck('category');
            
            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Kategori produk berhasil diambil.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil kategori produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product stock.
     */
    public function updateStock(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:add,subtract,set'
        ]);
        
        DB::beginTransaction();
        
        try {
            $quantity = $request->quantity;
            $type = $request->type;
            
            switch ($type) {
                case 'add':
                    $product->updateStock($quantity);
                    break;
                case 'subtract':
                    $product->updateStock(-$quantity);
                    break;
                case 'set':
                    $product->stock = $quantity;
                    $product->save();
                    break;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $product->fresh(),
                'message' => 'Stok produk berhasil diperbarui.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui stok produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get low stock products.
     */
    public function lowStock(): JsonResponse
    {
        try {
            $lowStockProducts = Product::whereRaw('stock <= min_stock')
                                    ->where('is_active', true)
                                    ->orderBy('stock')
                                    ->get();
            
            return response()->json([
                'success' => true,
                'data' => $lowStockProducts,
                'message' => 'Produk dengan stok rendah berhasil diambil.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil produk stok rendah.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}