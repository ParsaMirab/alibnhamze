<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClassTeacherRequest extends FormRequest
{
    /**
     * Authorization is enforced in the controller through the SchoolClassPolicy.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'teacher_id' => ['required', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'teacher_id.required' => 'لطفاً معلم را انتخاب کنید.',
            'teacher_id.exists' => 'معلم انتخابی وجود ندارد.',
            'subject.required' => 'لطفاً درس را وارد کنید.',
            'subject.string' => 'درس باید متنی باشد.',
            'subject.max' => 'درس نمی‌تواند بیش از 255 کاراکتر باشد.',
        ];
    }
}
