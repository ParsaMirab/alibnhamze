<?php

namespace App\Http\Requests\Admin;

use App\Enums\RankEnum;
use App\Models\StudentParent;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class StudentRequest extends FormRequest
{
    /**
     * Authorization is enforced in the controller through the StudentPolicy.
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
        $ignore = $this->ignoredStudentId();

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'national_code' => ['required', 'string', 'max:10', Rule::unique('users', 'national_code')->ignore($ignore)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($ignore)],
            'grade' => ['required', 'integer', 'in:10,11,12'],

            'father_alive' => ['nullable', 'boolean'],
            'father_first_name' => ['nullable', 'required_if:father_alive,1', 'string', 'max:255'],
            'father_last_name' => ['nullable', 'required_if:father_alive,1', 'string', 'max:255'],
            'father_national_code' => ['nullable', 'required_if:father_alive,1', 'string', 'max:10'],
            'father_phone' => ['nullable', 'string', 'max:20'],

            'mother_alive' => ['nullable', 'boolean'],
            'mother_first_name' => ['nullable', 'required_if:mother_alive,1', 'string', 'max:255'],
            'mother_last_name' => ['nullable', 'required_if:mother_alive,1', 'string', 'max:255'],
            'mother_national_code' => ['nullable', 'required_if:mother_alive,1', 'string', 'max:10'],
            'mother_phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Cross-field validation for the parent sections.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $this->validateParentSections($validator);
            },
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
            'first_name.required' => 'لطفاً نام دانش‌آموز را وارد کنید.',
            'last_name.required' => 'لطفاً نام خانوادگی دانش‌آموز را وارد کنید.',
            'national_code.required' => 'لطفاً کد ملی دانش‌آموز را وارد کنید.',
            'national_code.unique' => 'این کد ملی قبلاً در سامانه ثبت شده است.',
            'phone.unique' => 'این شماره تلفن قبلاً در سامانه ثبت شده است.',
            'grade.required' => 'لطفاً پایه تحصیلی را انتخاب کنید.',
            'grade.in' => 'پایه تحصیلی باید 10، 11 یا 12 باشد.',

            'father_first_name.required_if' => 'لطفاً نام پدر را وارد کنید.',
            'father_last_name.required_if' => 'لطفاً نام خانوادگی پدر را وارد کنید.',
            'father_national_code.required_if' => 'لطفاً کد ملی پدر را وارد کنید.',
            'mother_first_name.required_if' => 'لطفاً نام مادر را وارد کنید.',
            'mother_last_name.required_if' => 'لطفاً نام خانوادگی مادر را وارد کنید.',
            'mother_national_code.required_if' => 'لطفاً کد ملی مادر را وارد کنید.',
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
            'first_name' => 'نام دانش‌آموز',
            'last_name' => 'نام خانوادگی دانش‌آموز',
            'national_code' => 'کد ملی دانش‌آموز',
            'phone' => 'شماره تلفن دانش‌آموز',
            'grade' => 'پایه تحصیلی',
        ];
    }

    /**
     * The primary key of the student being updated (if any).
     */
    protected function ignoredStudentId(): ?int
    {
        $student = $this->route('student');

        return $student instanceof User ? $student->getKey() : null;
    }

    /**
     * Whether the given parent section is toggled on.
     */
    protected function parentIsAlive(string $type): bool
    {
        return (string) $this->input("{$type}_alive", '0') === '1';
    }

    /**
     * Validate the parent sections: rank eligibility, shared national codes,
     * unique phone numbers and the relationship between them.
     */
    protected function validateParentSections(Validator $validator): void
    {
        $studentNationalCode = trim((string) $this->input('national_code'));
        $studentPhone = trim((string) $this->input('phone', ''));
        $ignoreId = $this->ignoredStudentId();

        $seenNationalCodes = [];
        $seenPhones = [];

        if ($studentPhone !== '') {
            $seenPhones[$studentPhone] = 'phone';
        }

        foreach (StudentParent::types() as $type) {
            if (! $this->parentIsAlive($type)) {
                continue;
            }

            $codeField = "{$type}_national_code";
            $phoneField = "{$type}_phone";

            $code = trim((string) $this->input($codeField, ''));
            $phone = trim((string) $this->input($phoneField, ''));

            if ($code === '') {
                // The required rule already reports this case.
                continue;
            }

            if ($code === $studentNationalCode) {
                $validator->errors()->add($codeField, 'کد ملی ولی نمی‌تواند با کد ملی دانش‌آموز یکسان باشد.');

                continue;
            }

            if (isset($seenNationalCodes[$code])) {
                $validator->errors()->add($codeField, 'کد ملی پدر و مادر نمی‌تواند یکسان باشد.');

                continue;
            }

            $seenNationalCodes[$code] = $type;

            $existingParent = User::query()->where('national_code', $code)->first();

            if ($existingParent && $existingParent->rank !== RankEnum::PARENT->value) {
                $validator->errors()->add(
                    $codeField,
                    'این کد ملی متعلق به کاربری است که نمی‌تواند به عنوان ولی دانش‌آموز ثبت شود.'
                );

                continue;
            }

            if ($phone === '') {
                continue;
            }

            if (isset($seenPhones[$phone])) {
                $validator->errors()->add($phoneField, 'این شماره تلفن برای بیش از یک نفر وارد شده است.');

                continue;
            }

            $seenPhones[$phone] = $phoneField;

            $phoneOwner = User::query()->where('phone', $phone)->first();

            $belongsToSameParent = $existingParent && (int) $phoneOwner?->getKey() === (int) $existingParent->getKey();
            $belongsToCurrentStudent = $ignoreId !== null && (int) $phoneOwner?->getKey() === $ignoreId;

            if ($phoneOwner && ! $belongsToSameParent && ! $belongsToCurrentStudent) {
                $validator->errors()->add($phoneField, 'این شماره تلفن متعلق به حساب کاربری دیگری است.');
            }
        }
    }
}
