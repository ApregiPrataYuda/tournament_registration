<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatedAddRegisterTeam extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        'team_name' => [
            'required',
            'string',
            'min:4',                   
            'max:15',                 
            'regex:/^[A-Za-z0-9_]+$/', 
        ],
     
        'full_name_member' => [
            'required',
            'string',
            'regex:/^[A-Za-z ]+$/', 
        ],

      'phone_number_member' => [
        'required',
        'digits_between:7,14', 
       ],
        'captain_gender' => ['required', 'in:male,female'],
        'gender_member' => ['required', 'in:male,female'],
    ];
    }

    public function messages(): array
    {
         return [
        'team_name.required' => 'Nama tim wajib diisi.',
        'team_name.min' => 'Nama tim minimal 4 karakter.',
        'team_name.max' => 'Nama tim maksimal 15 karakter.',
        'team_name.regex' => 'Nama tim hanya boleh huruf, angka, dan underscore (_).',


        'full_name_member.required' => 'Nama anggota wajib diisi.',
        'full_name_member.regex' => 'Nama anggota hanya boleh huruf dan spasi.',



       'phone_number_member.required' => 'Nomor telepon anggota wajib diisi.',
       'phone_number_member.digits_between' => 'Nomor telepon anggota harus 7-14 digit dan hanya angka.',

        'captain_gender.required' => 'Jenis kelamin Captain wajib diisi.',
        'captain_gender.in' => 'Jenis kelamin Captain harus male atau female.',

        'gender_member.required' => 'Jenis kelamin anggota wajib diisi.',
        'gender_member.in' => 'Jenis kelamin anggota harus male atau female.',
    ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'team_name'    => $this->has('team_name') ? trim($this->input('team_name')) : null,
        ]);
    }

}
