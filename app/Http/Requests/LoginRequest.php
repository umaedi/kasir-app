<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'whatsapp' => 'required|string|max:15',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'whatsapp.required' => 'Nomor WhatsApp harus diisi.',
            'whatsapp.max' => 'Nomor WhatsApp maksimal 15 karakter.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ];
    }

    /**
     * Get the validated data from the request.
     */
    public function getCredentials()
    {
        $whatsapp = $this->validated('whatsapp');
        
        // Format WhatsApp number to ensure consistency
        $whatsapp = $this->formatWhatsappNumber($whatsapp);
        
        return [
            'whatsapp' => $whatsapp,
            'password' => $this->validated('password'),
        ];
    }

    /**
     * Format WhatsApp number to standard format
     */
    private function formatWhatsappNumber($number)
    {
        // Remove any non-digit characters
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // If number starts with 0, replace with 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }
        
        // If number doesn't start with country code, add 62
        if (substr($number, 0, 2) !== '62') {
            $number = '62' . $number;
        }
        
        return $number;
    }
}