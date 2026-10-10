<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolClassRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'integer', 'in:10,11,12'],
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
            'title.required' => 'لطفاً عنوان کلاس را وارد کنید.',
            'title.string' => 'عنوان کلاس باید متنی باشد.',
            'title.max' => 'عنوان کلاس نمی‌تواند بیش از 255 کاراکتر باشد.',
            'grade.required' => 'لطفاً پایه تحصیلی را انتخاب کنید.',
            'grade.integer' => 'پایه تحصیلی باید عددی باشد.',
            'grade.in' => 'پایه تحصیلی باید 10، 11 یا 12 باشد.',
        ];
    }
}
