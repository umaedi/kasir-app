<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



/**
 * @property string $transaction_id
 * @property float $total_amount
 * @property float $paid_amount
 * @property float $change_amount
 * @property array $items
 * @property string $transaction_date
 */

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah menjadi false dan sesuaikan dengan auth logic jika diperlukan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_id' => 'required|string|unique:transactions,transaction_id',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'change_amount' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.category' => 'nullable|string',
            'transaction_date' => 'required|date'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'transaction_id.unique' => 'Transaction ID sudah digunakan.',
            'items.required' => 'Items transaksi harus diisi.',
            'items.*.id.required' => 'ID produk harus diisi.',
            'items.*.name.required' => 'Nama produk harus diisi.',
            'items.*.price.required' => 'Harga produk harus diisi.',
            'items.*.quantity.required' => 'Quantity produk harus diisi.',
        ];
    }
}