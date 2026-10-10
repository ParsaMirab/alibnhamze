@php($canManage = auth()->user()?->can('update', $schoolClass))
@php($editAssignmentId = $editAssignmentId ?? null)
@php($teacherOptions = collect($teachers ?? []))
@php($selectedTeacherId = (string) old('teacher_id', ''))
@php($selectedTeacher = $teacherOptions->first(fn ($teacher) => (string) $teacher->id === $selectedTeacherId))

<div class="flex flex-col gap-5">

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-lg text-mono">
                معلمان کلاس {{ $schoolClass->title }}
            </h2>

            <div class="text-sm text-secondary-foreground">
                لیست معلمان و درس‌های ثبت‌شده در این کلاس
            </div>
        </div>

        @if ($canManage)
            <button
                class="kt-btn kt-btn-primary"
                type="button"
                data-modal-open="addTeacherModal"
            >
                <i class="ki-filled ki-plus"></i>
                افزودن معلم
            </button>
        @endif
    </div>

    @error('subject')
        <div class="kt-alert kt-alert-destructive" role="alert">
            <i class="ki-filled ki-information-2 text-lg"></i>
            {{ $message }}
        </div>
    @enderror

    <div class="kt-card kt-card-grid min-w-full">
        <div class="kt-card-header py-5 flex-wrap gap-2">
            <div class="flex items-center gap-2.5">
                <h2 class="kt-card-title">
                    فهرست معلمان
                </h2>

                <span class="kt-badge kt-badge-outline">
                    {{ $schoolClass->teacherAssignments->count() }} مورد
                </span>
            </div>
        </div>

        <div class="kt-card-content">
            <div class="kt-scrollable-x-auto">
                <table class="kt-table kt-table-border">
                    <thead>
                    <tr>
                        <th class="w-[80px]">شناسه</th>
                        <th class="min-w-[180px]">نام معلم</th>
                        <th class="min-w-[150px]">درس</th>
                        <th class="min-w-[120px]">تاریخ افزودن</th>
                        <th class="w-[110px]"></th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($schoolClass->teacherAssignments as $assignment)
                        <tr>
                            <td class="font-medium text-mono">
                                #{{ $assignment->id }}
                            </td>

                            <td>
                                <span class="font-medium text-sm text-mono">
                                    {{ trim(($assignment->teacher?->first_name ?? '') . ' ' . ($assignment->teacher?->last_name ?? '')) ?: '—' }}
                                </span>
                            </td>

                            <td>
                                <span class="kt-badge kt-badge-outline kt-badge-primary">
                                    {{ $assignment->subject }}
                                </span>
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
                                            data-modal-open="editTeacherModal"
                                            data-edit-form="#editTeacherForm"
                                            data-action="{{ route('admin.classes.teachers.update', [$schoolClass, $assignment]) }}"
                                            data-fill='{"teacher_id": {{ $assignment->teacher_id }}, "subject": @json($assignment->subject)}'
                                            @if ((string) $editAssignmentId === (string) $assignment->id) data-auto-open="1" @endif
                                        >
                                            <i class="ki-filled ki-pencil"></i>
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.classes.teachers.destroy', [$schoolClass, $assignment]) }}"
                                            data-confirm-delete="آیا از حذف این معلم از کلاس مطمئن هستید؟"
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
                                        <i class="ki-filled ki-people"></i>
                                    </span>
                                    <div class="font-medium text-mono">
                                        معلمی ثبت نشده است
                                    </div>
                                    <p class="text-sm text-secondary-foreground">
                                        هیچ معلمی به این کلاس اضافه نشده است.
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
        {{-- افزودن معلم --}}
        <div class="admin-modal hidden" data-modal id="addTeacherModal" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="admin-modal-dialog">
                <div class="kt-card w-full">
                    <div class="kt-card-header py-5">
                        <h3 class="kt-card-title">
                            افزودن معلم به کلاس
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
                        action="{{ route('admin.classes.teachers.store', $schoolClass) }}"
                    >
                        @csrf

                        <div>
                            <label class="kt-form-label">
                                معلم
                                <span class="text-destructive">*</span>
                            </label>

                            @include('admin.classes.partials.combobox', [
                                'name' => 'teacher_id',
                                'placeholder' => 'جستجوی معلم با نام...',
                                'options' => $teacherOptions,
                                'selectedId' => $selectedTeacherId,
                                'selectedLabel' => $selectedTeacher ? $selectedTeacher->first_name.' '.$selectedTeacher->last_name : '',
                            ])
                        </div>

                        <div>
                            <label class="kt-form-label" for="add_subject">
                                درس
                                <span class="text-destructive">*</span>
                            </label>

                            <input
                                class="kt-input mt-2 @error('subject') border-destructive @enderror"
                                id="add_subject"
                                name="subject"
                                type="text"
                                maxlength="255"
                                placeholder="مثال: ریاضی"
                                value="{{ old('subject') }}"
                                required
                            >

                            @error('subject')
                                <span class="block mt-2 text-xs text-destructive">{{ $message }}</span>
                            @enderror
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

        {{-- ویرایش معلم --}}
        <div class="admin-modal hidden" data-modal id="editTeacherModal" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="admin-modal-dialog">
                <div class="kt-card w-full">
                    <div class="kt-card-header py-5">
                        <h3 class="kt-card-title">
                            ویرایش معلم کلاس
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
                        id="editTeacherForm"
                        method="POST"
                        action="{{ route('admin.classes.teachers.store', $schoolClass) }}"
                    >
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="kt-form-label">
                                معلم
                                <span class="text-destructive">*</span>
                            </label>

                            @include('admin.classes.partials.combobox', [
                                'name' => 'teacher_id',
                                'placeholder' => 'جستجوی معلم با نام...',
                                'options' => $teacherOptions,
                                'selectedId' => '',
                                'selectedLabel' => '',
                            ])
                        </div>

                        <div>
                            <label class="kt-form-label" for="edit_subject">
                                درس
                                <span class="text-destructive">*</span>
                            </label>

                            <input
                                class="kt-input mt-2"
                                id="edit_subject"
                                name="subject"
                                type="text"
                                maxlength="255"
                                required
                            >
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
