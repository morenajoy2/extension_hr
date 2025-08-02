<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'string', 'max:20'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'birth_of_date' => ['required', 'date'],
            'school' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'max:100'],
            'role' => ['required', 'string', 'max:50'],
            'department' => ['required', 'string', 'max:100'],
            'department_team' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'group_no' => ['required', 'string', 'max:20'],
            'contact_number'=> ['required', 'string', 'max:20'],
            'gender' => ['required', 'string', 'max:50'],
            'address'=> ['nullable', 'string', 'max:255'],
            'school_address'=> ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'delete_photo' => ['nullable'],


        ];
    }
}
