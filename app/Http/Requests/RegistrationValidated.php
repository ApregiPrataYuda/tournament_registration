<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class RegistrationValidated extends FormRequest
{
     /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
             'username' => ['required', 'string', 'max:50', 'alpha_num', 'unique:users,username'],
            //  'password' => ['required', 'string', 'min:8', 'max:16', 'confirmed'],
             'password' => ['required', 'string', 'min:8', 'max:16'],
            'phone_number' => ['required', 'digits_between:8,20'],
            'role' => ['nullable', 'integer'],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'fullname.string' => 'Nama lengkap harus berupa teks.',
            'fullname.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah digunakan.',

            'username.required' => 'Username wajib diisi.',
            'username.alpha_num' => 'Username harus berupa huruf dan angka.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.unique' => 'Username sudah digunakan ganti dengan username yang unik.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 16 karakter.',
            // 'password.confirmed' => 'Password konfirmasi tidak sesuai.',

           
            
            'role.integer' => 'Role harus berupa angka.',

            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.digits_between' => 'Nomor telepon harus angka dan panjang antara 8 sampai 20 digit.',
        ];
    }

    /**
     * Handle failed validation
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors(),
        ], 400));
    }

    /**
     * Trim input
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->name ? trim($this->name) : null,
            'username' => $this->username ? trim($this->username) : null,
            'email' => $this->email ? trim($this->email) : null,
            'phone_number' => $this->phone_number ? trim($this->phone_number) : null,
        ]);
    }
}
