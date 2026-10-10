@php($canManage = auth()->user()?->can('update', $schoolClass))
@php($editAssignmentId = $editAssignmentId ?? null)
@php($studentOptions = collect($students ?? []))
@php($selectedStudentId = (string) old('student_id', ''))
@php($selectedStudent = $studentOptions->first(fn ($student) => (string) $student->id === $selectedStudentId))

<div class="flex flex-col gap-5">

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-lg text-mono">
                دانش‌آموزان کلاس {{ $schoolClass->title }}
            </h2>

            <div class="text-sm text-secondary-foreground">
                لیست دانش‌آموزان ثبت‌شده در این کلاس
            </div>
        </div>

        @if ($canManage)
            <button
                class="kt-btn kt-btn-primary"
                type="button"
                data-modal-open="addStudentModal"
            >
                <i class="ki-filled ki-plus"></i>
                افزودن دانش‌آموز
            </button>
        @endif
    </div>

    @error('student_id')
        <div class="kt-alert kt-alert-destructive" role="alert">
            <i class="ki-filled ki-information-2 text-lg"></i>
            {{ $message }}
        </div>
    @enderror

    <div class="kt-card kt-card-grid min-w-full">
        <div class="kt-card-header py-5 flex-wrap gap-2">
            <div class="flex items-center gap-2.5">
                <h2 class="kt-card-title">
                    فهرست دانش‌آموزان
                </h2>

                <span class="kt-badge kt-badge-outline">
                    {{ $schoolClass->studentAssignments->count() }} مورد
                </span>
            </div>
        </div>

        <div class="kt-card-content">
            <div class="kt-scrollable-x-auto">
                <table class="kt-table kt-table-border">
                    <thead>
                    <tr>
                        <th class="w-[80px]">شناسه</th>
                        <th class="min-w-[180px]">نام دانش‌آموز</th>
                        <th class="min-w-[130px]">کد ملی</th>
                        <th class="min-w-[120px]">تاریخ افزودن</th>
                        <th class="w-[110px]"></th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($schoolClass->studentAssignments as $assignment)
                        <tr>
                            <td class="font-medium text-mono">
                                #{{ $assignment->id }}
                            </td>

                            <td>
                                <span class="font-medium text-sm text-mono">
                                    {{ trim(($assignment->student?->first_name ?? '') . ' ' . ($assignment->student?->last_name ?? '')) ?: '—' }}
                                </span>
                            </td>

                            <td class="text-secondary-foreground" dir="ltr">
                                {{ $assignment->student?->national_code ?? '—' }}
                            </td>

                            <td>
                                <div class="flex flex-col gap-0.5">
                                    <span>{{ $assignment->created_at?->format('Y/m/d') }}</span>
                                    <span class="text-xs text-secondary-foreground">{{ $assignment->created_at?->format('H:i') }}</span>
                                </div>
                            </td>

                            <td>
                                @if ($canManage)
                                    <div class="flex items-center gap-1">
                                        <button
                                            class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost"
                                            type="button"
                                            title="ویرایش"
                                            aria-label="ویرایش"
                                            data-modal-open="editStudentModal"
                                            data-edit-form="#editStudentForm"
                                            data-action="{{ route('admin.classes.students.update', [$schoolClass, $assignment]) }}"
                                            data-fill='{"student_id": {{ $assignment->student_id }}}'
                                            @if ((string) $editAssignmentId === (string) $assignment->id) data-auto-open="1" @endif
                                        >
                                            <i class="ki-filled ki-pencil"></i>
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.classes.students.destroy', [$schoolClass, $assignment]) }}"
                                            data-confirm-delete="آیا از حذف این دانش‌آموز از کلاس مطمئن هستید؟"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost text-destructive"
                                                type="submit"
                                                title="حذف"
                                                aria-label="حذف"
                                            >
                                                <i class="ki-filled ki-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="flex flex-col items-center justify-center gap-3 py-14 text-center">
                                    <span class="flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary text-xl">
                                        <i class="ki-filled ki-user"></i>
                                    </span>
                                    <div class="font-medium text-mono">
                                        دانش‌آموزی ثبت نشده است
                                    </div>
                                    <p class="text-sm text-secondary-foreground">
                                        هیچ دانش‌آموزی به این کلاس اضافه نشده است.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if ($canManage)
    @push('modals')
        {{-- افزودن دانش‌آموز --}}
        <div class="admin-modal hidden" data-modal id="addStudentModal" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="admin-modal-dialog">
                <div class="kt-card w-full">
                    <div class="kt-card-header py-5">
                        <h3 class="kt-card-title">
                            افزودن دانش‌آموز به کلاس
                        </h3>

                        <button
                            class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost"
                            type="button"
                            data-modal-close
                            aria-label="بستن"
                        >
                            <i class="ki-filled ki-cross"></i>
                        </button>
                    </div>

                    <form
                        class="kt-card-content flex flex-col gap-5"
                        method="POST"
                        action="{{ route('admin.classes.students.store', $schoolClass) }}"
                    >
                        @csrf

                        <div>
                            <label class="kt-form-label">
                                دانش‌آموز
                                <span class="text-destructive">*</span>
                            </label>

                            @include('admin.classes.partials.combobox', [
                                'name' => 'student_id',
                                'placeholder' => 'جستجوی دانش‌آموز با نام یا کد ملی...',
                                'options' => $studentOptions,
                                'selectedId' => $selectedStudentId,
                                'selectedLabel' => $selectedStudent ? $selectedStudent->first_name.' '.$selectedStudent->last_name : '',
                            ])
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <button class="kt-btn kt-btn-outline" type="button" data-modal-close>
                                انصراف
                            </button>

                            <button class="kt-btn kt-btn-primary" type="submit">
                                <i class="ki-filled ki-check"></i>
                                افزودن
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ویرایش دانش‌آموز --}}
        <div class="admin-modal hidden" data-modal id="editStudentModal" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="admin-modal-dialog">
                <div class="kt-card w-full">
                    <div class="kt-card-header py-5">
                        <h3 class="kt-card-title">
                            ویرایش دانش‌آموز کلاس
                        </h3>

                        <button
                            class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost"
                            type="button"
                            data-modal-close
                            aria-label="بستن"
                        >
                            <i class="ki-filled ki-cross"></i>
                        </button>
                    </div>

                    <form
                        class="kt-card-content flex flex-col gap-5"
                        id="editStudentForm"
                        method="POST"
                        action="{{ route('admin.classes.students.store', $schoolClass) }}"
                    >
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="kt-form-label">
                                دانش‌آموز
                                <span class="text-destructive">*</span>
                            </label>

                            @include('admin.classes.partials.combobox', [
                                'name' => 'student_id',
                                'placeholder' => 'جستجوی دانش‌آموز با نام یا کد ملی...',
                                'options' => $studentOptions,
                                'selectedId' => '',
                                'selectedLabel' => '',
                            ])
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <button class="kt-btn kt-btn-outline" type="button" data-modal-close>
                                انصراف
                            </button>

                            <button class="kt-btn kt-btn-primary" type="submit">
                                <i class="ki-filled ki-check"></i>
                                ذخیره تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush
@endif
