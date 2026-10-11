<?php

namespace App\Http\Requests\Admin;

use App\Rules\ValidJalaliDate;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
            'date' => ['required', 'string', new ValidJalaliDate],
            'absent' => ['nullable', 'array'],
            'absent.*' => ['integer', 'distinct'],
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
            'date.required' => 'لطفاً تاریخ حضور و غیاب را وارد کنید.',
            'absent.array' => 'ساختار اطلاعات حضور و غیاب نامعتبر است.',
            'absent.*.integer' => 'شناسه دانش‌آموز ارسال‌شده نامعتبر است.',
            'absent.*.distinct' => 'شناسه دانش‌آموز به‌صورت تکراری ارسال شده است.',
        ];
    }
}
