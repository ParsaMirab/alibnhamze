<?php

namespace App\Http\Requests\Alibnhamze;

use App\Support\Digits;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VerifyPreRegistrationOtpRequest extends FormRequest
{
    /**
     * This form is public.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize Persian/Arabic digits typed into the OTP field.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => Digits::onlyDigits($this->input('code')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'digits:6'],
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
            'code.required' => 'لطفاً کد تأیید را وارد کنید.',
            'code.digits' => 'کد تأیید باید دقیقاً ۶ رقم باشد.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code' => 'کد تأیید',
        ];
    }
}
