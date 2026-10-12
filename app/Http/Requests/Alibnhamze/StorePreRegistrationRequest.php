<?php

namespace App\Http\Requests\Alibnhamze;

use App\Models\PreRegistration;
use App\Rules\IranianMobile;
use App\Rules\IranianNationalCode;
use App\Support\Digits;
use App\Support\IranianPhone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreRegistrationRequest extends FormRequest
{
    /**
     * This form is public.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize the submitted values before validating them so Persian
     * digits, separators and international phone prefixes are accepted.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'student_full_name' => $this->cleanText($this->input('student_full_name')),
            'father_name' => $this->cleanText($this->input('father_name')),
            'mother_full_name' => $this->cleanText($this->input('mother_full_name')),
            'national_code' => Digits::onlyDigits($this->input('national_code')),
            'previous_year_average' => $this->cleanDecimal($this->input('previous_year_average')),
            'previous_year_discipline_score' => $this->cleanDecimal($this->input('previous_year_discipline_score')),
            'phone_number' => IranianPhone::normalize($this->input('phone_number')),
        ]);

        if (is_scalar($this->input('grade'))) {
            $this->merge([
                'grade' => Digits::onlyDigits($this->input('grade')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'student_full_name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'mother_full_name' => ['required', 'string', 'max:255'],

            'national_code' => [
                'required',
                'string',
                new IranianNationalCode,
                Rule::unique('pre_registrations', 'national_code'),
            ],

            'grade' => ['required', 'integer', 'in:'.implode(',', PreRegistration::grades())],

            'previous_year_average' => ['required', 'numeric', 'between:0,20', 'decimal:0,2'],
            'previous_year_discipline_score' => ['required', 'numeric', 'between:0,20', 'decimal:0,2'],

            'phone_number' => ['required', 'string', new IranianMobile],
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
            'student_full_name.required' => 'لطفاً نام و نام خانوادگی دانش‌آموز را وارد کنید.',
            'student_full_name.max' => 'نام دانش‌آموز نمی‌تواند بیشتر از ۲۵۵ نویسه باشد.',
            'student_full_name.string' => 'نام دانش‌آموز باید متن باشد.',

            'father_name.required' => 'لطفاً نام پدر را وارد کنید.',
            'father_name.max' => 'نام پدر نمی‌تواند بیشتر از ۲۵۵ نویسه باشد.',
            'father_name.string' => 'نام پدر باید متن باشد.',

            'mother_full_name.required' => 'لطفاً نام و نام خانوادگی مادر را وارد کنید.',
            'mother_full_name.max' => 'نام مادر نمی‌تواند بیشتر از ۲۵۵ نویسه باشد.',
            'mother_full_name.string' => 'نام مادر باید متن باشد.',

            'national_code.required' => 'لطفاً کد ملی دانش‌آموز را وارد کنید.',
            'national_code.string' => 'کد ملی باید به صورت متن وارد شود.',
            'national_code.unique' => 'این کد ملی قبلاً در سامانه پیش‌ثبت‌نام شده است.',

            'grade.required' => 'لطفاً پایه تحصیلی را انتخاب کنید.',
            'grade.integer' => 'پایه تحصیلی انتخاب‌شده معتبر نیست.',
            'grade.in' => 'پایه تحصیلی باید یکی از پایه‌های دهم، یازدهم یا دوازدهم باشد.',

            'previous_year_average.required' => 'لطفاً معدل کل سال تحصیلی قبل را وارد کنید.',
            'previous_year_average.numeric' => 'معدل باید یک عدد باشد.',
            'previous_year_average.between' => 'معدل باید بین ۰ تا ۲۰ باشد.',
            'previous_year_average.decimal' => 'معدل حداکثر می‌تواند دو رقم اعشار داشته باشد.',

            'previous_year_discipline_score.required' => 'لطفاً نمره انضباط سال قبل را وارد کنید.',
            'previous_year_discipline_score.numeric' => 'نمره انضباط باید یک عدد باشد.',
            'previous_year_discipline_score.between' => 'نمره انضباط باید بین ۰ تا ۲۰ باشد.',
            'previous_year_discipline_score.decimal' => 'نمره انضباط حداکثر می‌تواند دو رقم اعشار داشته باشد.',

            'phone_number.required' => 'لطفاً شماره تلفن همراه را وارد کنید.',
            'phone_number.string' => 'شماره تلفن همراه معتبر نیست.',
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
            'student_full_name' => 'نام و نام خانوادگی دانش‌آموز',
            'father_name' => 'نام پدر',
            'mother_full_name' => 'نام و نام خانوادگی مادر',
            'national_code' => 'کد ملی دانش‌آموز',
            'grade' => 'پایه تحصیلی',
            'previous_year_average' => 'معدل کل سال تحصیلی قبل',
            'previous_year_discipline_score' => 'نمره انضباط سال قبل',
            'phone_number' => 'شماره تلفن همراه',
        ];
    }

    /**
     * Trim and collapse whitespace in a free-text value.
     */
    protected function cleanText(mixed $value): string
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return '';
        }

        return trim(preg_replace('/\s+/u', ' ', (string) $value) ?? '');
    }

    /**
     * Normalize a decimal value: Persian digits, Persian/Arabic decimal
     * separators and thousands separators are rewritten to plain ASCII.
     */
    protected function cleanDecimal(mixed $value): ?string
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $value = str_replace(['٫', '،', ','], '.', Digits::toEnglish((string) $value));

        return trim($value);
    }
}
